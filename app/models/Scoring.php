<?php

class Scoring extends Phalcon_Model_Base {

	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var integer
	 */
	public $episode_id;

	/**
	 * @var integer
	 */
	public $gameball;

	/**
	 * @var integer
	 */
	public $user_id;

	/**
	 * @var integer
	 */
	public $player_id;



	/**
	 * @return Scoring[]
	 */
	static public function find($parameters=array()){
		return parent::find($parameters);
	}


	/**
	 * @return Scoring
	 */
	static public function findFirst($parameters=array()){
		return parent::findFirst($parameters);
	}

}

