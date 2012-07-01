<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class AwardsMigration_107 extends Phalcon_Model_Migration {

	public function up(){
		$this->morphTable('awards', array(
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
				new Column('userId', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'episodeId'
				)),
				new Column('playerId', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 10,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'userId'
				)),
				new Column('award', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 1,
					'notNull' => true,
					'after' => 'playerId'
				)),
				new Column('createdAt', array(
					'type' => Column::TYPE_DATETIME,
					'after' => 'award'
				)),
				new Column('createdAtUserId', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'after' => 'createdAt'
				)),
				new Column('lastUpdate', array(
					'type' => Column::TYPE_DATETIME,
					'after' => 'createdAtUserId'
				)),
				new Column('lastUpdateUserId', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'after' => 'lastUpdate'
				))
			),
			'indexes' => array(
				new Index('PRIMARY', array(
					'id'
				)),
				new Index('userId', array(
					'userId'
				)),
				new Index('episodeId', array(
					'episodeId'
				)),
				new Index('playerId', array(
					'playerId'
				)),
				new Index('award', array(
					'award'
				))
			),
			'options' => array(
				'TABLE_TYPE' => 'BASE TABLE',
				'AUTO_INCREMENT' => '376',
				'ENGINE' => 'InnoDB',
				'TABLE_COLLATION' => 'utf8_unicode_ci'
			)
		));
	}

}