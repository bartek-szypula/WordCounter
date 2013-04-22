<?php

require 'config.php';

class PDOClass {

	protected $_conn;

	public function __construct()
	{
		$this->_conn = new PDO(DB_DNS, DB_USERNAME, DB_PASSWORD);

		$this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	}

}