<?php

class Episodes extends Phalcon_Model_Base {

	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var string
	 */
	public $number;

	/**
	 * @var string
	 */
	public $summary;

	/**
	 * @var integer
	 */
	public $air_date;



	/**
	 * @return Episodes[]
	 */
	static public function find($parameters=array()){
		return parent::find($parameters);
	}


	/**
	 * @return Episodes
	 */
	static public function findFirst($parameters=array()){
		return parent::findFirst($parameters);
	}

}

