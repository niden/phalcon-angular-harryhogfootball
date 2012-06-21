<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class ScoringMigration_100 extends Phalcon_Model_Migration {

	public function up(){
		$this->morphTable('scoring', array(
			'columns' => array(
				new Column('id', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 10,
					'unsigned' => true,
					'primary' => true,
					'notNull' => true,
					'autoIncrement' => true,
					'first' => true
				)),
				new Column('episode_id', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'id'
				)),
				new Column('gameball', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 1,
					'notNull' => true,
					'after' => 'episode_id'
				)),
				new Column('user_id', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'gameball'
				)),
				new Column('player_id', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'user_id'
				))
			),
			'indexes' => array(
				new Index('PRIMARY', array(
					'id'
				)),
				new Index('episode', array(
					'episode_id'
				)),
				new Index('gameball', array(
					'gameball'
				)),
				new Index('rater', array(
					'user_id'
				)),
				new Index('player_id', array(
					'player_id'
				))
			),
			'options' => array(
				'TABLE_TYPE' => 'BASE TABLE',
				'AUTO_INCREMENT' => '1',
				'ENGINE' => 'InnoDB',
				'TABLE_COLLATION' => 'utf8_unicode_ci'
			)
		));
	}

}