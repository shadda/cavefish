<?php
	
	namespace Database;

	use \PDO as PDO;
	use \XE as XE;

	abstract class Database extends PDO
	{
		public function __construct($dsn, $user, $password)
		{
			parent::__construct($dsn, $user, $password);
			parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		}
	}

	class Row 
	{
		protected $_data = array();
		protected $db;

		public function __construct(array $data)
		{
			$this->_data = $data;
		}

		public function __get($key)
		{
			if(array_key_exists($this->_data, $key))
			{
				return $this->_data[$key];
			}
		}

		public function __set($key, $value)
		{
			$this->_data[$key] = $value;
		}

		public function __toArray()
		{
			return $this->_data;
		}

		public function __toJSON()
		{
			return json_encode($this->_data);
		}

		public function __toNode()
		{
			$x_row = new XE('row');
			$x_row->AR($this->_data);

			return $x_row;
		}
	}