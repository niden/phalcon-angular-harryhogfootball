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
use \Phalcon\Flash\Session as Flash;
use \Phalcon\Logger\Adapter\File as Logger;
use \Phalcon\Db\Adapter\Pdo\Mysql as Mysql;
use \Phalcon\Session\Adapter\Files as Session;
use \Phalcon\Cache\Frontend\Data as CacheFront;
use \Phalcon\Cache\Backend\File as CacheBack;
use \Phalcon\Mvc\Application as Application;
use \Phalcon\Mvc\Dispatcher as Dispatcher;
use \Phalcon\Mvc\View as View;
use \Phalcon\Mvc\View\Engine\Volt as Volt;
use \Phalcon\Mvc\Model\Metadata\Memory as MetadataMemory;
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
        $loaders = array(
            'config',
            'loader',
            'environment',
            'timezone',
            'flash',
            'url',
            'dispatcher',
            'view',
            'logger',
            'database',
            'session',
            'cache',
            'behaviors',
            'debug',
        );


        try {

            foreach ($loaders as $service)
            {
                $function = 'init' . ucfirst($service);

                $this->$function($options);
            }

            $application = new Application();
            $application->setDI($this->_di);

            return $application->handle()->getContent();

        } catch (\Phalcon\Exception $e) {
            echo $e->getMessage();
        } catch (\PDOException $e) {
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
                ROOT_PATH . $config->app->path->controllers,
                ROOT_PATH . $config->app->path->models,
                ROOT_PATH . $config->app->path->library,
            )
        );

        // Register the namespace
        $loader->registerNamespaces(
            array("NDN" => $config->app->path->library)
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

        $debug = (isset($config->app->debug)) ?
                 (bool) $config->app->debug   :
                 false;

        if ($debug)
        {
            ini_set('display_errors', true);
            error_reporting(E_ALL);
        }
        else
        {
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

        $timezone = (isset($config->app->timezone)) ?
                    $config->app->timezone      :
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
            function()
            {
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
    protected function initUrl($options = array())
    {
        $config = $this->_di->get('config');

        /**
         * The URL component is used to generate all kind of urls in the
         * application
         */
        $this->_di->set(
            'url',
            function() use ($config)
            {
                $url = new \Phalcon\Mvc\Url();
                $url->setBaseUri($config->app->baseUri);
                return $url;
            }
        );
    }

    /**
     * Initializes the dispatcher
     *
     * @param array $options
     */
    protected function initDispatcher($options = array())
    {
        $di = $this->_di;

        $this->_di->set(
            'dispatcher',
            function() use ($di) {

                $evManager = $di->getShared('eventsManager');
                $acl       = new \NDN\Plugins\Acl($di);

                /**
                 * Listening to events in the dispatcher using the
                 * Acl plugin
                 */
                $evManager->attach('dispatch', $acl);
        		$dispatcher = new Dispatcher();
		        $dispatcher->setEventsManager($evManager);

		        return $dispatcher;
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
        $di     = $this->_di;

        /**
         * Setup the view service
         */
        $this->_di->set(
            'view',
            function() use ($config, $di)
            {
                $view = new \Phalcon\Mvc\View();
                $view->setViewsDir(ROOT_PATH . $config->app->path->views);

                $volt = new Volt($view, $di);
                $volt->setOptions(
                    array(
                        'compiledPath'      => ROOT_PATH . $config->app->volt->path,
                        'compiledExtension' => $config->app->volt->extension,
                        'compiledSeparator' => $config->app->volt->separator,
                        'stat'              => (bool) $config->app->volt->stat,
                    )
                );

                /**
                 * Register Volt
                 */
                $view->registerEngines(array('.volt' => $volt));

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
            function() use ($config)
            {
                $logger = new Logger(ROOT_PATH . $config->app->logger->file);
                $logger->setFormat($config->app->logger->format);
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
        $debug  = (isset($config->app->debug)) ?
                  (bool) $config->app->debug   :
                  false;

        $this->_di->set(
            'db',
            function() use ($debug, $config, $logger)
            {

                if ($debug)
                {
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

                if ($config->phalcon->debug)
                {
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
            function() use ($config)
            {
                if (isset($config->models->metadata))
                {
                    $metaDataConfig  = $config->models->metadata;
                    $metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\'
                                     . $metaDataConfig->adapter;
                    return new $metadataAdapter();
                }
                else
                {
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
            function()
            {
                $session = new Session();
                if (!$session->isStarted())
                {
                    $session->start();
                }
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
            function() use ($config)
            {
                // Get the parameters
                $lifetime        = $config->app->cache->lifetime;
                $cacheDir        = $config->app->cache->cacheDir;
                $frontEndOptions = array('lifetime' => $lifetime);
                $backEndOptions  = array('cacheDir' => ROOT_PATH . $cacheDir);

                $frontCache = new CacheFront($frontEndOptions);
                $cache      = new CacheBack($frontCache, $backEndOptions);

                return $cache;
            }
        );

    }

    /**
     * Initializes the model behaviors
     *
     * @param array $options
     */
    protected function initBehaviors($options = array())
    {
        $session = $this->_di->getShared('session');

        // Timestamp
        $this->_di->set(
            'Timestamp',
            function() use ($session)
            {
                $timestamp = new Models\Behaviors\Timestamp($session);
                return $timestamp;
            }
        );
    }

    /**
     * Initializes the debugging functions
     *
     * @param array $options
     */
    protected function initDebug($options = array())
    {
        $config = $this->_di->get('config');
        $debug  = (isset($config->app->debug)) ?
                  (bool) $config->app->debug   :
                  false;

        if ($debug)
        {
            require_once ROOT_PATH . '/app/library/NDN/Debug.php';
        }
    }
}
