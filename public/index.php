<?php

error_reporting(E_ALL);

try {

	require "../app/controllers/ControllerBase.php";

	$front = Phalcon_Controller_Front::getInstance();

	$config = new Phalcon_Config_Adapter_Ini("../app/config/config.ini");
	$front->setConfig($config);

	echo $front->dispatchLoop()->getContent();
}
catch(Phalcon_Exception $e){
	echo "PhalconException: ", $e->getMessage();
}