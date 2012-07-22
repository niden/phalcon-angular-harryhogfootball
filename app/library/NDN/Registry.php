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
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN;

class Registry extends \ArrayObject
{
    /**
     * The core of the registry, one object to rule them all
     * @var null
     */
    private static $_registry = null;

    /**
     * A prefix for the registry keys. This allows using the same object
     * but with different prefixes that could effectively be different
     * registries
     *
     * @var string
     */
    protected static $_prefix = '';

    /**
     * Constructs a parent ArrayObject with default ARRAY_AS_PROPS to allow
     * access as an object
     *
     * @param array   $array Array with data
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
            self::init();
        }

        return self::$_registry;
    }

    /**
     * Getter
     *
     * @static
     *
     * @param  string $index The index key
     *
     * @return mixed
     * @throws \NDN\Exception if no data stored in the registry
     */
    public static function get($index)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists(self::$_prefix .  $index)) {
            throw new Exception(
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
        $instance->offsetSet(self::$_prefix .  $index, $value);
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
        return self::$_registry->offsetExists(self::$_prefix .  $index);
    }

    /**
     * Clears the internal storage object and generates a new one
     *
     * @static
     */
    public static function clear()
    {
        self::$_registry = null;
        self::init();
    }

    /**
     * Workaround for http://bugs.php.net/bug.php?id=40442
     *
     * @param  string $index The index key
     * @return mixed
     */
    public function offsetExists($index)
    {
        return array_key_exists(self::$_prefix .  $index, $this);
    }

    /**
     * Initializes the object
     *
     * @static
     */
    protected static function init()
    {
        self::$_registry = new Registry();
    }
}