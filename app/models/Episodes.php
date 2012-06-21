<?php
    /**
* Positions.php
*
* The model for the positions table
*
* @author      Nikos Dimopoulos <nikos@niden.net>
* @since       2012-06-21
* @category    Models
* @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
*
*/

class Episodes extends Phalcon_Model_Base
{
    /**
* @var integer
*/
    public $id;

    /**
     * @var string
     */
    public $number;

    /**
     * @var string
     */
    public $summary;

    /**
     * @var integer
     */
    public $air_date;

    /**
     * @param array $parameters
     *
     * @static
     * @return Phalcon_Model_Resultset Episodes[]
     */
    static public function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return  Phalcon_Model_Base   Episodes
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}