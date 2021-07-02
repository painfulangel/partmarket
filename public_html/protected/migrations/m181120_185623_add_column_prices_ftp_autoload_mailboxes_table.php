<?php

class m181120_185623_add_column_prices_ftp_autoload_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('prices_ftp_autoload_mailboxes', 'cron_general', 'varchar(255) default null after `expire`');
	}

	public function safeDown()
	{
        echo "m181120_185623_add_column_prices_ftp_autoload_mailboxes_table does not support migration down.\n";
        return false;
	}
}