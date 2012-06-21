<?php

class Positions extends Phalcon_Model_Base {

	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var string
	 */
	public $position;



	/**
	 * @return Positions[]
	 */
	static public function find($parameters=array()){
		return parent::find($parameters);
	}


	/**
	 * @return Positions
	 */
	static public function findFirst($parameters=array()){
		return parent::findFirst($parameters);
	}

}

