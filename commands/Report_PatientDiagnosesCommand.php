<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class Report_PatientDiagnosesCommand extends CConsoleCommand {
	public function run($args) {
		Yii::import('application.modules.Reports.models.*');

		if (!$query_type_patients = ReportQueryType::model()->find('name=?',array('Patients'))) {
			$query_type_patients = new ReportQueryType;
			$query_type_patients->name = 'Patients';
			$query_type_patients->display_order = 1;
			if (!$query_type_patients->save()) {
				print_r($query_type_patients->getErrors(),true);
				exit;
			}
		}

		/* input data types */

		$ridt_number = ReportInputDataType::add('number',1);
		$ridt_dropdown_from_table = ReportInputDataType::add('dropdown_from_table',2);
		$ridt_date = ReportInputDataType::add('date',3);
		$ridt_diagnoses = ReportInputDataType::add('diagnoses',4);
		$ridt_checkbox = ReportInputDataType::add('checkbox',5);
		$ridt_checkbox_optional_match = ReportInputDataType::add('checkbox_optional_match',6);

		/* report item data types */

		$rimt_total = ReportItemDataType::add('total');
		$rimt_mean_and_range = ReportItemDataType::add('mean_and_range');
		$rimt_number_and_percentage = ReportItemDataType::add('number_and_percentage');
		$rimt_number_and_percentage_pair = ReportItemDataType::add('number_and_percentage_pair');
		$rimt_list = ReportItemDataType::add('list');
		$rimt_string = ReportItemDataType::add('string');
		$rimt_date = ReportItemDataType::add('date');
		$rimt_nhsdate = ReportItemDataType::add('NHSDate');
		$rimt_conditional = ReportItemDataType::add('conditional');
		$rimt_list_from_element_relation = ReportItemDataType::add('list_from_element_relation');
		$rimt_element_relation = ReportItemDataType::add('element_relation');
		$rimt_number = ReportItemDataType::add('number');

		/* rule types */

		$rule_one_of = ReportValidationRuleType::add('One of');

		/* Patient diagnoses */

		$report = Report::add(array(
			'query_type_id' => $query_type_patients->id,
			'name' => 'Patient diagnoses',
			'description' => 'Patient diagnoses report',
			'icon' => 'treatment_operation',
			'display_order' => 2,
			'can_print' => 1,
			'can_download' => 0,
		));

		$dataset1 = $report->addDataset('dataset1');
			$disorders = $dataset1->addRelatedEntity('diagnoses');
				$principal = $disorders->addRelatedEntityType('principal');
				$secondary = $disorders->addRelatedEntityType('secondary');

				$episode = $disorders->addRelatedEntityTable(array(
					'entity_type_id' => $principal->id,
					'table_name' => 'episode',
					'table_related_field' => 'patient_id',
					'table_query_field' => 'disorder_id',
					'table_date_field' => 'last_modified_date',
				));

					$episode->addRelation(array(
						'local_field' => 'disorder_id',
						'related_table' => 'disorder',
						'select_field' => 'term',
						'select_field_as' => 'diagnosis',
					));

					$episode->addRelation(array(
						'local_field' => 'eye_id',
						'related_table' => 'eye',
						'select_field' => 'name',
						'select_field_as' => 'eye',
					));

				$secondary_diagnosis = $disorders->addRelatedEntityTable(array(
					'entity_type_id' => $secondary->id,
					'table_name' => 'secondary_diagnosis',
					'table_related_field' => 'patient_id',
					'table_query_field' => 'disorder_id',
					'table_date_field' => 'last_modified_date',
				));

					$secondary_diagnosis->addRelation(array(
						'local_field' => 'disorder_id',
						'related_table' => 'disorder',
						'select_field' => 'term',
						'select_field_as' => 'diagnosis',
					));

					$secondary_diagnosis->addRelation(array(
						'local_field' => 'eye_id',
						'related_table' => 'eye',
						'select_field' => 'name',
						'select_field_as' => 'eye',
					));

			$dataset1->addInput(array(
				'data_type_id' => $ridt_date->id,
				'name' => 'date_from',
				'description' => 'Start date',
				'default_value' => '-12 months',
				'display_order' => 1,
				'required' => 1,
			));

			$dataset1->addInput(array(
				'data_type_id' => $ridt_date->id,
				'name' => 'date_to',
				'description' => 'End date',
				'default_value' => 'now',
				'display_order' => 2,
				'required' => 1,
			));

			$dataset1->addInput(array(
				'data_type_id' => $ridt_diagnoses->id,
				'related_entity_id' => $disorders->id,
				'name' => 'diagnoses',
				'description' => 'Diagnoses',
				'display_order' => 3,
			));

			$patients = $dataset1->addItem(array(
				'data_type_id' => $rimt_list->id,
				'name' => 'Patients',
				'data_field' => 'patients',
				'subtitle' => 'Patient diagnoses',
				'display_order' => 1,
			));

				$patients->addListItem(array(
					'data_type_id' => $rimt_nhsdate->id,
					'name' => 'Date',
					'data_field' => 'date',
					'subtitle' => 'Date',
					'display_order' => 1,
				));

				$patients->addListItem(array(
					'data_type_id' => $rimt_string->id,
					'name' => 'Hospital no',
					'data_field' => 'hos_num',
					'subtitle' => 'Patient hospital number',
					'display_order' => 2,
					'link' => '/patient/episodes/{patient_id}',
				));

				$patients->addListItem(array(
					'data_type_id' => $rimt_string->id,
					'name' => 'First name',
					'data_field' => 'first_name',
					'subtitle' => 'Patient first name',
					'display_order' => 3,
				));

				$patients->addListItem(array(
					'data_type_id' => $rimt_string->id,
					'name' => 'Last name',
					'data_field' => 'last_name',
					'subtitle' => 'Patient last name',
					'display_order' => 4,
				));

				$patients->addListItem(array(
					'data_type_id' => $rimt_number->id,
					'name' => 'Patient ID',
					'data_field' => 'patient_id',
					'subtitle' => 'Patient ID',
					'display' => 0,
				));

				$diagnoses = $patients->addListItem(array(
					'data_type_id' => $rimt_list->id,
					'name' => 'Diagnoses',
					'data_field' => 'diagnoses',
					'subtitle' => 'Diagnoses',
					'display_order' => 5,
				));

					$diagnoses->addListItem(array(
						'data_type_id' => $rimt_string->id,
						'name' => 'Eye',
						'data_field' => 'eye',
						'subtitle' => 'Eye',
						'display_order' => 1,
					));

					$diagnoses->addListItem(array(
						'data_type_id' => $rimt_string->id,
						'name' => 'Diagnosis',
						'data_field' => 'diagnosis',
						'subtitle' => 'Diagnosis',
						'display_order' => 2,
					));

	}
}
