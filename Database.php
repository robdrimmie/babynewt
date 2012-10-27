<?php

class Database {
    protected $_name;
    protected $_user;
    protected $_password;
    protected $_host;
    protected $_oldConnection;
    protected $_connection;

    public function __construct( $dbProperties = array(), $old = false, $new = true ) {
        $defaults = array(
            'name' => 'babynewt',
            'user' => 'babynewt',
            'password' => 'babynewt',
            'host' =>'localhost',
        );

        $merged = array_merge( $defaults, $dbProperties );

        $this->_name = $merged['name'];
        $this->_user = $merged['user'];
        $this->_password = $merged['password'];
        $this->_host = $merged['host'];

        $this->_oldConnection = null;
        $this->_connection = null;

        if( $old ) {
            $this->getOldConnection();
        }

        if( $new ) {
            $this->getConnection();
        }
    }


    protected function _connectOld() {
        if ( null === $this->_oldConnection ) {
            $this->_oldConnection = mysql_connect( 
                $this->_host, 
                $this->_user, 
                $this->_password 
            );

            if ( !mysql_select_db( $this->_name, $this->_oldConnection ) ) {
                throw new Exception( 
                    'Unable to select old database: [' . mysql_errno() . 
                    ']' . mysql_error() 
                );
            }
        }

        return $this->_oldConnection;
    }

    protected function _connect() {
        if ( null === $this->_connection ) {
            $this->_connection = new mysqli( 
                $this->_host, 
                $this->_user, 
                $this->_password,
                $this->_name
            );

            if ( $this->_connection->connect_errno ) {
                throw new Exception( 
                    'Unable to select  database: [' . $this->_connection->connect_errno . 
                    ']' . $this->_connection->connect_error
                );
            }
        }

        return $this->_connection;
    }

    public function getOldConnection() {
        return $this->_connectOld();
    }

    public function getConnection() {
        return $this->_connect();
    }


    /**
     * @see http://www.askbee.net/articles/php/SQL_Injection/sql_injection.html
     */
    public static function sql_quote( $value ) {

        if ( get_magic_quotes_gpc() ) {
            $value = stripslashes( $value );
        }
        // check if this function exists
        if ( function_exists( "mysql_real_escape_string" ) ) {
            $value = mysql_real_escape_string( $value );
        }
        // for PHP version < 4.3.0 use addslashes
        else {
            $value = addslashes( $value );
        }
        return $value;
    }

}
