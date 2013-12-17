<?php

class m131205_133940_table_versioning extends CDbMigration
{
	public function up()
	{
		$this->execute("
CREATE TABLE `report_dataset_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`report_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_dataset_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_dataset_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_dataset_report_id_fk` (`report_id`),
	CONSTRAINT `acv_report_dataset_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dataset_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dataset_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_version');

		$this->createIndex('report_dataset_aid_fk','report_dataset_version','id');
		$this->addForeignKey('report_dataset_aid_fk','report_dataset_version','id','report_dataset','id');

		$this->addColumn('report_dataset_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_version','version_id');
		$this->alterColumn('report_dataset_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_dataset_element_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`dataset_id` int(10) unsigned NOT NULL,
	`element_type_id` int(10) unsigned NOT NULL,
	`optional` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_de_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_de_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_de_dataset_id_fk` (`dataset_id`),
	KEY `acv_report_de_element_type_id_fk` (`element_type_id`),
	CONSTRAINT `acv_report_de_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_de_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_de_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`),
	CONSTRAINT `acv_report_de_element_type_id_fk` FOREIGN KEY (`element_type_id`) REFERENCES `element_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_element_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_element_version');

		$this->createIndex('report_dataset_element_aid_fk','report_dataset_element_version','id');
		$this->addForeignKey('report_dataset_element_aid_fk','report_dataset_element_version','id','report_dataset_element','id');

		$this->addColumn('report_dataset_element_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_element_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_element_version','version_id');
		$this->alterColumn('report_dataset_element_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_dataset_element_field_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`element_id` int(10) unsigned NOT NULL,
	`field` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_def_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_def_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_def_element_id_fk` (`element_id`),
	CONSTRAINT `acv_report_def_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_def_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_def_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_element_field_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_element_field_version');

		$this->createIndex('report_dataset_element_field_aid_fk','report_dataset_element_field_version','id');
		$this->addForeignKey('report_dataset_element_field_aid_fk','report_dataset_element_field_version','id','report_dataset_element_field','id');

		$this->addColumn('report_dataset_element_field_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_element_field_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_element_field_version','version_id');
		$this->alterColumn('report_dataset_element_field_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_dataset_element_join_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`element_id` int(10) unsigned NOT NULL,
	`join_table` varchar(64) COLLATE utf8_bin NOT NULL,
	`join_clause` varchar(64) COLLATE utf8_bin NOT NULL,
	`join_select` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_dej_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_dej_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_dej_element_id_fk` (`element_id`),
	CONSTRAINT `acv_report_dej_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dej_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dej_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_element_join_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_element_join_version');

		$this->createIndex('report_dataset_element_join_aid_fk','report_dataset_element_join_version','id');
		$this->addForeignKey('report_dataset_element_join_aid_fk','report_dataset_element_join_version','id','report_dataset_element_join','id');

		$this->addColumn('report_dataset_element_join_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_element_join_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_element_join_version','version_id');
		$this->alterColumn('report_dataset_element_join_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_dataset_related_entity_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`dataset_id` int(10) unsigned DEFAULT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`element_id` int(10) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_report_dre_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_dre_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_dre_dataset_id_fk` (`dataset_id`),
	KEY `acv_report_dre_element_id_fk` (`element_id`),
	CONSTRAINT `acv_report_dre_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`),
	CONSTRAINT `acv_report_dre_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dre_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`),
	CONSTRAINT `acv_report_dre_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_related_entity_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_related_entity_version');

		$this->createIndex('report_dataset_related_entity_aid_fk','report_dataset_related_entity_version','id');
		$this->addForeignKey('report_dataset_related_entity_aid_fk','report_dataset_related_entity_version','id','report_dataset_related_entity','id');

		$this->addColumn('report_dataset_related_entity_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_related_entity_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_related_entity_version','version_id');
		$this->alterColumn('report_dataset_related_entity_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_dataset_related_entity_table_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`entity_type_id` int(10) unsigned DEFAULT NULL,
	`entity_id` int(10) unsigned NOT NULL,
	`table_name` varchar(64) COLLATE utf8_bin NOT NULL,
	`table_related_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`table_query_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`table_date_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_dret_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_dret_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_dret_entity_id_fk` (`entity_id`),
	KEY `acv_report_dret_entity_type_id_fk` (`entity_type_id`),
	CONSTRAINT `acv_report_dret_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dret_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dret_entity_id_fk` FOREIGN KEY (`entity_id`) REFERENCES `report_dataset_related_entity` (`id`),
	CONSTRAINT `acv_report_dret_entity_type_id_fk` FOREIGN KEY (`entity_type_id`) REFERENCES `report_dataset_related_entity_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_related_entity_table_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_related_entity_table_version');

		$this->createIndex('report_dataset_related_entity_table_aid_fk','report_dataset_related_entity_table_version','id');
		$this->addForeignKey('report_dataset_related_entity_table_aid_fk','report_dataset_related_entity_table_version','id','report_dataset_related_entity_table','id');

		$this->addColumn('report_dataset_related_entity_table_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_related_entity_table_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_related_entity_table_version','version_id');
		$this->alterColumn('report_dataset_related_entity_table_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_dataset_related_entity_table_relation_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`entity_table_id` int(10) unsigned DEFAULT NULL,
	`local_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`related_table` varchar(64) COLLATE utf8_bin NOT NULL,
	`select_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`select_field_as` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_dretr_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_dretr_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_dretr_entity_table_id_fk` (`entity_table_id`),
	CONSTRAINT `acv_report_dretr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dretr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_dretr_entity_table_id_fk` FOREIGN KEY (`entity_table_id`) REFERENCES `report_dataset_related_entity_table` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_related_entity_table_relation_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_related_entity_table_relation_version');

		$this->createIndex('report_dataset_related_entity_table_relation_aid_fk','report_dataset_related_entity_table_relation_version','id');
		$this->addForeignKey('report_dataset_related_entity_table_relation_aid_fk','report_dataset_related_entity_table_relation_version','id','report_dataset_related_entity_table_relation','id');

		$this->addColumn('report_dataset_related_entity_table_relation_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_related_entity_table_relation_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_related_entity_table_relation_version','version_id');
		$this->alterColumn('report_dataset_related_entity_table_relation_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_dataset_related_entity_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`entity_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_ret_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_ret_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_ret_entity_id_fk` (`entity_id`),
	CONSTRAINT `acv_report_ret_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_ret_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_ret_entity_id_fk` FOREIGN KEY (`entity_id`) REFERENCES `report_dataset_related_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_dataset_related_entity_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_dataset_related_entity_type_version');

		$this->createIndex('report_dataset_related_entity_type_aid_fk','report_dataset_related_entity_type_version','id');
		$this->addForeignKey('report_dataset_related_entity_type_aid_fk','report_dataset_related_entity_type_version','id','report_dataset_related_entity_type','id');

		$this->addColumn('report_dataset_related_entity_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_dataset_related_entity_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_dataset_related_entity_type_version','version_id');
		$this->alterColumn('report_dataset_related_entity_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_graph_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`report_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_graph_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_graph_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_graph_report_id_fk` (`report_id`),
	CONSTRAINT `acv_report_graph_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_graph_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_graph_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_graph_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_graph_version');

		$this->createIndex('report_graph_aid_fk','report_graph_version','id');
		$this->addForeignKey('report_graph_aid_fk','report_graph_version','id','report_graph','id');

		$this->addColumn('report_graph_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_graph_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_graph_version','version_id');
		$this->alterColumn('report_graph_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_graph_item_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`graph_id` int(10) unsigned NOT NULL,
	`report_item_id` int(10) unsigned NOT NULL,
	`show_scale` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`subtitle` varchar(64) COLLATE utf8_bin NOT NULL,
	`range` float NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_graph_item_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_graph_item_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_graph_item_graph_id_fk` (`graph_id`),
	KEY `acv_report_graph_item_report_item_id_fk` (`report_item_id`),
	CONSTRAINT `acv_report_graph_item_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_graph_item_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_graph_item_graph_id_fk` FOREIGN KEY (`graph_id`) REFERENCES `report_graph` (`id`),
	CONSTRAINT `acv_report_graph_item_report_item_id_fk` FOREIGN KEY (`report_item_id`) REFERENCES `report_item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_graph_item_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_graph_item_version');

		$this->createIndex('report_graph_item_aid_fk','report_graph_item_version','id');
		$this->addForeignKey('report_graph_item_aid_fk','report_graph_item_version','id','report_graph_item','id');

		$this->addColumn('report_graph_item_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_graph_item_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_graph_item_version','version_id');
		$this->alterColumn('report_graph_item_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_input_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`dataset_id` int(10) unsigned NOT NULL,
	`data_type_id` int(10) unsigned NOT NULL,
	`data_type_param1` varchar(64) COLLATE utf8_bin NOT NULL,
	`data_type_param2` varchar(64) COLLATE utf8_bin NOT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`description` varchar(255) COLLATE utf8_bin NOT NULL,
	`default_value` varchar(255) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`required` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`or_id` int(10) unsigned DEFAULT NULL,
	`include` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`related_entity_id` int(10) unsigned DEFAULT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_input_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_input_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_input_data_type_id_fk` (`data_type_id`),
	KEY `acv_report_input_dataset_id_fk` (`dataset_id`),
	KEY `acv_report_input_related_entity_id_fk` (`related_entity_id`),
	CONSTRAINT `acv_report_input_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_input_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_input_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_input_data_type` (`id`),
	CONSTRAINT `acv_report_input_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`),
	CONSTRAINT `acv_report_input_related_entity_id_fk` FOREIGN KEY (`related_entity_id`) REFERENCES `report_dataset_related_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_input_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_input_version');

		$this->createIndex('report_input_aid_fk','report_input_version','id');
		$this->addForeignKey('report_input_aid_fk','report_input_version','id','report_input','id');

		$this->addColumn('report_input_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_input_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_input_version','version_id');
		$this->alterColumn('report_input_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_input_data_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_input_dm_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_input_dm_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_report_input_dm_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_input_dm_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_input_data_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_input_data_type_version');

		$this->createIndex('report_input_data_type_aid_fk','report_input_data_type_version','id');
		$this->addForeignKey('report_input_data_type_aid_fk','report_input_data_type_version','id','report_input_data_type','id');

		$this->addColumn('report_input_data_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_input_data_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_input_data_type_version','version_id');
		$this->alterColumn('report_input_data_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_input_option_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`input_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_input_option_input_id_fk` (`input_id`),
	KEY `acv_report_input_option_c_u_id_fk` (`created_user_id`),
	KEY `acv_report_input_option_l_m_u_id_fk` (`last_modified_user_id`),
	CONSTRAINT `acv_report_input_option_input_id_fk` FOREIGN KEY (`input_id`) REFERENCES `report_input` (`id`),
	CONSTRAINT `acv_report_input_option_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_input_option_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_input_option_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_input_option_version');

		$this->createIndex('report_input_option_aid_fk','report_input_option_version','id');
		$this->addForeignKey('report_input_option_aid_fk','report_input_option_version','id','report_input_option','id');

		$this->addColumn('report_input_option_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_input_option_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_input_option_version','version_id');
		$this->alterColumn('report_input_option_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_item_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`dataset_id` int(10) unsigned NOT NULL,
	`data_type_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`data_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`data_input_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`subtitle` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`display` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`element_id` int(10) unsigned DEFAULT NULL,
	`element_relation` varchar(64) COLLATE utf8_bin NOT NULL,
	`element_relation_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`element_relation_value` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_item_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_item_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_item_report_data_type_id_fk` (`data_type_id`),
	KEY `acv_report_item_dataset_id_fk` (`dataset_id`),
	KEY `acv_report_item_element_id_fk` (`element_id`),
	CONSTRAINT `acv_report_item_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`),
	CONSTRAINT `acv_report_item_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`),
	CONSTRAINT `acv_report_item_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_item_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_item_version');

		$this->createIndex('report_item_aid_fk','report_item_version','id');
		$this->addForeignKey('report_item_aid_fk','report_item_version','id','report_item','id');

		$this->addColumn('report_item_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_item_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_item_version','version_id');
		$this->alterColumn('report_item_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_item_data_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_item_data_type_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_item_data_type_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_report_item_data_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_data_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_item_data_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_item_data_type_version');

		$this->createIndex('report_item_data_type_aid_fk','report_item_data_type_version','id');
		$this->addForeignKey('report_item_data_type_aid_fk','report_item_data_type_version','id','report_item_data_type','id');

		$this->addColumn('report_item_data_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_item_data_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_item_data_type_version','version_id');
		$this->alterColumn('report_item_data_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_item_list_item_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`item_id` int(10) unsigned DEFAULT NULL,
	`list_item_id` int(10) unsigned DEFAULT NULL,
	`data_type_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`data_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`subtitle` varchar(64) COLLATE utf8_bin NOT NULL,
	`display` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`link` varchar(1024) COLLATE utf8_bin NOT NULL,
	`element_id` int(10) unsigned DEFAULT NULL,
	`element_relation` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_item_li_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_item_li_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_item_li_report_data_type_id_fk` (`data_type_id`),
	KEY `acv_report_item_li_item_id_fk` (`item_id`),
	KEY `acv_report_item_li_list_item_id_fk` (`list_item_id`),
	KEY `acv_report_item_li_element_id_fk` (`element_id`),
	CONSTRAINT `acv_report_item_li_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_li_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_li_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`),
	CONSTRAINT `acv_report_item_li_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `report_item` (`id`),
	CONSTRAINT `acv_report_item_li_list_item_id_fk` FOREIGN KEY (`list_item_id`) REFERENCES `report_item_list_item` (`id`),
	CONSTRAINT `acv_report_item_li_element_id_fk` FOREIGN KEY (`element_id`) REFERENCES `report_dataset_element` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_item_list_item_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_item_list_item_version');

		$this->createIndex('report_item_list_item_aid_fk','report_item_list_item_version','id');
		$this->addForeignKey('report_item_list_item_aid_fk','report_item_list_item_version','id','report_item_list_item','id');

		$this->addColumn('report_item_list_item_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_item_list_item_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_item_list_item_version','version_id');
		$this->alterColumn('report_item_list_item_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_item_list_item_conditional_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`list_item_id` int(10) unsigned NOT NULL,
	`input_id` int(10) unsigned NOT NULL,
	`match_field` varchar(64) COLLATE utf8_bin NOT NULL,
	`result` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_item_lic_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_item_lic_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_item_lic_list_item_id_fk` (`list_item_id`),
	KEY `acv_report_item_lic_input_id_fk` (`input_id`),
	CONSTRAINT `acv_report_item_lic_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_lic_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_lic_list_item_id_fk` FOREIGN KEY (`list_item_id`) REFERENCES `report_item_list_item` (`id`),
	CONSTRAINT `acv_report_item_lic_input_id_fk` FOREIGN KEY (`input_id`) REFERENCES `report_input` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_item_list_item_conditional_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_item_list_item_conditional_version');

		$this->createIndex('report_item_list_item_conditional_aid_fk','report_item_list_item_conditional_version','id');
		$this->addForeignKey('report_item_list_item_conditional_aid_fk','report_item_list_item_conditional_version','id','report_item_list_item_conditional','id');

		$this->addColumn('report_item_list_item_conditional_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_item_list_item_conditional_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_item_list_item_conditional_version','version_id');
		$this->alterColumn('report_item_list_item_conditional_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_item_pair_field_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`item_id` int(10) unsigned DEFAULT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`field` varchar(64) COLLATE utf8_bin NOT NULL,
	`value` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_item_pf_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_item_pf_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_item_pf_item_id_fk` (`item_id`),
	CONSTRAINT `acv_report_item_pf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_pf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_item_pf_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `report_item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_item_pair_field_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_item_pair_field_version');

		$this->createIndex('report_item_pair_field_aid_fk','report_item_pair_field_version','id');
		$this->addForeignKey('report_item_pair_field_aid_fk','report_item_pair_field_version','id','report_item_pair_field','id');

		$this->addColumn('report_item_pair_field_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_item_pair_field_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_item_pair_field_version','version_id');
		$this->alterColumn('report_item_pair_field_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_query_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_qt_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_qt_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_report_qt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_qt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_query_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_query_type_version');

		$this->createIndex('report_query_type_aid_fk','report_query_type_version','id');
		$this->addForeignKey('report_query_type_aid_fk','report_query_type_version','id','report_query_type','id');

		$this->addColumn('report_query_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_query_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_query_type_version','version_id');
		$this->alterColumn('report_query_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_validation_rule_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`report_id` int(10) unsigned NOT NULL,
	`rule_type_id` int(10) unsigned NOT NULL,
	`rule` varchar(1024) COLLATE utf8_bin NOT NULL,
	`message` varchar(1024) COLLATE utf8_bin NOT NULL,
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_vr_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_vr_created_user_id_fk` (`created_user_id`),
	KEY `acv_report_vr_report_id_fk` (`report_id`),
	CONSTRAINT `acv_report_vr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_vr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_vr_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_validation_rule_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_validation_rule_version');

		$this->createIndex('report_validation_rule_aid_fk','report_validation_rule_version','id');
		$this->addForeignKey('report_validation_rule_aid_fk','report_validation_rule_version','id','report_validation_rule','id');

		$this->addColumn('report_validation_rule_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_validation_rule_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_validation_rule_version','version_id');
		$this->alterColumn('report_validation_rule_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_validation_rule_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_report_vrt_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_report_vrt_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_report_vrt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_report_vrt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('report_validation_rule_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_validation_rule_type_version');

		$this->createIndex('report_validation_rule_type_aid_fk','report_validation_rule_type_version','id');
		$this->addForeignKey('report_validation_rule_type_aid_fk','report_validation_rule_type_version','id','report_validation_rule_type','id');

		$this->addColumn('report_validation_rule_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_validation_rule_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_validation_rule_type_version','version_id');
		$this->alterColumn('report_validation_rule_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `report_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`query_type_id` int(10) unsigned NOT NULL,
	`subspecialty_id` int(10) unsigned DEFAULT NULL,
	`name` varchar(64) COLLATE utf8_bin NOT NULL,
	`description` varchar(255) COLLATE utf8_bin NOT NULL,
	`icon` varchar(255) COLLATE utf8_bin NOT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`can_print` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`can_download` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `report_version_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `report_version_created_user_id_fk` (`created_user_id`),
	KEY `report_version_subspecialty_id_fk` (`subspecialty_id`),
	KEY `report_version_query_type_id_fk` (`query_type_id`),
	CONSTRAINT `report_version_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `report_version_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `report_version_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`),
	CONSTRAINT `report_version_query_type_id_fk` FOREIGN KEY (`query_type_id`) REFERENCES `report_query_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
");

		$this->alterColumn('report_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','report_version');

		$this->createIndex('report_aid_fk','report_version','id');
		$this->addForeignKey('report_aid_fk','report_version','id','report','id');

		$this->addColumn('report_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('report_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','report_version','version_id');
		$this->alterColumn('report_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->addColumn('report_dataset','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_element','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_element_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_element_field','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_element_field_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_element_join','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_element_join_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity_table','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity_table_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity_table_relation','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity_table_relation_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_dataset_related_entity_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_graph','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_graph_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_graph_item','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_graph_item_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_input','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_input_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_input_data_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_input_data_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_input_option','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_input_option_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_data_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_data_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_list_item','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_list_item_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_list_item_conditional','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_list_item_conditional_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_pair_field','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_item_pair_field_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_query_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_query_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_validation_rule','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_validation_rule_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_validation_rule_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_validation_rule_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report','deleted','tinyint(1) unsigned not null');
		$this->addColumn('report_version','deleted','tinyint(1) unsigned not null');
	}

	public function down()
	{
		$this->dropColumn('report_dataset','deleted');
		$this->dropColumn('report_dataset_element','deleted');
		$this->dropColumn('report_dataset_element_field','deleted');
		$this->dropColumn('report_dataset_element_join','deleted');
		$this->dropColumn('report_dataset_related_entity','deleted');
		$this->dropColumn('report_dataset_related_entity_table','deleted');
		$this->dropColumn('report_dataset_related_entity_table_relation','deleted');
		$this->dropColumn('report_dataset_related_entity_type','deleted');
		$this->dropColumn('report_graph','deleted');
		$this->dropColumn('report_graph_item','deleted');
		$this->dropColumn('report_input','deleted');
		$this->dropColumn('report_input_data_type','deleted');
		$this->dropColumn('report_input_option','deleted');
		$this->dropColumn('report_item','deleted');
		$this->dropColumn('report_item_data_type','deleted');
		$this->dropColumn('report_item_list_item','deleted');
		$this->dropColumn('report_item_list_item_conditional','deleted');
		$this->dropColumn('report_item_pair_field','deleted');
		$this->dropColumn('report_query_type','deleted');
		$this->dropColumn('report_validation_rule','deleted');
		$this->dropColumn('report_validation_rule_type','deleted');

		$this->dropTable('report_dataset_version');
		$this->dropTable('report_dataset_element_version');
		$this->dropTable('report_dataset_element_field_version');
		$this->dropTable('report_dataset_element_join_version');
		$this->dropTable('report_dataset_related_entity_version');
		$this->dropTable('report_dataset_related_entity_table_version');
		$this->dropTable('report_dataset_related_entity_table_relation_version');
		$this->dropTable('report_dataset_related_entity_type_version');
		$this->dropTable('report_graph_version');
		$this->dropTable('report_graph_item_version');
		$this->dropTable('report_input_version');
		$this->dropTable('report_input_data_type_version');
		$this->dropTable('report_input_option_version');
		$this->dropTable('report_item_version');
		$this->dropTable('report_item_data_type_version');
		$this->dropTable('report_item_list_item_version');
		$this->dropTable('report_item_list_item_conditional_version');
		$this->dropTable('report_item_pair_field_version');
		$this->dropTable('report_query_type_version');
		$this->dropTable('report_validation_rule_version');
		$this->dropTable('report_validation_rule_type_version');
		$this->dropTable('report_version');
	}
}
