<?php

class m130124_083001_reports_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('report',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'subspecialty_id' => 'int(10) unsigned NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'description' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'icon' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'controller' => 'varchar(64) COLLATE utf8_bin NOT NULL',
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
				'CONSTRAINT `report_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report',array('subspecialty_id'=>4,'name'=>'Cataract outcomes','description'=>'Cataract outcomes report','icon'=>'treatment_operation','display_order'=>1,'controller'=>'BaseEventTypeController','can_download'=>0));
		$this->insert('report',array('subspecialty_id'=>null,'name'=>'Patient diagnoses','description'=>'Patient diagnoses','icon'=>'treatment_operation','display_order'=>2,'controller'=>'PatientController'));
		$this->insert('report',array('subspecialty_id'=>null,'name'=>'Operations','description'=>'Operations','icon'=>'treatment_operation','display_order'=>3,'controller'=>'BaseEventTypeController'));

		$this->createTable('report_dataset',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'report_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
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

		$this->insert('report_dataset',array('report_id'=>1,'name'=>'dataset1'));
		$this->insert('report_dataset',array('report_id'=>1,'name'=>'dataset2'));

		$this->insert('report_dataset',array('report_id'=>3,'name'=>'dataset1'));

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

		$this->insert('report_dataset_element',array('dataset_id'=>1,'element_type_id'=>34));
		$this->insert('report_dataset_element',array('dataset_id'=>1,'element_type_id'=>41));
		$this->insert('report_dataset_element',array('dataset_id'=>1,'element_type_id'=>39));
		$this->insert('report_dataset_element',array('dataset_id'=>2,'element_type_id'=>39));

		$this->insert('report_dataset_element',array('dataset_id'=>3,'element_type_id'=>34));
		$this->insert('report_dataset_element',array('dataset_id'=>3,'element_type_id'=>41));
		$this->insert('report_dataset_element',array('dataset_id'=>3,'element_type_id'=>39,'optional'=>1));

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

		$this->insert('report_dataset_element_field',array('element_id'=>1,'field'=>'eye_id'));
		$this->insert('report_dataset_element_field',array('element_id'=>2,'field'=>'surgeon_id'));
		$this->insert('report_dataset_element_field',array('element_id'=>2,'field'=>'assistant_id'));
		$this->insert('report_dataset_element_field',array('element_id'=>2,'field'=>'supervising_surgeon_id'));

		$this->insert('report_dataset_element_field',array('element_id'=>5,'field'=>'eye_id'));
		$this->insert('report_dataset_element_field',array('element_id'=>6,'field'=>'surgeon_id'));
		$this->insert('report_dataset_element_field',array('element_id'=>6,'field'=>'assistant_id'));
		$this->insert('report_dataset_element_field',array('element_id'=>6,'field'=>'supervising_surgeon_id'));

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

		$this->insert('report_dataset_element_join',array('element_id'=>5,'join_table'=>'eye','join_clause'=>'eye_id = eye.id','join_select'=>'eye.name as eye'));

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

		$this->insert('report_input_data_type',array('name'=>'number','display_order'=>1));
		$this->insert('report_input_data_type',array('name'=>'dropdown_from_table','display_order'=>2));
		$this->insert('report_input_data_type',array('name'=>'date','display_order'=>3));
		$this->insert('report_input_data_type',array('name'=>'diagnoses','display_order'=>4));
		$this->insert('report_input_data_type',array('name'=>'checkbox','display_order'=>5));
		$this->insert('report_input_data_type',array('name'=>'checkbox_optional_match','display_order'=>6));

		$this->createTable('report_input',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'report_id' => 'int(10) unsigned NOT NULL',
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
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_input_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_input_created_user_id_fk` (`created_user_id`)',
				'KEY `report_input_report_id_fk` (`report_id`)',
				'KEY `report_input_data_type_id_fk` (`data_type_id`)',
				'KEY `report_input_dataset_id_fk` (`dataset_id`)',
				'CONSTRAINT `report_input_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)',
				'CONSTRAINT `report_input_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_input_data_type` (`id`)',
				'CONSTRAINT `report_input_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report_input',array('report_id'=>1,'dataset_id'=>1,'data_type_id'=>2,'data_type_param1'=>'Firm','data_type_param2'=>'getListWithSpecialties','name'=>'firm_id','description'=>'Firm','display_order'=>1));
		$this->insert('report_input',array('report_id'=>1,'dataset_id'=>1,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'surgeon_id','description'=>'Surgeon','display_order'=>2,'or_id'=>1));
		$this->insert('report_input',array('report_id'=>1,'dataset_id'=>1,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'assistant_id','description'=>'Assistant surgeon','display_order'=>3,'or_id'=>1));
		$this->insert('report_input',array('report_id'=>1,'dataset_id'=>1,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'supervising_surgeon_id','description'=>'Supervising surgeon','display_order'=>4,'or_id'=>1));
		$this->insert('report_input',array('report_id'=>1,'dataset_id'=>1,'data_type_id'=>3,'name'=>'date_from','description'=>'Date from','default_value'=>'-12 months','display_order'=>5,'required'=>1));
		$this->insert('report_input',array('report_id'=>1,'dataset_id'=>1,'data_type_id'=>3,'name'=>'date_to','description'=>'Date to','default_value'=>'now','display_order'=>6,'required'=>1));

		$this->insert('report_input',array('report_id'=>2,'dataset_id'=>1,'data_type_id'=>3,'name'=>'date_from','description'=>'Start date','default_value'=>'-12 months','display_order'=>1,'required'=>1));
		$this->insert('report_input',array('report_id'=>2,'dataset_id'=>1,'data_type_id'=>3,'name'=>'date_to','description'=>'End date','default_value'=>'now','display_order'=>2,'required'=>1));
		$this->insert('report_input',array('report_id'=>2,'dataset_id'=>1,'data_type_id'=>4,'name'=>'diagnoses','description'=>'Diagnoses','default_value'=>'','display_order'=>3));

		$this->insert('report_input',array('report_id'=>3,'dataset_id'=>1,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'surgeon_id','description'=>'Surgeon','display_order'=>1,'required'=>1,'include'=>0));
		$this->insert('report_input',array('report_id'=>3,'dataset_id'=>1,'data_type_id'=>6,'name'=>'match_surgeon','default_value'=>1,'description'=>'Match surgeon','display_order'=>2,'data_type_param1'=>'surgeon_id','data_type_param2'=>'surgeon_id','or_id'=>1));
		$this->insert('report_input',array('report_id'=>3,'dataset_id'=>1,'data_type_id'=>6,'name'=>'match_assistant_surgeon','default_value'=>1,'description'=>'Match assistant surgeon','display_order'=>3,'data_type_param1'=>'surgeon_id','data_type_param2'=>'assistant_id','or_id'=>1));
		$this->insert('report_input',array('report_id'=>3,'dataset_id'=>1,'data_type_id'=>6,'name'=>'match_supervising_surgeon','default_value'=>1,'description'=>'Match supervising surgeon','display_order'=>4,'data_type_param1'=>'surgeon_id','data_type_param2'=>'supervising_surgeon_id','or_id'=>1));
		$this->insert('report_input',array('report_id'=>3,'dataset_id'=>1,'data_type_id'=>3,'name'=>'date_from','description'=>'Date from','default_value'=>'-12 months','display_order'=>5,'required'=>1));
		$this->insert('report_input',array('report_id'=>3,'dataset_id'=>1,'data_type_id'=>3,'name'=>'date_to','description'=>'Date to','default_value'=>'now','display_order'=>6,'required'=>1));

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

		$this->insert('report_item_data_type',array('name'=>'total'));
		$this->insert('report_item_data_type',array('name'=>'mean_and_range'));
		$this->insert('report_item_data_type',array('name'=>'number_and_percentage'));
		$this->insert('report_item_data_type',array('name'=>'number_and_percentage_pair'));
		$this->insert('report_item_data_type',array('name'=>'list'));
		$this->insert('report_item_data_type',array('name'=>'string'));
		$this->insert('report_item_data_type',array('name'=>'date'));
		$this->insert('report_item_data_type',array('name'=>'NHSDate'));
		$this->insert('report_item_data_type',array('name'=>'conditional'));
		$this->insert('report_item_data_type',array('name'=>'list_from_element_relation'));
		$this->insert('report_item_data_type',array('name'=>'element_relation'));

		$this->createTable('report_item',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'dataset_id' => 'int(10) unsigned NOT NULL',
				'report_id' => 'int(10) unsigned NOT NULL',
				'data_type_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_input_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'subtitle' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'display' => 'tinyint(1) unsigned NOT NULL DEFAULT 1',
				'element_type_id' => 'int(10) unsigned NULL',
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
				'KEY `report_item_report_id_fk` (`report_id`)',
				'KEY `report_item_dataset_id_fk` (`dataset_id`)',
				'KEY `report_item_element_type_id_fk` (`element_type_id`)',
				'CONSTRAINT `report_item_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`)',
				'CONSTRAINT `report_item_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)',
				'CONSTRAINT `report_item_dataset_id_fk` FOREIGN KEY (`dataset_id`) REFERENCES `report_dataset` (`id`)',
				'CONSTRAINT `report_item_element_type_id_fk` FOREIGN KEY (`element_type_id`) REFERENCES `element_type` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>1,'data_type_id'=>1,'name'=>'Cataracts','data_field'=>'cataracts','subtitle'=>'Number of cataracts performed','display_order'=>1));
		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>1,'data_type_id'=>2,'name'=>'Age','data_field'=>'age','data_input_field'=>'age','subtitle'=>'Age of patients','display_order'=>2));
		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>1,'data_type_id'=>4,'name'=>'Eyes','data_field'=>'eyes','subtitle'=>'Eyes','display_order'=>3));
		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>1,'data_type_id'=>2,'name'=>'Final visual acuity','data_field'=>'final_visual_acuity','subtitle'=>'Final visual acuity','display_order'=>4));
		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>1,'data_type_id'=>3,'name'=>'PC ruptures','data_field'=>'pc_ruptures','subtitle'=>'PC ruptures','display_order'=>5,'element_type_id'=>39,'element_relation'=>'complications','element_relation_field'=>'complication_id','element_relation_value'=>'11'));
		$this->insert('report_item',array('dataset_id'=>2,'report_id'=>1,'data_type_id'=>3,'name'=>'Average','data_field'=>'pc_rupture_average','subtitle'=>'Average','display_order'=>6,'display'=>0,'element_type_id'=>39,'element_relation'=>'complications','element_relation_field'=>'complication_id','element_relation_value'=>'11'));
		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>1,'data_type_id'=>3,'name'=>'Complications','data_field'=>'complications','subtitle'=>'All complications','display_order'=>7,'element_type_id'=>39,'element_relation'=>'complications'));
		$this->insert('report_item',array('dataset_id'=>2,'report_id'=>1,'data_type_id'=>3,'name'=>'Average','data_field'=>'complication_average','subtitle'=>'Average','display_order'=>8,'display'=>0,'element_type_id'=>39,'element_relation'=>'complications'));

		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>2,'data_type_id'=>5,'name'=>'Patients','data_field'=>'patients','subtitle'=>'Patient diagnoses','display_order'=>1));

		$this->insert('report_item',array('dataset_id'=>1,'report_id'=>3,'data_type_id'=>5,'name'=>'Operations','data_field'=>'operations','subtitle'=>'Operations','display_order'=>1));

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

		$this->insert('report_item_pair_field',array('item_id'=>3,'name'=>'left','field'=>'eye_id','value'=>'1'));
		$this->insert('report_item_pair_field',array('item_id'=>3,'name'=>'right','field'=>'eye_id','value'=>'2'));

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
				'element_type_id' => 'int(10) unsigned NULL',
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
				'KEY `report_item_li_element_type_id_fk` (`element_type_id`)',
				'CONSTRAINT `report_item_li_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_li_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_li_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`)',
				'CONSTRAINT `report_item_li_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `report_item` (`id`)',
				'CONSTRAINT `report_item_li_list_item_id_fk` FOREIGN KEY (`list_item_id`) REFERENCES `report_item_list_item` (`id`)',
				'CONSTRAINT `report_item_li_element_type_id_fk` FOREIGN KEY (`element_type_id`) REFERENCES `element_type` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report_item_list_item',array('item_id'=>9,'data_type_id'=>8,'name'=>'Date','data_field'=>'datetime','subtitle'=>'Date','display_order'=>1));

		$this->insert('report_item_list_item',array('item_id'=>9,'data_type_id'=>6,'name'=>'Hospital no','data_field'=>'hos_num','subtitle'=>'Patient hospital number','display_order'=>2,'link'=>'/patient/episodes/{patient_id}'));

		$this->insert('report_item_list_item',array('item_id'=>9,'data_type_id'=>6,'name'=>'First name','data_field'=>'first_name','subtitle'=>'Patient first name','display_order'=>3));
		$this->insert('report_item_list_item',array('item_id'=>9,'data_type_id'=>6,'name'=>'Last name','data_field'=>'last_name','subtitle'=>'Patient last name','display_order'=>4));
		$this->insert('report_item_list_item',array('item_id'=>9,'data_type_id'=>1,'name'=>'Patient ID','data_field'=>'patient_id','subtitle'=>'Patient ID','display'=>0));
		$this->insert('report_item_list_item',array('item_id'=>9,'data_type_id'=>5,'name'=>'Diagnoses','data_field'=>'diagnoses','subtitle'=>'Diagnoses','display_order'=>5));
		$this->insert('report_item_list_item',array('list_item_id'=>6,'data_type_id'=>6,'name'=>'Eye','data_field'=>'eye','subtitle'=>'Eye','display_order'=>1));
		$this->insert('report_item_list_item',array('list_item_id'=>6,'data_type_id'=>6,'name'=>'Diagnosis','data_field'=>'diagnosis','subtitle'=>'Diagnosis','display_order'=>2));

		$this->insert('report_item_list_item',array('item_id'=>10,'data_type_id'=>8,'name'=>'Date','data_field'=>'datetime','subtitle'=>'Date','display_order'=>1));
		$this->insert('report_item_list_item',array('item_id'=>10,'data_type_id'=>6,'name'=>'Hospital no','data_field'=>'hos_num','subtitle'=>'Patient hospital number','display_order'=>2,'link'=>'/patient/episodes/{patient_id}'));
		$this->insert('report_item_list_item',array('item_id'=>10,'data_type_id'=>6,'name'=>'First name','data_field'=>'first_name','subtitle'=>'Patient first name','display_order'=>3));
		$this->insert('report_item_list_item',array('item_id'=>10,'data_type_id'=>6,'name'=>'Last name','data_field'=>'last_name','subtitle'=>'Patient last name','display_order'=>4));
		$this->insert('report_item_list_item',array('item_id'=>10,'data_type_id'=>10,'name'=>'Procedures','data_field'=>'procedures','subtitle'=>'Procedures','display_order'=>5,'element_type_id'=>34,'element_relation'=>'procedures'));
		$this->insert('report_item_list_item',array('item_id'=>10,'data_type_id'=>10,'name'=>'Complications','data_field'=>'complications','subtitle'=>'Complications','display_order'=>6,'element_type_id'=>39,'element_relation'=>'complications'));
		$this->insert('report_item_list_item',array('item_id'=>10,'data_type_id'=>9,'name'=>'Role','data_field'=>'role','subtitle'=>'Role','display_order'=>7));
		$this->insert('report_item_list_item',array('list_item_id'=>11,'data_type_id'=>6,'name'=>'Eye','data_field'=>'eye','subtitle'=>'Eye','display_order'=>1));
		$this->insert('report_item_list_item',array('list_item_id'=>11,'data_type_id'=>6,'name'=>'Procedure','data_field'=>'procedure','subtitle'=>'Procedure','display_order'=>2));
		$this->insert('report_item_list_item',array('list_item_id'=>12,'data_type_id'=>6,'name'=>'Complication','data_field'=>'complication','subtitle'=>'Complication','display_order'=>1));

		$this->createTable('report_item_list_item_field',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'list_item_id' => 'int(10) unsigned NOT NULL',
				'data_type_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_item_lif_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_item_lif_created_user_id_fk` (`created_user_id`)',
				'KEY `report_item_lif_list_item_id_fk` (`list_item_id`)',
				'KEY `report_item_lif_data_type_id_fk` (`data_type_id`)',
				'CONSTRAINT `report_item_lif_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_lif_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_lif_list_item_id_fk` FOREIGN KEY (`list_item_id`) REFERENCES `report_item_list_item` (`id`)',
				'CONSTRAINT `report_item_lif_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report_item_list_item_field',array('list_item_id'=>13,'data_type_id'=>6,'name'=>'eye','data_field'=>'eye'));
		$this->insert('report_item_list_item_field',array('list_item_id'=>13,'data_type_id'=>11,'name'=>'procedure','data_field'=>'term'));
		$this->insert('report_item_list_item_field',array('list_item_id'=>14,'data_type_id'=>11,'name'=>'name','data_field'=>'name'));

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

		$this->insert('report_item_list_item_conditional',array('list_item_id'=>15,'input_id'=>10,'match_field'=>'surgeon_id','result'=>'Surgeon'));
		$this->insert('report_item_list_item_conditional',array('list_item_id'=>15,'input_id'=>10,'match_field'=>'assistant_id','result'=>'Assistant surgeon'));
		$this->insert('report_item_list_item_conditional',array('list_item_id'=>15,'input_id'=>10,'match_field'=>'supervising_surgeon_id','result'=>'Supervising surgeon'));

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

		$this->insert('report_validation_rule_type',array('name'=>'One of'));

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

		$this->insert('report_validation_rule',array('report_id'=>3,'rule_type_id'=>1,'rule'=>'match_surgeon,match_assistant_surgeon,match_supervising_surgeon','message'=>'At least one of the surgeon checkboxes must be selected'));

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

		$this->insert('report_graph',array('report_id'=>1,'name'=>'Cataract complication rate','display_order'=>1));

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

		$this->insert('report_graph_item',array('graph_id'=>1,'report_item_id'=>5,'name'=>'PC rupture rate','subtitle'=>'percentage','range'=>100,'display_order'=>1,'show_scale'=>0));
		$this->insert('report_graph_item',array('graph_id'=>1,'report_item_id'=>6,'name'=>'Average rate','subtitle'=>'institution average','range'=>100,'display_order'=>2));
		$this->insert('report_graph_item',array('graph_id'=>1,'report_item_id'=>7,'name'=>'Complication rate','subtitle'=>'percentage','range'=>100,'display_order'=>3,'show_scale'=>0));
		$this->insert('report_graph_item',array('graph_id'=>1,'report_item_id'=>8,'name'=>'Average rate','subtitle'=>'institution average','range'=>100,'display_order'=>4));
	}

	public function down()
	{
		$this->dropTable('report_graph_item');
		$this->dropTable('report_graph');
		$this->dropTable('report_validation_rule');
		$this->dropTable('report_validation_rule_type');
		$this->dropTable('report_item_list_item_conditional');
		$this->dropTable('report_item_list_item_field');
		$this->dropTable('report_item_list_item');
		$this->dropTable('report_item_pair_field');
		$this->dropTable('report_item');
		$this->dropTable('report_item_data_type');
		$this->dropTable('report_input');
		$this->dropTable('report_input_data_type');
		$this->dropTable('report_dataset_element_join');
		$this->dropTable('report_dataset_element_field');
		$this->dropTable('report_dataset_element');
		$this->dropTable('report_dataset');
		$this->dropTable('report');
	}
}
