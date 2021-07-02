<?php

class m181120_190104_remove_column_prices_ftp_autoload_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->dropColumn('prices_ftp_autoload_mailboxes', 'cron_general');

	}

	public function safeDown()
	{
        echo "m181120_190104_remove_column_prices_ftp_autoload_mailboxes_table does not support migration down.\n";
        return false;
	}
}