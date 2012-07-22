<?php

namespace NDN;

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
    $loader = new \Phalcon\Loader();

    // Register some classes
    $loader->registerNamespaces(
        array("NDN" => $library . "NDN/")
    );
    $loader->register();

    echo Bootstrap::run(array());

} catch (\Phalcon\Exception $e) {
    Error::exception($e);
    header('Location: /');
}
