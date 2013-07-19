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

class Report_LettersCommand extends CConsoleCommand {
	public function run($args) {
		Yii::import('application.modules.Reports.models.*');

		if (!$query_type_letters = ReportQueryType::model()->find('name=?',array('Letters'))) {
			$query_type_letters = new ReportQueryType;
			$query_type_letters->name = 'Letters';
			$query_type_letters->display_order = 1;
			if (!$query_type_letters->save()) {
				print_r($query_type_letters->getErrors(),true);
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
		$ridt_multi_string = ReportInputDataType::add('multi_string',7);
		$ridt_radio_buttons = ReportInputDataType::add('radio_buttons',8);

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
		$rimt_link = ReportItemDataType::add('link');

		/* rule types */

		$rule_one_of = ReportValidationRuleType::add('One of');

		/* Letters */

		if ($report = Report::model()->find('query_type_id=? and subspecialty_id is null and name=?',array($query_type_letters->id,'Letters'))) {
			if (!$report->delete()) {
				throw new Exception("Unable to delete report: ".print_r($report->getErrors(),true));
			}
		}

		$report = Report::add(array(
			'query_type_id' => $query_type_letters->id,
			'subspecialty_id' => null,
			'name' => 'Letters',
			'icon' => 'treatment_operation',
			'display_order' => 4,
			'can_print' => 1,
			'can_download' => 1,
		));

		$dataset1 = $report->addDataset('dataset1');

		$dataset1->addInput(array(
			'data_type_id' => $ridt_multi_string->id,
			'name' => 'phrases',
			'description' => 'Phrases',
			'display_order' => 1,
			'required' => 1,
		));

		$search_method = $dataset1->addInput(array(
			'data_type_id' => $ridt_radio_buttons->id,
			'name' => 'search_method',
			'description' => 'Search method',
			'display_order' => 2,
			'required' => 1,
			'default_value' => 1,
		));

			$search_method->addOption('Must contain all phrases');
			$search_method->addOption('Must contain any of the phrases');

		$dataset1->addInput(array(
			'data_type_id' => $ridt_checkbox_optional_match->id,
			'name' => 'match_correspondence',
			'default_value' => 1,
			'description' => 'Match correspondence',
			'display_order' => 3,
		));

		$dataset1->addInput(array(
			'data_type_id' => $ridt_checkbox_optional_match->id,
			'name' => 'match_legacyletters',
			'default_value' => 1,
			'description' => 'Match legacy letters',
			'display_order' => 4,
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

		$dataset1->addInput(array(
			'data_type_id' => $ridt_dropdown_from_table->id,
			'data_type_param1' => 'User',
			'data_type_param2' => 'getListSurgeons',
			'name' => 'author_id',
			'description' => 'Author',
			'display_order' => 7,
			'required' => 0,
			'include' => 0,
		));

		$operations = $dataset1->addItem(array(
			'data_type_id' => $rimt_list->id,
			'name' => 'Letters',
			'data_field' => 'letters',
			'subtitle' => 'Letters',
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
				'name' => 'Type',
				'data_field' => 'type',
				'subtitle' => 'Type',
				'display_order' => 5,
			));

			$operations->addListItem(array(
				'data_type_id' => $rimt_link->id,
				'name' => 'Link',
				'data_field' => 'link',
				'subtitle' => 'Link',
				'display_order' => 6,
			));

		$report->addRule(array(
			'rule_type_id' => $rule_one_of->id,
			'rule' => 'match_correspondence,match_legacyletters',
			'message' => 'At least one of the checkboxes must be selected',
		));
	}
}
