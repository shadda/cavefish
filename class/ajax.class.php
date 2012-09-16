<?php
	
	namespace Ajax;

	use \Controller as Controller;

	abstract class Ajax extends Controller
	{
		protected $HoneyPot  = array();
		protected $UseDB = true;

		public static $Remember = false;

		protected function __construct(array $params = array())
		{	
			parent::__construct($params);

			$this->HoneyPot = array(
				'status' => Controller::STATUS_OK,
				'errors' => array(),
				'data' => array()
			);
		}

		public function Call($action)
		{
			try 
			{
				if(method_exists($this, $action)) 
				{
					$params = $this->request;
					$this->HoneyPot['data'] = $this->$action();
				}
				else 
				{
					throw new \Exception('Handler does not exist');
				}
					
			} catch(Exception $e) {
				
				$this->HoneyPot['errors'][] = $e->getMessage();	
			}
		}

		public function Render()
		{	
			$this->HoneyPot['status'] = self::$status;
			$this->HoneyPot['errors'] = $this->errors;
			$this->Response = json_encode($this->HoneyPot);

			return parent::Render();
		}
	}
