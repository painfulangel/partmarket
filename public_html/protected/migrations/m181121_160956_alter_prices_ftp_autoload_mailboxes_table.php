<?php

class m181121_160956_alter_prices_ftp_autoload_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->dropColumn('prices_ftp_autoload_mailboxes', 'mail_subject');
        $this->dropColumn('prices_ftp_autoload_mailboxes', 'mail_body');
        $this->dropColumn('prices_ftp_autoload_mailboxes', 'mail_from');
	}

	public function safeDown()
	{
        echo "m181121_160956_alter_prices_ftp_autoload_mailboxes_table does not support migration down.\n";
        return false;
	}
}