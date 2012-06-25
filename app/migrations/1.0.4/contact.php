<?php 

use Phalcon_Db_Column as Column;
use Phalcon_Db_Index as Index;
use Phalcon_Db_Reference as Reference;

class ContactMigration_104 extends Phalcon_Model_Migration {

	public function up(){
		$this->morphTable('contact', array(
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
					'size' => 70,
					'notNull' => true,
					'after' => 'id'
				)),
				new Column('email', array(
					'type' => Column::TYPE_VARCHAR,
					'size' => 70,
					'notNull' => true,
					'after' => 'name'
				)),
				new Column('comments', array(
					'type' => Column::TYPE_TEXT,
					'notNull' => true,
					'after' => 'email'
				)),
				new Column('createdAt', array(
					'type' => Column::TYPE_INTEGER,
					'size' => 11,
					'notNull' => true,
					'after' => 'comments'
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