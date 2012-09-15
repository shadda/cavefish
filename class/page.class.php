<?php

	namespace Pages;

	use \XD as XD;
	use \XE as XE;
	use \X as X;

	abstract class Page extends \Controller
	{
		private $doc;
		protected $page;
		protected $sub;
		protected $nodes = array();

		protected $template = false;
		protected $index; 
		protected $headers;
		protected $session;

		protected $scripts = array();
		protected $stylesheets = array();

		protected $post;
		protected $pageName;

		protected function __construct(array $params)
		{
			parent::__construct($params);

			$this->doc = new XD;
			$this->className = strtolower(get_class($this));
			$this->className = substr($this->className, strrpos($this->className, '\\') + 1);

			$this->index = $this->doc->appendChild( new XE('index') );
			$this->headers = $this->index->AC( new XE('headers') );
			$this->session = $this->index->AC( new XE('session') );
			$this->user_preferences = $this->index->AC( new XE('user_preferences') );
			$this->page = $this->index->AC( new XE($this->className) );

			$this->scripts = array();

			$this->addScript(
				array(
					'jquery.min.js', 
					'common.js',
					'pages/' . $this->className . '.js'
				)
			);

			$this->addStyleSheet(
				array(
					'main.css', 
					'pages/' . $this->className . '.css'
				)
			);
		}

		protected function addHeader($key, $val)
		{
			$key = htmlentities($key);
			$val = htmlentities($val);

			$this->headers->AC( new XE($key, $val) );
		}

		protected function addScript($mixed)
		{
			if(is_array($mixed))
			{
				foreach($mixed as $js)
				{
					if(strpos($js, 'http') !== 0)
						$js = "script/$js";

					$this->scripts[] = $js;
				}
				return;
			}
			$this->scripts[] = $mixed;
		}

		protected function addStyleSheet($mixed)
		{
			if(is_array($mixed))
			{
				foreach($mixed as $css)
				{
					if(strpos($css, 'http') !== 0)
						$css= "css/$css";

					$this->stylesheets[] = $css;
				}
				return;
			}
			$this->stylesheets[] = $mixed;
		}

		protected function setTitle($title)
		{
			$title = htmlentities($title);
			$this->addHeader('title', $title);
		}

		protected function setName($name)
		{
			$this->pageName = htmlentities($name);
		}

		protected function setTemplate($template)
		{
			if(file_exists($template))
			{
				return $this->template = $template;
			}

			throw new \Exceptions\PageError("Template \"$template\" does not exist.");
		}

		protected function getTemplate($class, $method = null)
		{
			if($this->template)
				return $this->template;

			$path = sprintf("templates/%s/%s_%s.xsl", $class, $class, $method);
			$path = strtolower($path);

			if(file_exists($path))
				return $path;

			return "templates/$class/$class.xsl";
		}

		protected function FlipBack()
		{
			$location = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : \Tools::GetBaseURL();
			header('Location: '. $location);
		}

		public function Render($render = true)
		{
			$this->addHeader('base', \Tools::GetBaseURL());
			$this->addHeader('class_name', $this->className);
			$this->addHeader('session_id', $this->session_id);
			$this->addHeader('self', \Tools::GetBaseURL() . get_class($this));
			$this->addHeader('uri', X::T($_SERVER['REQUEST_URI']));
			$this->addHeader('token', $_SESSION['token']);

			$crumbs = array();

			if(strtolower($this->className) != 'defaultpage')
			{
				$crums[] = ucwords($this->className);
			}

			$method = $this->method;

			if($this->method == '_Default')
			{
				$method = 'Main';
			}

			$this->addHeader('sub_content', ucwords($method));
			$this->addHeader('breadcrumbs', implode(' / ', $crumbs));

			$_tmp_session = $_SESSION;

			unset($_tmp_session['cache'], $_tmp_session['CACHE_TOKEN']);

			$this->session->AR($_tmp_session);
			$this->post = $this->index->AC( new XE('post', json_encode($_REQUEST)) );
			$this->errors = $this->index->AC( new XE('errors', json_encode($this->errors)) );

			$this->scripts = $this->index->AC( new XE('scripts', implode('|', $this->scripts)) );
			$this->stylesheets = $this->index->AC( new XE('stylesheets', implode('|', $this->stylesheets)) );

			if(!$render || self::$Debug)
			{
				header('Content-Type: text/xml');
				echo $this->doc->saveXML();
				return;
			}

			$this->_add_response_header('Content-type: text/html; charset=UTF-8');

			$class = \Tools::GetClassName($this);
			$template = $this->getTemplate($class, $this->method);
			
			$this->Response = X::Translate($this->doc, $template);

			return parent::Render();
		}
	}