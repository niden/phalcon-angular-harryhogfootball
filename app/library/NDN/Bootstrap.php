<?php
/**
 * Bootstrap.php
 * Bootstrap
 *
 * Bootstraps the application
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       7/22/12
 * @category    Library
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN;

class Bootstrap
{
    public static function run($options)
    {
        // Initialize everything - Order does matter
        self::initRegistry($options);
        self::initConfig($options);
        self::initEnvironment($options);
        self::initTimezone($options);
        self::initCache($options);
        self::initLogger($options);
        self::initDebug($options);
        self::initDatabase($options);
        self::initSession($options);

        // Run the controller
        $front = self::initFrontController($options);

        return $front->dispatchLoop()->getContent();
    }

    // Protected functions

    protected function initFrontController($options = array())
    {
        $config = Registry::get('config');
        $front  = \Phalcon\Controller\Front::getInstance();
        $front->setConfig($config);

        return $front;
    }

    protected function initEnvironment($options = array())
    {
        // Setting some settings based on the environment
        $config = Registry::get('config');

        $debug = (isset($config->debug)) ? (bool) $config->debug : false;

        if ($debug) {
            ini_set('display_errors', true);
            error_reporting( -1 );
        } else {
            ini_set('display_errors', false);
//            error_reporting( -1 );
        }

        set_error_handler(array('\NDN\Error', 'normal'));
        set_exception_handler(array('\NDN\Error', 'exception'));
        register_shutdown_function(array('\NDN\Error', 'shutdown'));

        // This is used only for nginx.
        if (isset($_GET["_url"])) {
            $_GET["_url"] = preg_replace("#^/#", "", $_GET["_url"]);
        }

    }

    protected function initRegistry($options = array())
    {
        Registry::clear();
    }

    protected function initConfig($options = array())
    {
        $configFile = ROOT_PATH . '/app/config/config.ini';

        // Create the new object
        $config = new \Phalcon\Config\Adapter\Ini($configFile);

        // Store it in the registry
        Registry::set('config', $config);
    }

    protected static function initTimezone($options = array())
    {
        $config   = Registry::get('config');
        $timezone = (isset($config->timezone)) ? $config->timezone : 'US/Eastern';

        date_default_timezone_set($timezone);

        Registry::set('timezone_default', $timezone);
    }

    protected static function initLogger($options = array())
    {
        $config = Registry::get('config');
        $logger = new Logger($config);

        Registry::set('logger', $logger);
    }

    protected static function initCache($options = array())
    {
        $config = Registry::get('config');

        // Get the parameters
        $lifetime        = $config->models->cache->lifetime;
        $cacheDir        = $config->models->cache->cacheDir;
        $frontEndOptions = array('lifetime' => $lifetime);
        $backEndOptions  = array('cacheDir' => $cacheDir);

        // Create the data cache
        $cache = \Phalcon\Cache::factory(
            'Data',
            $config->models->cache->adapter,
            $frontEndOptions,
            $backEndOptions
        );

        // Set the cache in the registry
        Registry::set('cache', $cache);
    }

    protected static function initSession($options = array())
    {
        // Start the session
        Session::start();
    }

    protected static function initDatabase($options = array())
    {
    }

    protected static function initDebug($options = array())
    {
    }
}
