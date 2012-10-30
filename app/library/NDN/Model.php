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

use \Phalcon\DI\FactoryDefault as Di;


class Model extends \Phalcon\Mvc\Model
{
    protected $behaviors = array();

    /**
     * Adds a behavior in the model
     *
     * @param $behavior
     */
    public function addBehavior($behavior)
    {
        $this->behaviors[$behavior] = true;
    }

    public function beforeSave()
    {
        $path = dirname(__FILE__);
        $di   = Di::getDefault();

        foreach ($this->behaviors as $behavior => $active)
        {
            if ($active && $di->has($behavior))
            {
                $di->get($behavior)->beforeSave($this);
            }
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