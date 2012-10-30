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
    protected $behaviors = array();

    public function setBehavior($behavior)
    {
        $this->behaviors[$behavior] = true;
    }

    public function beforeSave()
    {
        $path = dirname(__FILE__);

        foreach ($this->behaviors as $behavior => $active)
        {
            if ($active &&
                file_exists($path . '/Models/Behaviors/' . $behavior . '.php'))
            {
                $className = '\NDN\Models\Behaviors\\' . $behavior;
                $class     = new $className;

                $class->beforeSave($this);
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