<?php

class Model {
    protected $_db;

    public function __construct( $db ) {
        $this->_db = $db;
    }

    private function prepareStatementForQuery( $query ) {
    	$conn = $this->_db->getConnection();
    	$statement = $conn->stmt_init();
        $statement->prepare( query );

        return $statement;    	
    }
}
