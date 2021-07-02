<?php

class m181129_212225_alter_prices_table extends CDbMigration
{
	public function safeUp()
	{
	    $this->addColumn('prices', 'rule_name', 'varchar(255) DEFAULT NULL');
        $this->addColumn('prices', 'store_name', 'varchar(255) DEFAULT NULL');
        $this->addColumn('prices', 'count_position', 'integer(11) DEFAULT 0');
	}

	public function safeDown()
	{
        echo "m181129_212225_alter_prices_table does not support migration down.\n";
        return false;
	}

}