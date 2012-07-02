<?php
/**
 * Contacts.php
 * Contacts
 *
 * The model for the contacts table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Contacts extends Phalcon_Model_Base
{

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $comments;

    /**
     * @var integer
     */
    public $createdAt;

    /**
     * Validations and business logic 
     */
    public function validation()
    {
        $this->validate(
            'Email',
            array(
                'field' => 'email',
                'required' => true
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return Phalcon_Model_Resultset Contacts[]
     */
    static public function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return  Phalcon_Model_Base   Contacts
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}

