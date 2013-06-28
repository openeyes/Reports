<?php

class m130628_075604_database_settings extends CDbMigration
{
	public function up()
	{
		$this->insert('config_key',array(
			'config_group_id' => 4,
			'module_name' => 'Reports',
			'name' => 'report_db',
			'label' => 'Database instance for reports',
			'config_type_id' => 3,
			'default_value' => '',
			'display_order' => 10,
		));
	}

	public function down()
	{
		$this->delete('config_key',"module_name = 'Reports'");
	}
}
