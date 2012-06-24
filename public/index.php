<?php

error_reporting(E_ALL);

try {

    $app     = __DIR__ . '/../app/';
    $library = $app . 'library/';

    // Creates the autoloader
    $loader = new Phalcon_Loader();

    // Register some classes
    $loader->registerClasses(
        array(
            'ControllerBase'    => $app     . 'controllers/ControllerBase.php',
            'Elements'          => $library . 'Elements.php',
            'niden_Registry'    => $library . 'niden/Registry.php',
            'niden_Breadcrumbs' => $library . 'niden/Breadcrumbs.php',
        )
    );

    //register autoloader
    $loader->register();

    // Get the config
    $config = new Phalcon_Config_Adapter_Ini($app . 'config/config.ini');

    niden_Registry::set('config', $config);

    // Start the session
    Phalcon_Session::start();

    $front = Phalcon_Controller_Front::getInstance();
    $front->setConfig($config);

    echo $front->dispatchLoop()->getContent();

} catch (Phalcon_Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}
