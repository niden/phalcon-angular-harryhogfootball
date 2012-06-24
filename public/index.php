<?php

error_reporting(E_ALL);

try {

    if (!defined('ROOT_PATH')) {

        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            // This is defined BY APACHE. It doesn't appear in IIS or anything
            define('ROOT_PATH', dirname($_SERVER['DOCUMENT_ROOT']));
        } else {
            // This is the default. It is not install specific unfortunately
            define('ROOT_PATH', dirname(dirname(__FILE__)));
        }
    }

    $app     = ROOT_PATH . '/app/';
    $library = ROOT_PATH . '/app/library/';

    // Creates the autoloader
    $loader = new Phalcon_Loader();

    // Register some classes
    $loader->registerClasses(
        array(
            'ControllerBase'    => $app     . 'controllers/ControllerBase.php',
            'Elements'          => $library . 'Elements.php',
            'niden_Exception'   => $library . 'niden/Exception.php',
            'niden_Registry'    => $library . 'niden/Registry.php',
            'niden_Logger'      => $library . 'niden/Logger.php',
            'niden_Breadcrumbs' => $library . 'niden/Breadcrumbs.php',
        )
    );

    //register autoloader
    $loader->register();

    // Get the config
    $config = new Phalcon_Config_Adapter_Ini($app . 'config/config.ini');
    niden_Registry::set('config', $config);

    $logger = new niden_Logger($config);
    niden_Registry::set('logger', $logger);

    $logger->info('Before Session');
    // Start the session
    Phalcon_Session::start();
    $logger->info('After Session');

    $logger->info('Before Dispatch');

    $front = Phalcon_Controller_Front::getInstance();
    $front->setConfig($config);

    echo $front->dispatchLoop()->getContent();

    $logger->info('After Session');

} catch (Phalcon_Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}
