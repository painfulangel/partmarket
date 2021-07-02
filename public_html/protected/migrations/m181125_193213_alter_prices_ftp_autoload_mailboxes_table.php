<?php

class m181125_193213_alter_prices_ftp_autoload_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->addColumn('prices_ftp_autoload_mailboxes', 'download_time', 'varchar(20) DEFAULT 0');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'download_count', 'integer(11) DEFAULT 0');
	}

	public function safeDown()
	{
        echo "m181125_193213_alter_prices_ftp_autoload_mailboxes_table does not support migration down.\n";
        return false;
	}
}