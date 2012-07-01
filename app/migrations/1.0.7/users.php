<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class UsersMigration_107 extends Phalcon_Model_Migration {

	public function up(){
		$this->morphTable('users', array(
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
				new Column('username', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 50,
					'notNull' => true,
					'after' => 'id'
				)),
				new Column('password', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 64,
					'notNull' => true,
					'after' => 'username'
				)),
				new Column('name', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 100,
					'notNull' => true,
					'after' => 'password'
				))
			),
			'indexes' => array(
				new Index('PRIMARY', array(
					'id'
				)),
				new Index('username', array(
					'username'
				))
			),
			'options' => array(
				'TABLE_TYPE' => 'BASE TABLE',
				'AUTO_INCREMENT' => '5',
				'ENGINE' => 'InnoDB',
				'TABLE_COLLATION' => 'utf8_unicode_ci'
			)
		));
	}

}