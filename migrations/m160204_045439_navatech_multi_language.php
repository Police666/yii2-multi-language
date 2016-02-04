<?php
use yii\db\Migration;

class m160204_045439_navatech_multi_language extends Migration {

	public function up() {
		$tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
		$this->createTable('{{%language}}', [
			'id'      => 'INT(11) NOT NULL AUTO_INCREMENT',
			0         => 'PRIMARY KEY (`id`)',
			'name'    => 'VARCHAR(255) NOT NULL',
			'code'    => 'VARCHAR(5) NOT NULL',
			'country' => 'VARCHAR(255) NOT NULL',
			'status'  => 'INT(1) NOT NULL DEFAULT 1',
		], $tableOptions_mysql);
		$this->createTable('{{%phrase}}', [
			'id'   => 'INT(11) NOT NULL AUTO_INCREMENT',
			0      => 'PRIMARY KEY (`id`)',
			'name' => 'VARCHAR(255) NOT NULL',
		], $tableOptions_mysql);
		$this->createTable('{{%phrase_meta}}', [
			'id'          => 'INT(11) NOT NULL AUTO_INCREMENT',
			0             => 'PRIMARY KEY (`id`)',
			'phrase_id'   => 'int(11) NOT NULL',
			'language_id' => 'int(11) NOT NULL',
			'value'       => 'TEXT NOT NULL',
		], $tableOptions_mysql);
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
