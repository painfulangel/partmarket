<?php

class m181121_155805_prices_ftp_sources_mailboxes_table extends CDbMigration
{
	public function safeUp()
	{
	    Yii::app()->db->createCommand("
	    CREATE TABLE `prices_ftp_sources_rules` (
  `id` int(11) UNSIGNED NOT NULL,
  `rule_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `search_file_criteria` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active_state` tinyint(1) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `start_line` int(11) DEFAULT NULL,
  `finish_line` int(11) DEFAULT NULL,
  `brand` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantum` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `article` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delete_state` tinyint(1) DEFAULT NULL,
  `charset` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `load_period_hours` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `load_period_days` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `load_period_minutes` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `xml_element_tag` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `download_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT '0',
  `replace_delivery` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replace_brand` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replace_name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replace_price` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replace_quantum` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replace_article` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_admin_mail` tinyint(1) DEFAULT NULL,
  `mail_subject` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_body` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_from` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_file` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `prices_ftp_sources_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sources_active_search_index` (`active_state`);
  
ALTER TABLE `prices_ftp_sources_rules`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
")->execute();
	}

	public function safeDown()
	{
        echo "m181121_155805_prices_ftp_sources_mailboxes_table does not support migration down.\n";
        return false;
	}
}