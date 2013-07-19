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

class Report_OperationsCommand extends CConsoleCommand {
	public function run($args) {
		Yii::import('application.modules.Reports.models.*');

		$opnote = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$element_proclist = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementProcedureList'));
		$element_surgeon = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementSurgeon'));
		$element_cataract = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementCataract'));

		if (!$query_type_events = ReportQueryType::model()->find('name=?',array('Events'))) {
			$query_type_events = new ReportQueryType;
			$query_type_events->name = 'Events';
			$query_type_events->display_order = 1;
			if (!$query_type_events->save()) {
				print_r($query_type_events->getErrors(),true);
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

		/* Operations */

		if ($report = Report::model()->find('query_type_id=? and subspecialty_id is null and name=?',array($query_type_events->id,'Operations'))) {
			if (!$report->delete()) {
				throw new Exception("Failed to delete report: ".print_r($report->getErrors(),true));
			}
		}

		$report = Report::add(array(
			'query_type_id' => $query_type_events->id,
			'subspecialty_id' => null,
			'name' => 'Operations',
			'description' => 'Operations',
			'icon' => 'treatment_operation',
			'display_order' => 3,
			'can_print' => 1,
			'can_download' => 1,
		));

		$dataset1 = $report->addDataset('dataset1');
			$el_proclist = $dataset1->addElement($element_proclist->id);
				$el_proclist->addField('eye_id');
				$el_proclist->addJoin(array(
					'join_table' => 'eye',
					'join_clause' => 'eye_id = eye.id',
					'join_select' => 'eye.name as eye',
				));
			$el_surgeon = $dataset1->addElement($element_surgeon->id);
				$el_surgeon->addField('surgeon_id');
				$el_surgeon->addField('assistant_id');
				$el_surgeon->addField('supervising_surgeon_id');
			$el_cataract = $dataset1->addElement($element_cataract->id, 1);

		$surgeon_id = $dataset1->addInput(array(
			'data_type_id' => $ridt_dropdown_from_table->id,
			'data_type_param1' => 'User',
			'data_type_param2' => 'getListSurgeons',
			'name' => 'surgeon_id',
			'description' => 'Surgeon',
			'display_order' => 1,
			'required' => 1,
			'include' => 0,
		));

		$dataset1->addInput(array(
			'data_type_id' => $ridt_checkbox_optional_match->id,
			'name' => 'match_surgeon',
			'default_value' => 1,
			'description' => 'Match surgeon',
			'display_order' => 2,
			'data_type_param1' => 'surgeon_id',
			'data_type_param2' => 'surgeon_id',
			'or_id' => 1,
		));

		$dataset1->addInput(array(
			'data_type_id' => $ridt_checkbox_optional_match->id,
			'name' => 'match_assistant_surgeon',
			'default_value' => 1,
			'description' => 'Match assistant surgeon',
			'display_order' => 3,
			'data_type_param1' => 'surgeon_id',
			'data_type_param2' => 'assistant_id',
			'or_id' => 1,
		));

		$dataset1->addInput(array(
			'data_type_id' => $ridt_checkbox_optional_match->id,
			'name' => 'match_supervising_surgeon',
			'default_value' => 1,
			'description' => 'Match supervising surgeon',
			'display_order' => 4,
			'data_type_param1' => 'surgeon_id',
			'data_type_param2' => 'supervising_surgeon_id',
			'or_id' => 1,
		));

		$dataset1->addInput(array(
			'data_type_id' => $ridt_date->id,
			'name' => 'date_from',
			'description' => 'Date from',
			'default_value' => '-12 months',
			'display_order' => 5,
			'required' => 1,
		));

		$dataset1->addInput(array(
			'data_type_id' => $ridt_date->id,
			'name' => 'date_to',
			'description' => 'Date to',
			'default_value' => 'now',
			'display_order' => 6,
			'required' => 1,
		));

		$operations = $dataset1->addItem(array(
			'data_type_id' => $rimt_list->id,
			'name' => 'Operations',
			'data_field' => 'operations',
			'subtitle' => 'Operations',
			'display_order' => 1,
		));

			$operations->addListItem(array(
				'data_type_id' => $rimt_number->id,
				'name' => 'Patient ID',
				'data_field' => 'patient_id',
				'subtitle' => 'Patient ID',
				'display' => 0,
			));

			$operations->addListItem(array(
				'data_type_id' => $rimt_nhsdate->id,
				'name' => 'Date',
				'data_field' => 'datetime',
				'subtitle' => 'Date',
				'display_order' => 1,
			));

			$operations->addListItem(array(
				'data_type_id' => $rimt_string->id,
				'name' => 'Hospital no',
				'data_field' => 'hos_num',
				'subtitle' => 'Patient hospital number',
				'display_order' => 2,
				'link' => '/patient/episodes/{patient_id}',
			));
	
			$operations->addListItem(array(
				'data_type_id' => $rimt_nhsdate->id,
				'name' => 'Date of birth',
				'data_field' => 'dob',
				'subtitle' => 'Patient date of birth',
				'display_order' => 2,
			));

			$operations->addListItem(array(
				'data_type_id' => $rimt_string->id,
				'name' => 'First name',
				'data_field' => 'first_name',
				'subtitle' => 'Patient first name',
				'display_order' => 3,
			));
	
			$operations->addListItem(array(
				'data_type_id' => $rimt_string->id,
				'name' => 'Last name',
				'data_field' => 'last_name',
				'subtitle' => 'Patient last name',
				'display_order' => 4,
			));
	
			$operations->addListItem(array(
				'data_type_id' => $rimt_string->id,
				'name' => 'Eye',
				'data_field' => 'eye',
				'subtitle' => 'Eye',
				'display_order' => 4,
			));

			$procedures = $operations->addListItem(array(
				'data_type_id' => $rimt_list_from_element_relation->id,
				'name' => 'Procedures',
				'data_field' => 'procedures',
				'subtitle' => 'Procedures',
				'display_order' => 5,
				'element_id' => $el_proclist->id,
				'element_relation' => 'procedures',
			));
	
				$procedures->addListItem(array(
					'data_type_id' => $rimt_element_relation->id,
					'name' => 'procedure',
					'data_field' => 'term',
				));

				$procedures->addListItem(array(
					'data_type_id' => $rimt_element_relation->id,
					'name' => 'procedure',
					'data_field' => 'term',
				));

			$complications = $operations->addListItem(array(
				'data_type_id' => $rimt_list_from_element_relation->id,
				'name' => 'Complications',
				'data_field' => 'complications',
				'subtitle' => 'Complications',
				'display_order' => 6,
				'element_id' => $el_cataract->id,
				'element_relation' => 'complicationItems',
			));

				$complications->addListItem(array(
					'data_type_id' => $rimt_element_relation->id,
					'name' => 'name',
					'data_field' => 'name',
				));

			$role = $operations->addListItem(array(
				'data_type_id' => $rimt_conditional->id,
				'name' => 'Role',
				'data_field' => 'role',
				'subtitle' => 'Role',
				'display_order' => 7,
			));

				$role->addConditional(array(
					'input_id' => $surgeon_id->id,
					'match_field' => 'surgeon_id',
					'result' => 'Surgeon',
					'display_order' => 1,
				));

				$role->addConditional(array(
					'input_id' => $surgeon_id->id,
					'match_field' => 'assistant_id',
					'result' => 'Assistant surgeon',
					'display_order' => 2,
				));

				$role->addConditional(array(
					'input_id' => $surgeon_id->id,
					'match_field' => 'supervising_surgeon_id',
					'result' => 'Supervising surgeon',
					'display_order' => 3,
				));

		$report->addRule(array(
			'rule_type_id' => $rule_one_of->id,
			'rule' => 'match_surgeon,match_assistant_surgeon,match_supervising_surgeon',
			'message' => 'At least one of the surgeon checkboxes must be selected',
		));
	}
}
