<?php
/**
 * Players.php
 * Players
 *
 * The model for the players table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Players extends Phalcon_Model_Base
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
     * @var integer
     */
    public $active;

    /**
     * @var string
     */
    public $team;

    /**
     * @param array $parameters
     *
     * @static
     * @return Phalcon_Model_Resultset Players[]
     */
    static public function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return  Phalcon_Model_Base   Players
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}