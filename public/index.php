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
            'Elements'        => $library . 'Elements.php',
            'NDN_Controller'  => $library . 'NDN/Controller.php',
            'NDN_Model'       => $library . 'NDN/Model.php',
            'NDN_Exception'   => $library . 'NDN/Exception.php',
            'NDN_Registry'    => $library . 'NDN/Registry.php',
            'NDN_Session'     => $library . 'NDN/Session.php',
            'NDN_Logger'      => $library . 'NDN/Logger.php',
            'NDN_Breadcrumbs' => $library . 'NDN/Breadcrumbs.php',
        )
    );

    //register autoloader
    $loader->register();

    // Get the config
    $config = new Phalcon_Config_Adapter_Ini($app . 'config/config.ini');
    NDN_Registry::set('config', $config);

//    $logger = new NDN_Logger($config);
//    NDN_Registry::set('logger', $logger);

    // Start the session
    NDN_Session::start();

    if (isset($_GET["_url"])) {
        $_GET["_url"] = preg_replace("#^/#", "", $_GET["_url"]);
    }

    $front = Phalcon_Controller_Front::getInstance();
    $front->setConfig($config);

    echo $front->dispatchLoop()->getContent();

} catch (Phalcon_Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}
