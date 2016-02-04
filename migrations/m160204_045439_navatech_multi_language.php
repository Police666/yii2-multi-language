<?php
use navatech\language\migrations\Migration;

class m160204_045439_navatech_multi_language extends Migration {

	public function up() {
//		$tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
//		$tables             = $this->getDb()->schema->tableNames;
//		if(in_array('{{%language}}', $tables)) {
//			$this->dropTable('{{%language}}');
//		}
//		if(in_array('{{%phrase}}', $tables)) {
//			$this->dropTable('{{%phrase}}');
//		}
//		if(in_array('{{%phrase_meta}}', $tables)) {
//			$this->dropTable('{{%phrase_meta}}');
//		}
//		$this->createTable('{{%language}}', [
//			'id'      => 'INT(11) NOT NULL AUTO_INCREMENT',
//			0         => 'PRIMARY KEY (`id`)',
//			'name'    => 'VARCHAR(255) NOT NULL',
//			'code'    => 'VARCHAR(5) NOT NULL',
//			'country' => 'VARCHAR(255) NOT NULL',
//			'status'  => 'INT(1) NOT NULL DEFAULT 1',
//		], $tableOptions_mysql);
//		$this->createTable('{{%phrase}}', [
//			'id'   => 'INT(11) NOT NULL AUTO_INCREMENT',
//			0      => 'PRIMARY KEY (`id`)',
//			'name' => 'VARCHAR(255) NOT NULL',
//		], $tableOptions_mysql);
//		$this->createTable('{{%phrase_meta}}', [
//			'id'          => 'INT(11) NOT NULL AUTO_INCREMENT',
//			0             => 'PRIMARY KEY (`id`)',
//			'phrase_id'   => 'int(11) NOT NULL',
//			'language_id' => 'int(11) NOT NULL',
//			'value'       => 'TEXT NOT NULL',
//		], $tableOptions_mysql);
//		$this->insert('{{%language}}', [
//			'id'      => 1,
//			'name'    => 'Việt Nam',
//			'code'    => 'vi',
//			'country' => 'vn',
//			'status'  => 1,
//		]);
//		$this->insert('{{%language}}', [
//			'id'      => 2,
//			'name'    => 'United States',
//			'code'    => 'en',
//			'country' => 'us',
//			'status'  => 1,
//		]);
		$translate = new \navatech\language\Translate();
		$translate->setLanguage();
		$translate->setData();
	}

	public function down() {
		$this->dropTable('{{%language}}');
		$this->dropTable('{{%phrase}}');
		$this->dropTable('{{%phrase_meta}}');
	}
	/*
	// Use safeUp/safeDown to run migration code within a transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
