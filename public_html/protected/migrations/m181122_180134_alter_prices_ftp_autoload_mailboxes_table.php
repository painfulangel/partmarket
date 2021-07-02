<?php

class m181122_180134_alter_prices_ftp_autoload_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->addColumn('prices_ftp_autoload_mailboxes', 'cron_general', 'integer(2) DEFAULT NULL');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'load_period_hours', 'varchar(32) DEFAULT NULL');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'load_period_minutes', 'varchar(32) DEFAULT NULL');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'load_period_days', 'varchar(32) DEFAULT NULL');
	}

	public function safeDown()
	{
        echo "m181122_180134_alter_prices_ftp_autoload_mailboxes_table does not support migration down.\n";
        return false;
	}
}