<?php

class Model
{
	protected $_db;
	
	public function __construct( $db ) {
		$this->_db = $db;
	}
}