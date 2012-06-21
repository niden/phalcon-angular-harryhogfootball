<?php

class Players extends Phalcon_Model_Base {

	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var integer
	 */
	public $position_id;

	/**
	 * @var string
	 */
	public $name;



	/**
	 * @return Players[]
	 */
	static public function find($parameters=array()){
		return parent::find($parameters);
	}


	/**
	 * @return Players
	 */
	static public function findFirst($parameters=array()){
		return parent::findFirst($parameters);
	}

}

