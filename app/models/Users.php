<?php
/**
 * Users.php
 *
 * The model for the users table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT -
 *
 */

class Users extends Phalcon_Model_Base {

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;


    /**
     * @static
     * @param   array                   $parameters
     * @return  Phalcon_Model_Resultset Users[]
     */
    static public function find($parameters=array()){
        return parent::find($parameters);
    }

    /**
     * @static
     * @param   array               $parameters
     * @return  Phalcon_Model_Base   Users
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}

