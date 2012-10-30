<?php

error_reporting(E_ALL);

try {

    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(dirname(__FILE__)));
    }

    // Using require once because I want to get the specific
    // bootloader class here. The loader will be initialized
    // in my bootstrap class
    require_once ROOT_PATH . '/app/library/NDN/Bootstrap.php';
    require_once ROOT_PATH . '/app/library/NDN/Error.php';
    require_once ROOT_PATH . '/app/library/NDN/Debug.php';

    $di  = new \Phalcon\DI\FactoryDefault();
    $app = new \NDN\Bootstrap($di);

    echo $app->run(array());

} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    //\NDN\Error::exception($e);
    //header('Location: /');
}
