<?php
/**
 * Positions.php
 * Positions
 *
 * The model for the positions table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Positions extends Phalcon_Model_Base
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $position;

    /**
     * @param array $parameters
     *
     * @static
     * @return Phalcon_Model_Resultset Positions[]
     */
    static public function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return  Phalcon_Model_Base   Positions
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}

