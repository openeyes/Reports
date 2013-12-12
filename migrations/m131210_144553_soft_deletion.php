<?php

class m131210_144553_soft_deletion extends CDbMigration
{
	public function up()
	{
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
	}

	public function down()
	{
		$this->dropColumn('report_dataset','deleted');
		$this->dropColumn('report_dataset_version','deleted');
		$this->dropColumn('report_dataset_element','deleted');
		$this->dropColumn('report_dataset_element_version','deleted');
		$this->dropColumn('report_dataset_element_field','deleted');
		$this->dropColumn('report_dataset_element_field_version','deleted');
		$this->dropColumn('report_dataset_element_join','deleted');
		$this->dropColumn('report_dataset_element_join_version','deleted');
		$this->dropColumn('report_dataset_related_entity','deleted');
		$this->dropColumn('report_dataset_related_entity_version','deleted');
		$this->dropColumn('report_dataset_related_entity_table','deleted');
		$this->dropColumn('report_dataset_related_entity_table_version','deleted');
		$this->dropColumn('report_dataset_related_entity_table_relation','deleted');
		$this->dropColumn('report_dataset_related_entity_table_relation_version','deleted');
		$this->dropColumn('report_dataset_related_entity_type','deleted');
		$this->dropColumn('report_dataset_related_entity_type_version','deleted');
		$this->dropColumn('report_graph','deleted');
		$this->dropColumn('report_graph_version','deleted');
		$this->dropColumn('report_graph_item','deleted');
		$this->dropColumn('report_graph_item_version','deleted');
		$this->dropColumn('report_input','deleted');
		$this->dropColumn('report_input_version','deleted');
		$this->dropColumn('report_input_data_type','deleted');
		$this->dropColumn('report_input_data_type_version','deleted');
		$this->dropColumn('report_input_option','deleted');
		$this->dropColumn('report_input_option_version','deleted');
		$this->dropColumn('report_item','deleted');
		$this->dropColumn('report_item_version','deleted');
		$this->dropColumn('report_item_data_type','deleted');
		$this->dropColumn('report_item_data_type_version','deleted');
		$this->dropColumn('report_item_list_item','deleted');
		$this->dropColumn('report_item_list_item_version','deleted');
		$this->dropColumn('report_item_list_item_conditional','deleted');
		$this->dropColumn('report_item_list_item_conditional_version','deleted');
		$this->dropColumn('report_item_pair_field','deleted');
		$this->dropColumn('report_item_pair_field_version','deleted');
		$this->dropColumn('report_query_type','deleted');
		$this->dropColumn('report_query_type_version','deleted');
		$this->dropColumn('report_validation_rule','deleted');
		$this->dropColumn('report_validation_rule_version','deleted');
		$this->dropColumn('report_validation_rule_type','deleted');
		$this->dropColumn('report_validation_rule_type_version','deleted');
	}
}
