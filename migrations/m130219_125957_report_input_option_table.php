<?php

class m130219_125957_report_input_option_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('report_input_option', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'input_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_input_option_input_id_fk` (`input_id`)',
				'KEY `report_input_option_c_u_id_fk` (`created_user_id`)',
				'KEY `report_input_option_l_m_u_id_fk` (`last_modified_user_id`)',
				'CONSTRAINT `report_input_option_input_id_fk` FOREIGN KEY (`input_id`) REFERENCES `report_input` (`id`)',
				'CONSTRAINT `report_input_option_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_option_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
	}

	public function down()
	{
		$this->dropTable('report_input_option');
	}
}
