<?php
/**
 * Users.php
 * Users
 *
 * The model for the users table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Users extends Phalcon_Model_Base
{
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
     * @var string
     */
    public $name;

    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $this->belongsTo('userId', 'Scoring', 'id');
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return Phalcon_Model_Resultset Users[]
     */
    static public function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return  Phalcon_Model_Base   Users
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}