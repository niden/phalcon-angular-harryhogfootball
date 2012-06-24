<?php
/**
 * Registry.php
 * niden_Registry
 *
 * Registry pattern implementation
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       6/24/12
 * @category    Library
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class niden_Registry extends ArrayObject
{
    /**
     * The core of the registry, one object to rule them all
     * @var null
     */
    private static $_registry = null;

    /**
     * Constructs a parent ArrayObject with default
     * ARRAY_AS_PROPS to allow access as an object
     *
     * @param array $array data array
     * @param integer $flags ArrayObject flags
     */
    public function __construct(
        $array = array(),
        $flags = parent::ARRAY_AS_PROPS
    )
    {
        parent::__construct($array, $flags);
    }

    /**
     * Singleton implementation on getting the same instance all the time
     *
     * @static
     * @return null
     */
    public static function getInstance()
    {
        if (self::$_registry === null) {
            self::$_registry = new niden_Registry();
        }

        return self::$_registry;
    }

    /**
     * Getter
     *
     * @static
     * @param  string $index The index key
     * @return mixed
     * @throws niden_Exception if no data stored in the registry
     */
    public static function get($index)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index)) {
            throw new niden_Exception(
                "No entry is registered for key '$index'"
            );
        }

        return $instance->offsetGet($index);
    }

    /**
     * Setter
     *
     * @static
     * @param string $index The key of the index
     * @param mixed  $value The value of the item
     */
    public static function set($index, $value)
    {
        $instance = self::getInstance();
        $instance->offsetSet($index, $value);
    }

    /**
     * Returns true if the index has been set otherwise false
     * @static
     * @param  string $index The index key
     * @return bool
     */
    public static function isRegistered($index)
    {
        if (self::$_registry === null) {
            return false;
        }
        return self::$_registry->offsetExists($index);
    }

    /**
     * Workaround for http://bugs.php.net/bug.php?id=40442
     *
     * @param  string $index The index key
     * @return mixed
     */
    public function offsetExists($index)
    {
        return array_key_exists($index, $this);
    }

}