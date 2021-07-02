<?php

class m181120_112520_add_column_prices_ftp_autoload_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->addColumn('prices_ftp_autoload_mailboxes', 'mail_subject', 'varchar(255) default null after `pop_port`');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'mail_body', 'varchar(255) default null after `mail_subject`');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'mail_from', 'varchar(255) default null after `mail_body`');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'protocol', 'varchar(255) default null after `mail_from`');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'imap_address', 'varchar(255) default null after `protocol`');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'imap_port', 'varchar(255) default null after `imap_address`');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'frequency', 'varchar(255) default null after `imap_port`');
        $this->addColumn('prices_ftp_autoload_mailboxes', 'expire', 'varchar(255) default null after `frequency`');

	}

	public function safeDown()
	{
        echo "m181120_112520_add_column_prices_ftp_autoload_mailboxes_table does not support migration down.\n";
        return false;
	}
}