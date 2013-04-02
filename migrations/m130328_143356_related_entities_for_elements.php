<?php

class m130328_143356_related_entities_for_elements extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('report_dataset_related_entity','dataset_id','int(10) unsigned NULL');
		$this->addColumn('report_dataset_related_entity','element_id','int(10) unsigned NULL');
		$this->createIndex('report_dre_element_id_fk','report_dataset_related_entity','element_id');
		$this->addForeignKey('report_dre_element_id_fk','report_dataset_related_entity','element_id','report_dataset_element','id');
	}

	public function down()
	{
		$this->dropForeignKey('report_dre_element_id_fk','report_dataset_related_entity');
		$this->dropIndex('report_dre_element_id_fk','report_dataset_related_entity');
		$this->dropColumn('report_dataset_related_entity','element_id');
		$this->alterColumn('report_dataset_related_entity','dataset_id','int(10) unsigned NOT NULL');
	}
}
