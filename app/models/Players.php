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

class Players extends Phalcon_Model_Base
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $positionId;

    /**
     * @var string
     */
    public $name;

    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $message = 'This record cannot be deleted because of referential '
                 . 'integrity rules';
        $fk = array('foreignKey' => array('message' => $message));

        $this->hasOne('positionId', 'Positions', 'id', $fk);
    }

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