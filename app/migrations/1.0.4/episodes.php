<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class EpisodesMigration_104 extends Phalcon_Model_Migration {

	public function up(){
		$this->morphTable('episodes', array(
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
				new Column('number', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 5,
					'notNull' => true,
					'after' => 'id'
				)),
				new Column('summary', array(
					'type' => Column::TYPE_TEXT,
					'notNull' => true,
					'after' => 'number'
				)),
				new Column('airDate', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 10,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'summary'
				))
			),
			'indexes' => array(
				new Index('PRIMARY', array(
					'id'
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