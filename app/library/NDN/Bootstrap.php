<?php
/**
 * \NDN\Bootstrap
 * Bootstrap.php
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

use \Phalcon\Config\Adapter\Ini as Config;
use \Phalcon\Loader as Loader;
use \Phalcon\Flash\Direct as Flash;
use \Phalcon\Logger\Adapter\File as Logger;
use \Phalcon\Db\Adapter\Pdo\Mysql as Mysql;
use \Phalcon\Mvc\Model\Metadata\Memory as MetadataMemory;
use \Phalcon\Session\Adapter\Files as Session;
use \Phalcon\Cache\Frontend\Data as CacheFront;
use \Phalcon\Cache\Backend\File as CacheBack;
use \Phalcon\Mvc\Application as Application;
use \Phalcon\Events\Manager as EventsManager;

class Bootstrap
{
    private $_di;

    public function __construct($di)
    {
        $this->_di = $di;
    }

    public function run($options)
    {
        try {

            // Initialize everything - Order does matter
            $this->initConfig($options);
            $this->initLoader($options);
            $this->initEnvironment($options);
            $this->initTimezone($options);
            $this->initFlash($options);
            $this->initBaseUrl($options);
            $this->initView($options);
            $this->initLogger($options);
            $this->initDatabase($options);
            $this->initSession($options);
            $this->initCache($options);

//        $this->initDebug($options);
            $application = new Application();
            $application->setDI($this->_di);

            return $application->handle()->getContent();

        } catch (\Phalcon\Exception $e) {
            echo $e->getMessage();
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    // Protected functions

    /**
     * Initializes the config. Reads it from its location and
     * stores it in the Di container for easier access
     *
     * @param array $options
     */
    protected function initConfig($options = array())
    {
        $configFile = ROOT_PATH . '/app/config/config.ini';

        // Create the new object
        $config = new Config($configFile);

        // Store it in the Di container
        $this->_di->set('config', $config);
    }

    /**
     * Initializes the loader
     *
     * @param array $options
     */
    protected function initLoader($options = array())
    {
        $config = $this->_di->get('config');

        // Creates the autoloader
        $loader = new Loader();

        $loader->registerDirs(
            array(
                ROOT_PATH . $config->phalcon->controllersDir,
                ROOT_PATH . $config->phalcon->modelsDir,
                ROOT_PATH . $config->phalcon->pluginsDir,
                ROOT_PATH . $config->phalcon->libraryDir,
            )
        );

        // Register the namespace
        $loader->registerNamespaces(
            array("NDN" => $config->phalcon->libraryDir)
        );

        $loader->register();
    }

    /**
     * Initializes the environment
     *
     * @param array $options
     */
    protected function initEnvironment($options = array())
    {
        $config = $this->_di->get('config');

        $debug = (isset($config->phalcon->debug)) ?
            (bool) $config->phalcon->debug   : false;

        if ($debug) {
            ini_set('display_errors', true);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', false);
//            error_reporting( -1 );
        }

        set_error_handler(array('\NDN\Error', 'normal'));
        set_exception_handler(array('\NDN\Error', 'exception'));
        register_shutdown_function(array('\NDN\Error', 'shutdown'));
    }

    /**
     * Initializes the timezone
     *
     * @param array $options
     */
    protected function initTimezone($options = array())
    {
        $config = $this->_di->get('config');

        $timezone = (isset($config->timezone)) ?
            $config->timezone      :
            'US/Eastern';

        date_default_timezone_set($timezone);

        $this->_di->set('timezone_default', $timezone);
    }

    /**
     * Initializes the flash messenger
     *
     * @param array $options
     */
    protected function initFlash($options = array())
    {
        $this->_di->set(
            'flash',
            function() {
                $params = array(
                    'error'   => 'alert alert-error',
                    'success' => 'alert alert-success',
                    'notice'  => 'alert alert-info',
                );

                return new Flash($params);
            }
        );
    }

    /**
     * Initializes the baseUrl
     *
     * @param array $options
     */
    protected function initBaseUrl($options = array())
    {
        $config = $this->_di->get('config');

        /**
         * The URL component is used to generate all kind of urls in the
         * application
         */
        $this->_di->set(
            'url', function() use ($config) {
                $url = new \Phalcon\Mvc\Url();
                $url->setBaseUri($config->phalcon->baseUri);
                return $url;
            }
        );
    }

    /**
     * Initializes the view and Volt
     *
     * @param array $options
     */
    protected function initView($options = array())
    {
        $config = $this->_di->get('config');

        /**
         * Setup the view service
         */
        $this->_di->set(
            'view',
            function() use ($config) {
                $view = new \Phalcon\Mvc\View();
                $view->setViewsDir(ROOT_PATH . $config->phalcon->viewsDir);

                /**
                 * Register Volt
                 */
                $view->registerEngines(
                    array(
                        '.volt' => 'Phalcon\Mvc\View\Engine\Volt'
                    )
                );
                return $view;
            }
        );
    }

    /**
     * Initializes the logger
     *
     * @param array $options
     */
    protected function initLogger($options = array())
    {
        $config = $this->_di->get('config');

        $this->_di->set(
            'logger',
            function() use ($config) {
                $logger = new Logger(ROOT_PATH . $config->logger->file);
                $logger->setFormat($config->logger->format);
                return $logger;
            }
        );
    }

    /**
     * Initializes the database and netadata adapter
     *
     * @param array $options
     */
    protected function initDatabase($options = array())
    {
        $config = $this->_di->get('config');
        $logger = $this->_di->get('logger');

        $this->_di->set(
            'db',
            function() use ($config, $logger) {

                if ($config->phalcon->debug) {
                    $eventsManager = new EventsManager();

                    // Listen all the database events
                    $eventsManager->attach(
                        'db',
                        function($event, $connection) use ($logger) {
                            if ($event->getType() == 'beforeQuery') {
                                $logger->log(
                                    $connection->getSQLStatement(),
                                    Logger::INFO
                                );
                            }
                        }
                    );
                }

                $params = array(
                    "host"     => $config->database->host,
                    "username" => $config->database->username,
                    "password" => $config->database->password,
                    "dbname"   => $config->database->name,
                );

                $conn = new Mysql($params);

                if ($config->phalcon->debug) {
                    // Assign the eventsManager to the db adapter instance
                    $conn->setEventsManager($eventsManager);
                }

                return $conn;
            }
        );

        /**
         * If the configuration specify the use of metadata adapter use it or use memory otherwise
         */
        $this->_di->set(
            'modelsMetadata',
            function() use ($config) {
                if(isset($config->models->metadata)) {
                    $metaDataConfig  = $config->models->metadata;
                    $metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\'
                                     . $metaDataConfig->adapter;
                    return new $metadataAdapter();
                } else {
                    return new MetadataMemory();
                }
            }
        );
    }

    /**
     * Initializes the session
     *
     * @param array $options
     */
    protected function initSession($options = array())
    {
        $this->_di->set(
            'session',
            function() {
                $session = new Session();
                return $session;
            }
        );
    }

    /**
     * Initializes the cache
     *
     * @param array $options
     */
    protected function initCache($options = array())
    {
        $config = $this->_di->get('config');

        $this->_di->set(
            'cache',
            function() use ($config) {
                // Get the parameters
                $lifetime        = $config->models->cache->lifetime;
                $cacheDir        = $config->models->cache->cacheDir;
                $frontEndOptions = array('lifetime' => $lifetime);
                $backEndOptions  = array('cacheDir' => ROOT_PATH . $cacheDir);

                $frontCache = new CacheFront($frontEndOptions);
                $cache      = new CacheBack($frontCache, $backEndOptions);

                return $cache;
            }
        );

    }
//    protected function initEventsManager($options = array())
//   {
//        $di->set('dispatcher', function() use ($di) {
//
//		$eventsManager = $di->getShared('eventsManager');
//
//		$security = new Security($di);
//
//		/**
//         * We listen for events in the dispatcher using the Security plugin
//         */
//		$eventsManager->attach('dispatch', $security);
//
//		$dispatcher = new Phalcon\Mvc\Dispatcher();
//		$dispatcher->setEventsManager($eventsManager);
//
//		return $dispatcher;
//	}
//
//
//
//
//    protected function initDebug($options = array())
//    {
//    }
}
