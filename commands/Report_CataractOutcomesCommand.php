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

class Report_CataractOutcomesCommand extends CConsoleCommand {
	public function run($args) {
		Yii::import('application.modules.Reports.models.*');

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

		/* Cataract Outcomes */

		Yii::import('application.modules.OphTrOperationnote.models.*');

		$opnote = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$element_proclist = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementProcedureList'));
		$element_surgeon = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementSurgeon'));
		$element_cataract = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementCataract'));

		$report = Report::add(array(
			'query_type_id' => $query_type_events->id,
			'subspecialty_id' => 4,
			'name' => 'Cataract outcomes',
			'description' => 'Cataract outcomes report',
			'icon' => 'treatment_operation',
			'display_order' => 1,
			'can_print' => 1,
			'can_download' => 1,
		));

			$dataset1 = $report->addDataset('dataset1');
				$el_proclist = $dataset1->addElement($element_proclist->id);
					$el_proclist->addField('eye_id');
				$el_surgeon = $dataset1->addElement($element_surgeon->id);
					$el_surgeon->addField('surgeon_id');
					$el_surgeon->addField('assistant_id');
					$el_surgeon->addField('supervising_surgeon_id');
				$el_cataract = $dataset1->addElement($element_cataract->id);

			$dataset1->addInput(array(
				'data_type_id' => $ridt_dropdown_from_table->id,
				'data_type_param1' => 'Firm',
				'data_type_param2' => 'getCataractList',
				'name' => 'firm_id',
				'description' => 'Firm',
				'display_order' => 1,
			));

			$dataset1->addInput(array(
				'data_type_id' => $ridt_dropdown_from_table->id,
				'data_type_param1' => 'User',
				'data_type_param2' => 'getListSurgeons',
				'name' => 'surgeon_id',
				'description' => 'Surgeon',
				'display_order' => 2,
				'or_id' => 1,
			));

			$dataset1->addInput(array(
				'data_type_id' => $ridt_dropdown_from_table->id,
				'data_type_param1' => 'User',
				'data_type_param2' => 'getListSurgeons',
				'name' => 'assistant_id',
				'description' => 'Assistant surgeon',
				'display_order' => 3,
				'or_id' => 1,
			));

			$dataset1->addInput(array(
				'data_type_id' => $ridt_dropdown_from_table->id,
				'data_type_param1' => 'User',
				'data_type_param2' => 'getListSurgeons',
				'name' => 'supervising_surgeon_id',
				'description' => 'Supervising surgeon',
				'display_order' => 4,
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

			$dataset1->addItem(array(
				'data_type_id' => $rimt_total->id,
				'name' => 'Cataracts',
				'data_field' => 'cataracts',
				'subtitle' => 'Number of cataracts performed',
				'display_order' => 1,
			));

			$dataset1->addItem(array(
				'data_type_id' => $rimt_mean_and_range->id,
				'name' => 'Age',
				'data_field' => 'age',
				'data_input_field' => 'age',
				'subtitle' => 'Age of patients',
				'display_order' => 2,
			));

			$item = $dataset1->addItem(array(
				'data_type_id' => $rimt_number_and_percentage_pair->id,
				'name' => 'Eyes',
				'data_field' => 'eyes',
				'subtitle' => 'Eyes',
				'display_order' => 3,
			));

				$item->addPairField(array(
					'name' => 'left',
					'field' => 'eye_id',
					'value' => '1',
				));

				$item->addPairField(array(
					'name' => 'right',
					'field' => 'eye_id',
					'value' => '2',
				));

			$dataset1->addItem(array(
				'data_type_id' => $rimt_mean_and_range->id,
				'name' => 'Final visual acuity',
				'data_field' => 'final_visual_acuity',
				'subtitle' => 'Final visual acuity',
				'display_order' => 4,
			));

			$pc_rupture = CataractComplications::model()->find('name=?',array('PC rupture'));

			$pc_ruptures = $dataset1->addItem(array(
				'data_type_id' => $rimt_number_and_percentage->id,
				'name' => 'PC ruptures',
				'data_field' => 'pc_ruptures',
				'subtitle' => 'PC ruptures',
				'display_order' => 5,
				'element_id' => $el_cataract->id,
				'element_relation' => 'complications',
				'element_relation_field' => 'complication_id',
				'element_relation_value' => $pc_rupture->id,
			));

			$complications = $dataset1->addItem(array(
				'data_type_id' => $rimt_number_and_percentage->id,
				'name' => 'Complications',
				'data_field' => 'complications',
				'subtitle' => 'All complications',
				'display_order' => 7,
				'element_id' => $el_cataract->id,
				'element_relation' => 'complications',
			));

			$dataset2 = $report->addDataset('dataset2');
				$el_cataract = $dataset2->addElement($element_cataract->id);

			$avg_pc_ruptures = $dataset2->addItem(array(
				'data_type_id' => $rimt_number_and_percentage->id,
				'name' => 'Average',
				'data_field' => 'pc_rupture_average',
				'subtitle' => 'Average',
				'display_order' => 6,
				'element_id' => $el_cataract->id,
				'element_relation' => 'complications',
				'element_relation_field' => 'complication_id',
				'element_relation_value' => $pc_rupture->id,
				'display' => 0,
			));

			$avg_complications = $dataset2->addItem(array(
				'data_type_id' => $rimt_number_and_percentage->id,
				'name' => 'Average',
				'data_field' => 'complication_average',
				'subtitle' => 'Average',
				'display_order' => 8,
				'element_id' => $el_cataract->id,
				'element_relation' => 'complications',
				'display' => 0,
			));

		$graph = $report->addGraph('Cataract complication rate',1);

			$graph->addItem(array(
				'report_item_id' => $pc_ruptures->id,
				'name' => 'PC rupture rate',
				'subtitle' => 'percentage',
				'range' => 10,
				'display_order' => 1,
				'show_scale' => 0,
			));

			$graph->addItem(array(
				'report_item_id' => $avg_pc_ruptures->id,
				'name' => 'Average rate',
				'subtitle' => 'institution average',
				'range' => 10,
				'display_order' => 2,
			));

			$graph->addItem(array(
				'report_item_id' => $complications->id,
				'name' => 'Complication rate',
				'subtitle' => 'percentage',
				'range' => 10,
				'display_order' => 3,
				'show_scale' => 0,
			));
			
			$graph->addItem(array(
				'report_item_id' => $avg_complications->id,
				'name' => 'Average rate',
				'subtitle' => 'institution average',
				'range' => 10,
				'display_order' => 4,
			));
	}
}
