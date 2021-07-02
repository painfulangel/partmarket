<?php

class m181125_181526_create_prices_autoload_queue_table extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable('prices_autoload_queue', array(
            'id' => 'pk',
            'rule_id'=>'integer(11) NOT NULL',
            'store_id'=>'integer(11) NOT NULL',
            'path' => 'varchar(255) NOT NULL',
            'filename' => 'varchar(255) NOT NULL',
            'created'=>'integer(11) NOT NULL'
        ));
	}

	public function safeDown()
	{
        echo "m181125_181526_create_prices_autoload_queue_table does not support migration down.\n";
        return false;
	}
}