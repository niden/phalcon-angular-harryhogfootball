<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class PlayersMigration_105 extends Phalcon_Model_Migration {

	public function up(){
		$this->morphTable('players', array(
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
				new Column('name', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 150,
					'notNull' => true,
					'after' => 'id'
				)),
				new Column('active', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 1,
					'unsigned' => true,
					'notNull' => true,
					'after' => 'name'
				)),
				new Column('team', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 50,
					'after' => 'active'
				))
			),
			'indexes' => array(
				new Index('PRIMARY', array(
					'id'
				)),
				new Index('name', array(
					'name',
					'team'
				)),
				new Index('active', array(
					'active'
				))
			),
			'options' => array(
				'TABLE_TYPE' => 'BASE TABLE',
				'AUTO_INCREMENT' => '151',
				'ENGINE' => 'InnoDB',
				'TABLE_COLLATION' => 'utf8_unicode_ci'
			)
		));
	}

}