<?php
/**
 * Scoring.php
 *
 * The model for the scoring table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Scoring extends Phalcon_Model_Base
{

    /**
    * @var integer
    */
    public $id;

    /**
    * @var integer
    */
    public $episode_id;

    /**
    * @var integer
    */
    public $gameball;

    /**
    * @var integer
    */
    public $user_id;

    /**
    * @var integer
    */
    public $player_id;

    /**
     *
     */
    public function initialize()
    {
        $message = array(
            'foreignKey' => array(
                'message' => 'This record cannot be deleted because of referential integrity rules'
            )
        );
        $this->hasOne('user_id', 'Users', 'id', $message);
    }

    /**
     * @param array $parameters
     *
     * @static
     *
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


