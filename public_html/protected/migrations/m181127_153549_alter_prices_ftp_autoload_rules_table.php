<?php

class m181127_153549_alter_prices_ftp_autoload_rules_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->addColumn('prices_ftp_autoload_rules', 'remote_url', 'varchar(255)');
	}

	public function safeDown()
	{
        echo "m181127_153549_alter_prices_ftp_autoload_rules_table does not support migration down.\n";
        return false;
	}

}