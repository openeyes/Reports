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
				'module' => 'varchar(64) COLLATE utf8_bin NULL',
				'controller' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'method' => 'varchar(64) COLLATE utf8_bin NOT NULL',
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

		$this->insert('report',array('subspecialty_id'=>4,'name'=>'Cataract outcomes','description'=>'Cataract outcomes report','icon'=>'treatment_operation','display_order'=>1,'module'=>'OphTrOperationnote','controller'=>'ReportController','method'=>'reportCataractOperations'));
		$this->insert('report',array('subspecialty_id'=>null,'name'=>'Patient diagnoses','description'=>'Patient diagnoses','icon'=>'treatment_operation','display_order'=>2,'module'=>null,'controller'=>'PatientController','method'=>'reportDiagnoses'));
		$this->insert('report',array('subspecialty_id'=>null,'name'=>'Operations','description'=>'Operations','icon'=>'treatment_operation','display_order'=>3,'module'=>'OphTrOperationnote','controller'=>'ReportController','method'=>'reportOperations'));

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

		$this->createTable('report_input',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'report_id' => 'int(10) unsigned NOT NULL',
				'data_type_id' => 'int(10) unsigned NOT NULL',
				'data_type_param1' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_type_param2' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'description' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'default_value' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_input_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_input_created_user_id_fk` (`created_user_id`)',
				'KEY `report_input_report_id_fk` (`report_id`)',
				'KEY `report_input_data_type_id_fk` (`data_type_id`)',
				'CONSTRAINT `report_input_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_input_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)',
				'CONSTRAINT `report_input_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_input_data_type` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report_input',array('report_id'=>1,'data_type_id'=>2,'data_type_param1'=>'Firm','data_type_param2'=>'getListWithSpecialties','name'=>'firm_id','description'=>'Firm','display_order'=>1));
		$this->insert('report_input',array('report_id'=>1,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'surgeon_id','description'=>'Surgeon','display_order'=>2));
		$this->insert('report_input',array('report_id'=>1,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'assistant_id','description'=>'Assistant surgeon','display_order'=>3));
		$this->insert('report_input',array('report_id'=>1,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'supervising_surgeon_id','description'=>'Supervising surgeon','display_order'=>4));
		$this->insert('report_input',array('report_id'=>1,'data_type_id'=>3,'name'=>'date_from','description'=>'Date from','default_value'=>'-12 months','display_order'=>5));
		$this->insert('report_input',array('report_id'=>1,'data_type_id'=>3,'name'=>'date_to','description'=>'Date to','default_value'=>'now','display_order'=>6));

		$this->insert('report_input',array('report_id'=>2,'data_type_id'=>3,'name'=>'date_from','description'=>'Start date','default_value'=>'-12 months','display_order'=>1));
		$this->insert('report_input',array('report_id'=>2,'data_type_id'=>3,'name'=>'date_to','description'=>'End date','default_value'=>'now','display_order'=>2));
		$this->insert('report_input',array('report_id'=>2,'data_type_id'=>4,'name'=>'diagnoses','description'=>'Diagnoses','default_value'=>'','display_order'=>3));

		$this->insert('report_input',array('report_id'=>3,'data_type_id'=>2,'data_type_param1'=>'User','data_type_param2'=>'getListSurgeons','name'=>'surgeon_id','description'=>'Surgeon','display_order'=>1));
		$this->insert('report_input',array('report_id'=>3,'data_type_id'=>5,'name'=>'match_surgeon','default_value'=>1,'description'=>'Match surgeon','display_order'=>2));
		$this->insert('report_input',array('report_id'=>3,'data_type_id'=>5,'name'=>'match_assistant_surgeon','default_value'=>1,'description'=>'Match assistant surgeon','display_order'=>3));
		$this->insert('report_input',array('report_id'=>3,'data_type_id'=>5,'name'=>'match_supervising_surgeon','default_value'=>1,'description'=>'Match supervising surgeon','display_order'=>4));
		$this->insert('report_input',array('report_id'=>3,'data_type_id'=>3,'name'=>'date_from','description'=>'Date from','default_value'=>'-12 months','display_order'=>5));
		$this->insert('report_input',array('report_id'=>3,'data_type_id'=>3,'name'=>'date_to','description'=>'Date to','default_value'=>'now','display_order'=>6));

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

		$this->insert('report_item_data_type',array('name'=>'number'));
		$this->insert('report_item_data_type',array('name'=>'mean_and_range'));
		$this->insert('report_item_data_type',array('name'=>'number_and_percentage'));
		$this->insert('report_item_data_type',array('name'=>'number_and_percentage_pair'));
		$this->insert('report_item_data_type',array('name'=>'list'));
		$this->insert('report_item_data_type',array('name'=>'string'));
		$this->insert('report_item_data_type',array('name'=>'date'));

		$this->createTable('report_item',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'report_id' => 'int(10) unsigned NOT NULL',
				'data_type_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'data_field' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'subtitle' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `report_item_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `report_item_created_user_id_fk` (`created_user_id`)',
				'KEY `report_item_report_data_type_id_fk` (`data_type_id`)',
				'KEY `report_item_report_id_fk` (`report_id`)',
				'CONSTRAINT `report_item_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`)',
				'CONSTRAINT `report_item_report_id_fk` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report_item',array('report_id'=>1,'data_type_id'=>1,'name'=>'Cataracts','data_field'=>'cataracts','subtitle'=>'Number of cataracts performed','display_order'=>1));
		$this->insert('report_item',array('report_id'=>1,'data_type_id'=>2,'name'=>'Age','data_field'=>'age','subtitle'=>'Age of patients','display_order'=>2));
		$this->insert('report_item',array('report_id'=>1,'data_type_id'=>4,'name'=>'Eyes','data_field'=>'eyes','subtitle'=>'Eyes','display_order'=>3));
		$this->insert('report_item',array('report_id'=>1,'data_type_id'=>2,'name'=>'Final visual acuity','data_field'=>'final_visual_acuity','subtitle'=>'Final visual acuity','display_order'=>4));
		$this->insert('report_item',array('report_id'=>1,'data_type_id'=>3,'name'=>'PC ruptures','data_field'=>'pc_ruptures','subtitle'=>'PC ruptures','display_order'=>5));
		$this->insert('report_item',array('report_id'=>1,'data_type_id'=>3,'name'=>'Complications','data_field'=>'complications','subtitle'=>'All complications','display_order'=>6));

		$this->insert('report_item',array('report_id'=>2,'data_type_id'=>5,'name'=>'Patients','data_field'=>'patients','subtitle'=>'Patient diagnoses','display_order'=>1));

		$this->insert('report_item',array('report_id'=>3,'data_type_id'=>5,'name'=>'Operations','data_field'=>'operations','subtitle'=>'Operations','display_order'=>1));

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
				'CONSTRAINT `report_item_li_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_li_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `report_item_li_report_data_type_id_fk` FOREIGN KEY (`data_type_id`) REFERENCES `report_item_data_type` (`id`)',
				'CONSTRAINT `report_item_li_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `report_item` (`id`)',
				'CONSTRAINT `report_item_li_list_item_id_fk` FOREIGN KEY (`list_item_id`) REFERENCES `report_item_list_item` (`id`)',
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('report_item_list_item',array('item_id'=>7,'data_type_id'=>7,'name'=>'Date','data_field'=>'date','subtitle'=>'Date','display_order'=>1));

		$this->insert('report_item_list_item',array('item_id'=>7,'data_type_id'=>6,'name'=>'Hospital no','data_field'=>'hos_num','subtitle'=>'Patient hospital number','display_order'=>2,'link'=>'/patient/episodes/{patient_id}'));

		$this->insert('report_item_list_item',array('item_id'=>7,'data_type_id'=>6,'name'=>'First name','data_field'=>'first_name','subtitle'=>'Patient first name','display_order'=>3));
		$this->insert('report_item_list_item',array('item_id'=>7,'data_type_id'=>6,'name'=>'Last name','data_field'=>'last_name','subtitle'=>'Patient last name','display_order'=>4));
		$this->insert('report_item_list_item',array('item_id'=>7,'data_type_id'=>1,'name'=>'Patient ID','data_field'=>'patient_id','subtitle'=>'Patient ID','display'=>0));
		$this->insert('report_item_list_item',array('item_id'=>7,'data_type_id'=>5,'name'=>'Diagnoses','data_field'=>'diagnoses','subtitle'=>'Diagnoses','display_order'=>5));
		$this->insert('report_item_list_item',array('list_item_id'=>6,'data_type_id'=>6,'name'=>'Eye','data_field'=>'eye','subtitle'=>'Eye','display_order'=>1));
		$this->insert('report_item_list_item',array('list_item_id'=>6,'data_type_id'=>6,'name'=>'Diagnosis','data_field'=>'diagnosis','subtitle'=>'Diagnosis','display_order'=>2));

		$this->insert('report_item_list_item',array('item_id'=>8,'data_type_id'=>7,'name'=>'Date','data_field'=>'date','subtitle'=>'Date','display_order'=>1));
		$this->insert('report_item_list_item',array('item_id'=>8,'data_type_id'=>6,'name'=>'Hospital no','data_field'=>'hos_num','subtitle'=>'Patient hospital number','display_order'=>2,'link'=>'/patient/episodes/{patient_id}'));
		$this->insert('report_item_list_item',array('item_id'=>8,'data_type_id'=>6,'name'=>'First name','data_field'=>'first_name','subtitle'=>'Patient first name','display_order'=>3));
		$this->insert('report_item_list_item',array('item_id'=>8,'data_type_id'=>6,'name'=>'Last name','data_field'=>'last_name','subtitle'=>'Patient last name','display_order'=>4));
		$this->insert('report_item_list_item',array('item_id'=>8,'data_type_id'=>5,'name'=>'Procedures','data_field'=>'procedures','subtitle'=>'Procedures','display_order'=>5));
		$this->insert('report_item_list_item',array('item_id'=>8,'data_type_id'=>5,'name'=>'Complications','data_field'=>'complications','subtitle'=>'Complications','display_order'=>6));
		$this->insert('report_item_list_item',array('item_id'=>8,'data_type_id'=>6,'name'=>'Role','data_field'=>'role','subtitle'=>'Role','display_order'=>7));
		$this->insert('report_item_list_item',array('list_item_id'=>11,'data_type_id'=>6,'name'=>'Eye','data_field'=>'eye','subtitle'=>'Eye','display_order'=>1));
		$this->insert('report_item_list_item',array('list_item_id'=>11,'data_type_id'=>6,'name'=>'Procedure','data_field'=>'procedure','subtitle'=>'Procedure','display_order'=>2));
		$this->insert('report_item_list_item',array('list_item_id'=>12,'data_type_id'=>6,'name'=>'Complication','data_field'=>'complication','subtitle'=>'Complication','display_order'=>1));

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

		$this->insert('report_graph_item',array('graph_id'=>1,'report_item_id'=>5,'name'=>'PC rupture rate','subtitle'=>'number of','range'=>100,'display_order'=>1));
		$this->insert('report_graph_item',array('graph_id'=>1,'report_item_id'=>6,'name'=>'Complication rate','subtitle'=>'number of','range'=>100,'display_order'=>2));
	}

	public function down()
	{
		$this->dropTable('report_graph_item');
		$this->dropTable('report_graph');
		$this->dropTable('report_item_list_item');
		$this->dropTable('report_item');
		$this->dropTable('report_item_data_type');
		$this->dropTable('report_input');
		$this->dropTable('report_input_data_type');
		$this->dropTable('report');
	}
}
