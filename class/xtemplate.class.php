<?php
	
	class XD extends \DOMDocument
	{
		public function __construct($version = '1.0', $encoding = 'UTF-8')
		{
			parent::__construct($version, $encoding);
			$this->registerNodeClass('DOMElement', 'XE');
		}
	}

	class XE extends \DOMElement
	{
		public function __set($key, $val)
		{
			if(!isset($this->$key))
			{
				parent::setAttribute($key, $val);
			}
		}

		public function setAttribute($key, $val)
		{
			parent::setAttribute($key, $val);
			return $this; #Useful for chaining
		}

		public function error($mixed, $msg = false) 
		{
			if(!isset($this->errors)) 
			{	
				$this->errors = $this->appendChild( new DOMElement('errors') );
			}
			
				
			$key = $msg ? $mixed : count(X::$errorList);	
			X::$errorList[] = array('key'=>$key, 'error' => $msg);
		
			$message = $msg ? $msg : $mixed;
			X::T($message);
			
			$e = $this->errors->AppendChild( new XE('error', $message) );
			
			if($msg) 
			{	
				$e->field = $mixed;
			}
		}
		
		public function getErrors() 
		{	
			return X::$errorList;	
		}
		
		public function success($msg) 
		{	
			$this->appendChild( new DOMElement('success', $msg) );	
		}
		
		public function notice($msg) {
			
			$this->appendChild( new DOMElement('notice', $msg) );
			
		}
		
		public function AC(DOMElement $node) {
			
			return $this->appendChild( $node );
			
		}
		
		public function AR($aNode, $attr = true) {
			
			X::T($aNode);
			
			foreach($aNode as $key=>$val) 
			{
				$val = Tools::Coalesce($val, '');
			
				if(is_array($val) || is_object($val)) 
				{	
					if(is_numeric($key)) 
					{
						$key = 'X_' . $key;
					}
					
					$n = $this->AC( new XE($key) );
					$n->AR($val);	
				} 
				else if(is_int($key)) 
				{
					if(substr($this->nodeName, -1) == 's') 
					{
						$key = substr($this->nodeName, 0, -1);
					} 
					else 
					{
						if(!ctype_alpha($val)) 
						{
							$key = 'Node';
						}
						else 
						{
							$key = $val;
						}
					}
					
					if($attr)
					{
						$this->$key = $val;
					} 
					else 
					{
						$this->AppendChild( new XE($key, (string) $val) );
					}
				} 
				else 
				{		
					if($attr) 
					{	
						$this->$key = $val;	
					} 
					else 
					{	
						$this->AppendChild( new XE($key, (string) $val) );
					}
					
				}
			}
			
			return $this;
		}
		
		public function __get($key)
		{
			if(!isset($this->$key))
			{
				return parent::getAttribute($key);
			}
		}

		public function __isset($key) 
		{
			if($this->hasAttribute($key)) 
			{ 
				return false;
			} 
			else if(isset($this->$key)) 
			{
				return true;
			}	
		}
	}

	class X 
	{
		public static $errorList = array();

		public static function T(&$arg) 
		{
			if(is_array($arg) || is_object($arg)) 
			{
				foreach($arg as $key=>&$val) 
				{
					self::T($val);
				}
			} 
			else 
			{
				$arg = htmlentities( (string) $arg);		
			}
		}
	
		public static function Translate(XD $doc, $template) 
		{	
			if(!file_exists($template)) 
			{	
				$template = 'templates/base.xsl';	
			}

			if ( Config::Get('USE_XSLCACHE') )
			{
				$xsl = new XSLTCache();
				$xsl->registerPHPFunctions();
				$xsl->importStyleSheet($template);
				return $xsl->transformToXML($doc);
			}

		            $t = new XD;
		            $t->load($template);
		            $xsl = new XSLTProcessor();
		            $xsl->importStyleSheet($t);
		            return $xsl->transformToXML($doc);
		}
	}
