<?php

class m130124_083001_reports_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('report_query_type',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_qt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_qt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `report_qt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_qt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'query_type_id' => 'int(10) unsigned NOT NULL',
				'subspecialty_id' => 'int(10) unsigned NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'description' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'icon' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'can_print' => 'tinyint(1) unsigned NOT NULL DEFAULT 1',
				'can_download' => 'tinyint(1) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_created_user_id_fk` (`created_user_id`)',
				'KEY `report_subspecialty_id_fk` (`subspecialty_id`)',
				'KEY `report_query_type_id_fk` (`query_type_id`)',
				'CONSTRAINT `report_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)',
				'CONSTRAINT `report_query_type_id_fk` FOREIGN KEY (`query_type_id`) REFERENCES `report_query_type` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'report_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_dataset_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_dataset_created_user_id_fk` (`created_user_id`)',
				'KEY `report_dataset_report_id_fk` (`report_id`)',
				'CONSTRAINT `report_dataset_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dataset_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dataset_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset_element',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'dataset_id' => 'int(10) unsigned NOT NULL',
				'element_type_id' => 'int(10) unsigned NOT NULL',
				'optional' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_de_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_de_created_user_id_fk` (`created_user_id`)',
				'KEY `report_de_dataset_id_fk` (`dataset_id`)',
				'KEY `report_de_element_type_id_fk` (`element_type_id`)',
				'CONSTRAINT `report_de_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_de_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_de_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`)',
				'CONSTRAINT `report_de_element_type_id_fk` FOREIGN KEY (`element_type_id`) REFERENCES `element_type` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset_element_field',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'element_id' => 'int(10) unsigned NOT NULL',
				'field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_def_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_def_created_user_id_fk` (`created_user_id`)',
				'KEY `report_def_element_id_fk` (`element_id`)',
				'CONSTRAINT `report_def_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_def_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_def_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset_element_join',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'element_id' => 'int(10) unsigned NOT NULL',
				'join_table' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'join_clause' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'join_select' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_dej_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_dej_created_user_id_fk` (`created_user_id`)',
				'KEY `report_dej_element_id_fk` (`element_id`)',
				'CONSTRAINT `report_dej_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dej_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dej_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset_related_entity',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'dataset_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_dre_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_dre_created_user_id_fk` (`created_user_id`)',
				'KEY `report_dre_dataset_id_fk` (`dataset_id`)',
				'CONSTRAINT `report_dre_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dre_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dre_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset_related_entity_type',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'entity_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_ret_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_ret_created_user_id_fk` (`created_user_id`)',
				'KEY `report_ret_entity_id_fk` (`entity_id`)',
				'CONSTRAINT `report_ret_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_ret_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_ret_entity_id_fk` FOREIGN KEY (`entity_id`) REFERENCES `report_dataset_related_entity` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset_related_entity_table',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'entity_type_id' => 'int(10) unsigned NULL',
				'entity_id' => 'int(10) unsigned NOT NULL',
				'table_name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'table_related_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'table_query_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'table_date_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_dret_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_dret_created_user_id_fk` (`created_user_id`)',
				'KEY `report_dret_entity_id_fk` (`entity_id`)',
				'KEY `report_dret_entity_type_id_fk` (`entity_type_id`)',
				'CONSTRAINT `report_dret_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dret_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dret_entity_id_fk` FOREIGN KEY (`entity_id`) REFERENCES `report_dataset_related_entity` (`id`)',
				'CONSTRAINT `report_dret_entity_type_id_fk` FOREIGN KEY (`entity_type_id`) REFERENCES `report_dataset_related_entity_type` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_dataset_related_entity_table_relation',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'entity_table_id' => 'int(10) unsigned NULL',
				'local_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'related_table' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'select_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'select_field_as' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_dretr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_dretr_created_user_id_fk` (`created_user_id`)',
				'KEY `report_dretr_entity_table_id_fk` (`entity_table_id`)',
				'CONSTRAINT `report_dretr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dretr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_dretr_entity_table_id_fk` FOREIGN KEY (`entity_table_id`) REFERENCES `report_dataset_related_entity_table` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_input_data_type',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_input_dm_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_input_dm_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `report_input_dm_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_dm_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_input',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'dataset_id' => 'int(10) unsigned NOT NULL',
				'data_type_id' => 'int(10) unsigned NOT NULL',
				'data_type_param1' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_type_param2' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'description' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'default_value' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'required' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'or_id' => 'int(10) unsigned NULL',
				'include' => 'tinyint(1) unsigned NOT NULL DEFAULT 1',
				'related_entity_id' => 'int(10) unsigned NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_input_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_input_created_user_id_fk` (`created_user_id`)',
				'KEY `report_input_data_type_id_fk` (`data_type_id`)',
				'KEY `report_input_dataset_id_fk` (`dataset_id`)',
				'KEY `report_input_related_entity_id_fk` (`related_entity_id`)',
				'CONSTRAINT `report_input_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_input_data_type` (`id`)',
				'CONSTRAINT `report_input_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`)',
				'CONSTRAINT `report_input_related_entity_id_fk` FOREIGN KEY (`related_entity_id`) REFERENCES `report_dataset_related_entity` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_item_data_type',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_item_data_type_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_item_data_type_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `report_item_data_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_data_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_item',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'dataset_id' => 'int(10) unsigned NOT NULL',
				'data_type_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_input_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'subtitle' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'display' => 'tinyint(1) unsigned NOT NULL DEFAULT 1',
				'element_id' => 'int(10) unsigned NULL',
				'element_relation' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'element_relation_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'element_relation_value' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_item_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_item_created_user_id_fk` (`created_user_id`)',
				'KEY `report_item_report_data_type_id_fk` (`data_type_id`)',
				'KEY `report_item_dataset_id_fk` (`dataset_id`)',
				'KEY `report_item_element_id_fk` (`element_id`)',
				'CONSTRAINT `report_item_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`)',
				'CONSTRAINT `report_item_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`)',
				'CONSTRAINT `report_item_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_item_pair_field',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'item_id' => 'int(10) unsigned NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'value' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_item_pf_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_item_pf_created_user_id_fk` (`created_user_id`)',
				'KEY `report_item_pf_item_id_fk` (`item_id`)',
				'CONSTRAINT `report_item_pf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_pf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_pf_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `report_item` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_item_list_item',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'item_id' => 'int(10) unsigned NULL',
				'list_item_id' => 'int(10) unsigned NULL',
				'data_type_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'subtitle' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display' => 'tinyint(1) unsigned NOT NULL DEFAULT 1',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'link' => 'varchar(1024) COLLATE utf8_bin NOT NULL',
				'element_id' => 'int(10) unsigned NULL',
				'element_relation' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_item_li_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_item_li_created_user_id_fk` (`created_user_id`)',
				'KEY `report_item_li_report_data_type_id_fk` (`data_type_id`)',
				'KEY `report_item_li_item_id_fk` (`item_id`)',
				'KEY `report_item_li_list_item_id_fk` (`list_item_id`)',
				'KEY `report_item_li_element_id_fk` (`element_id`)',
				'CONSTRAINT `report_item_li_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_li_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_li_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`)',
				'CONSTRAINT `report_item_li_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `report_item` (`id`)',
				'CONSTRAINT `report_item_li_list_item_id_fk` FOREIGN KEY (`list_item_id`) REFERENCES `report_item_list_item` (`id`)',
				'CONSTRAINT `report_item_li_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_item_list_item_conditional',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'list_item_id' => 'int(10) unsigned NOT NULL',
				'input_id' => 'int(10) unsigned NOT NULL',
				'match_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'result' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_item_lic_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_item_lic_created_user_id_fk` (`created_user_id`)',
				'KEY `report_item_lic_list_item_id_fk` (`list_item_id`)',
				'KEY `report_item_lic_input_id_fk` (`input_id`)',
				'CONSTRAINT `report_item_lic_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_lic_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_lic_list_item_id_fk` FOREIGN KEY (`list_item_id`) REFERENCES `report_item_list_item` (`id`)',
				'CONSTRAINT `report_item_lic_input_id_fk` FOREIGN KEY (`input_id`) REFERENCES `report_input` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_validation_rule_type',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_vrt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_vrt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `report_vrt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_vrt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_validation_rule',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'report_id' => 'int(10) unsigned NOT NULL',
				'rule_type_id' => 'int(10) unsigned NOT NULL',
				'rule' => 'varchar(1024) COLLATE utf8_bin NOT NULL',
				'message' => 'varchar(1024) COLLATE utf8_bin NOT NULL',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_vr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_vr_created_user_id_fk` (`created_user_id`)',
				'KEY `report_vr_report_id_fk` (`report_id`)',
				'CONSTRAINT `report_vr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_vr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_vr_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_graph',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'report_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_graph_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_graph_created_user_id_fk` (`created_user_id`)',
				'KEY `report_graph_report_id_fk` (`report_id`)',
				'CONSTRAINT `report_graph_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_graph_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_graph_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('report_graph_item',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'graph_id' => 'int(10) unsigned NOT NULL',
				'report_item_id' => 'int(10) unsigned NOT NULL',
				'show_scale' => 'tinyint(1) unsigned NOT NULL DEFAULT 1',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'subtitle' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'range' => 'float NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_graph_item_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_graph_item_created_user_id_fk` (`created_user_id`)',
				'KEY `report_graph_item_graph_id_fk` (`graph_id`)',
				'KEY `report_graph_item_report_item_id_fk` (`report_item_id`)',
				'CONSTRAINT `report_graph_item_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_graph_item_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_graph_item_graph_id_fk` FOREIGN KEY (`graph_id`) REFERENCES `report_graph` (`id`)',
				'CONSTRAINT `report_graph_item_report_item_id_fk` FOREIGN KEY (`report_item_id`) REFERENCES `report_item` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
	}

	public function down()
	{
		$this->dropTable('report_graph_item');
		$this->dropTable('report_graph');
		$this->dropTable('report_validation_rule');
		$this->dropTable('report_validation_rule_type');
		$this->dropTable('report_item_list_item_conditional');
		$this->dropTable('report_item_list_item');
		$this->dropTable('report_item_pair_field');
		$this->dropTable('report_item');
		$this->dropTable('report_item_data_type');
		$this->dropTable('report_input');
		$this->dropTable('report_input_data_type');
		$this->dropTable('report_dataset_related_entity_table_relation');
		$this->dropTable('report_dataset_related_entity_table');
		$this->dropTable('report_dataset_related_entity_type');
		$this->dropTable('report_dataset_related_entity');
		$this->dropTable('report_dataset_element_join');
		$this->dropTable('report_dataset_element_field');
		$this->dropTable('report_dataset_element');
		$this->dropTable('report_dataset');
		$this->dropTable('report');
		$this->dropTable('report_query_type');
	}
}
