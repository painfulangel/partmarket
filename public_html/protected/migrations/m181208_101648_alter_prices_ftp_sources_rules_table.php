<?php

class m181208_101648_alter_prices_ftp_sources_rules_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->alterColumn('prices_ftp_sources_rules', 'search_file_criteria', 'text');
	}

	public function safeDown()
	{
        echo "m181208_101648_alter_prices_ftp_sources_rules_table does not support migration down.\n";
        return false;
	}
}