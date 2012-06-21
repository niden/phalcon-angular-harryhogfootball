<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class ScoringMigration_101 extends Phalcon_Model_Migration {

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
				new Column('episodeId', array(
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
					'after' => 'episodeId'
				)),
				new Column('userId', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'gameball'
				)),
				new Column('playerId', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'userId'
				))
			),
			'indexes' => array(
				new Index('PRIMARY', array(
					'id'
				)),
				new Index('episode', array(
					'episodeId'
				)),
				new Index('gameball', array(
					'gameball'
				)),
				new Index('rater', array(
					'userId'
				)),
				new Index('player_id', array(
					'playerId'
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