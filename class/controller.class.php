<?php

	abstract class Controller 
	{
		const STATUS_OK = '200 OK';
		const STATUS_ERROR = '500 Internal Server Error';

		protected static $status = Controller::STATUS_OK;
		
		protected static $REQUEST = array();
		public static $Remember = true;

		protected $request = array();
		protected $response_headers = array();
		protected $tasks = array();
		protected $errors = array();

		protected $db;

		protected $className;
		protected $method;
		protected $token;

		/**
		 * @var	$Response	string	The final response sent to the client
		 */		
		protected $Response;

		/**
		 * @var	$LoginRequired	boolean	Does this action require the user to be logged in
		 */
		protected $LoginRequired = false;

		/**
		 * @var	$Secure	boolean	Should this page require SSL
		 */
		protected $Secure = false;

		/**
		 * @var	$Debug	boolean	If true, output raw XML
		 */
		protected static $Debug = false;

		/**
		 * @var	$UseDB	boolean	Does this request need to connect to the database
		 */
		protected $UseDB = true;

		protected function __construct(array $params)
		{	
			Controller::$REQUEST = array_filter($params);
			$this->request = &Controller::$REQUEST;
			$this->token = uniqid();
			$this->session_id = session_id();

			if(!$this->Secure && isset($_SERVER['HTTPS']))
			{
				header('Location: http://'.Tools.getCurrentURL(false));
				exit;
			}
			else if($this->Secure && !isset($_SERVER['HTTPS']))
			{
				header('Location: https://'.Tools.getCurrentURL(false));
				exit;	
			}

			if($this->UseDB)
			{
				$this->db = \Database\PostgreSQL::GetInstance();
			}

			if($this->LoginRequired && empty($_SESSION['user']->user_id))
			{
				$this->GoToPage('Login');
			}

			$this->className = strtolower( get_class($this) );

			if(method_exists($this, 'Init'))
			{
				$this->Init();
			}
		}

		protected function _add_response_header($header)
		{
			$this->response_headers[] = $header;
		}

		protected function _add_response_headers(array $header_list)
		{
			$this->response_headers += $header_list;
		}

		protected function _send_response_headers()
		{
			foreach($this->response_headers as $header)
			{
				header($header);
			}
		}

		protected function error($message)
		{
			$this->errors[] = $message;
		}

		protected function isErrors($message)
		{
			return count($this->errors) > 0 ? true : false;
		}

		protected function GoToPage($page, $action=null)
		{
			header('Location: '.Tools::GetPageURL($page, $action));
			exit;
		}

		public function Render()
		{	
			$this->_add_response_header(self::$status);
			$this->_send_response_headers();

			return $this->Response;
		}

		public function setMethod($method)
		{
			$this->method = $method;
		}

		public function Call($action)
		{
			$this->$action();
		}

		public static function Load($page, $action, $params = array()) 
		{	
			if(class_exists($page)) 
			{
				$page = new $page($params);
				$page->setMethod($action);

				if(is_callable( array($page, $action) )) 
				{
					$page->Call($action);
					return $page;	
				}
			} 
			
			$page = new \Pages\PageNotFound($params);
			$page->__Default($action);
				
			return $page;
		}
	}