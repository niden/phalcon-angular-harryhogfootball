<?php
/**
 * Model.php
 * NDN_Model
 *
 * The base model for all tables
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN;

class Model extends \Phalcon\Mvc\Model
{
    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var integer
     */
    public $createdAtUserId;

    /**
     * @var string
     */
    public $lastUpdate;

    /**
     * @var integer
     */
    public $lastUpdateUserId;

    /**
     * beforeSave hook - called prior to any Save (insert/update)
     */
    public function beforeSave()
    {
        if (empty($this->createdAtUserId)) {
            $auth     = Session::get('auth');
            $datetime = date('Y-m-d H:i:s');

            $this->createdAt        = $datetime;
            $this->createdAtUserId  = (int) $auth['id'];
        }
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return Phalcon_Model_Resultset Model[]
     */
    static public function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return  Phalcon_Model_Base   Models
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}