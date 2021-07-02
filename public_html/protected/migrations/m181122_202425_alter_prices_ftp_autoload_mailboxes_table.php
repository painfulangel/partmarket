<?php

class m181122_202425_alter_prices_ftp_autoload_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->addColumn('prices_ftp_autoload_mailboxes', 'state', 'integer(1) DEFAULT 1');
	}

	public function safeDown()
	{
        echo "m181122_202425_alter_prices_ftp_autoload_mailboxes_table does not support migration down.\n";
        return false;
	}
}