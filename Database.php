<?php

class Database
{
    protected $_name;
    protected $_user;
    protected $_password;
    protected $_host;
    protected $_conn;

    public function __construct( $connect = true ) {
        $this->_name = 'babynewt';
        $this->_user = 'root';
        $this->_password = '';
        $this->_host = '127.0.0.1';

        $this->_conn = null;

        if ( $connect ) {
            $this->getConnection();
        }
    }


    protected function _connect() {
        if ( null === $this->_conn ) {
            $this->_conn = mysql_connect( $this->_host, $this->_user, $this->_password );

            if ( !mysql_select_db( $this->_name, $this->_conn ) ) {
                throw new Exception( 'Unable to select database: [' . mysql_errno() . ']' . mysql_error() );
            }
        }

        return $this->_conn;
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
