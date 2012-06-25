<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class PositionsMigration_104 extends Phalcon_Model_Migration {

	public function up(){
		$this->morphTable('positions', array(
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
				new Column('position', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 50,
					'notNull' => true,
					'after' => 'id'
				))
			),
			'indexes' => array(
				new Index('PRIMARY', array(
					'id'
				))
			),
			'options' => array(
				'TABLE_TYPE' => 'BASE TABLE',
				'AUTO_INCREMENT' => '10',
				'ENGINE' => 'InnoDB',
				'TABLE_COLLATION' => 'utf8_unicode_ci'
			)
		));
	}

}