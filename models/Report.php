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

/**
 * This is the model class for table "report".
 *
 * The followings are the available columns in table 'report':
 * @property integer $id
 * @property string $name
 *
 */
class Report extends BaseActiveRecordVersioned
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Report the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'queryType' => array(self::BELONGS_TO, 'ReportQueryType', 'query_type_id'),
			'subspecialty' => array(self::BELONGS_TO, 'Subspecialty', 'subspecialty_id'),
			'graphs' => array(self::HAS_MANY, 'ReportGraph', 'report_id', 'order' => 'display_order'),
			'validationRules' => array(self::HAS_MANY, 'ReportValidationRule', 'report_id'),
			'datasets' => array(self::HAS_MANY, 'ReportDataset', 'report_id', 'order' => 'display_order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public static function add($params) {
		$whereParams = array(
			$params['query_type_id'],
			$params['name'],
		);

		if (!@$params['subspecialty_id']) {
			$subspecialty = "subspecialty_id is null";
		} else {
			$subspecialty = "subspecialty_id = ?";
			$whereParams[] = $params['subspecialty_id'];
		}

		if (!$report = Report::model()->find("query_type_id=? and name=? and $subspecialty",$whereParams)) {
			$report = new Report;
		}

		foreach ($params as $key => $value) {
			$report->{$key} = $value;
		}

		if (!$report->save()) {
			throw new Exception("Unable to save report: ".print_r($report->getErrors(),true));
		}

		return $report;
	}

	public function addDataset($name) {
		if (!$dataset = ReportDataset::model()->find('report_id=? and name=?',array($this->id,$name))) {
			$dataset = new ReportDataset;
			$dataset->report_id = $this->id;
			$dataset->name = $name;
			$dataset->display_order = count(ReportDataset::model()->findAll('report_id=?',array($this->id))) + 1;

			if (!$dataset->save()) {
				throw new Exception("Unable to save report dataset: ".print_r($dataset->getErrors(),true));
			}
		}

		return $dataset;
	}

	public function addGraph($name, $display_order=1) {
		if (!$graph = ReportGraph::model()->find('report_id=? and name=?',array($this->id,$name))) {
			$graph = new ReportGraph;
			$graph->report_id = $this->id;
			$graph->name = $name;
		}

		$graph->display_order = $display_order;

		if (!$graph->save()) {
			throw new Exception("Unable to save graph: ".print_r($graph->getErrors(),true));
		}

		return $graph;
	}

	public function addRule($params) {
		if (!$rule = ReportValidationRule::model()->find('report_id=? and rule=?',array($this->id,$params['rule']))) {
			$rule = new ReportValidationRule;
			$rule->report_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$rule->{$key} = $value;
		}

		if (!$rule->save()) {
			throw new Exception("Unable to save validation rule: ".print_r($rule->getErrors(),true));
		}

		return $rule;
	}

	public static function subspecialties() {
		$subspecialties = array();

		if (Report::model()->find('subspecialty_id is null')) {
			$subspecialties[null] = 'General';
		}

		$firm = Firm::model()->findByPk(Yii::app()->getController()->selectedFirmId);

		foreach (Yii::app()->db->createCommand()
			->select("distinct(subspecialty.id), subspecialty.name")
			->from("subspecialty")
			->join("report","report.subspecialty_id = subspecialty.id")
			->where("subspecialty.deleted = :notdeleted and report.deleted = :notdeleted",array(
				":notdeleted" => 0,
			))
			->order("subspecialty.name asc")
			->queryAll() as $subspecialty) {

			if ($subspecialty['id'] == $firm->serviceSubspecialtyAssignment->subspecialty_id) {
				$subspecialties[$subspecialty['id']] = $subspecialty['name'];
			}
		}

		return $subspecialties;
	}

	public function execute($inputs) {
		$results = array();

		foreach ($this->datasets as $dataset) {
			$data = $dataset->compute($inputs);

			foreach ($dataset->items as $item) {
				$results[$item->data_field] = $item->compute($data, $inputs);
			}
		}

		return $results;
	}

	public function validateInput($data) {
		$errors = array();

		foreach ($this->datasets as $dataset) {
			foreach ($dataset->inputs as $input) {
				if ($input->required && !@$data[$input->name]) {
					$errors[] = "$input->description is required";
				}
			}
		}

		foreach ($this->validationRules as $rule) {
			if (!$rule->pass($data)) {
				$errors[] = $rule->message;
			}
		}

		return empty($errors) ? array() : array($this->name=>$errors);
	}

	public function getAllBySpeciality($subspecialty_id) {
		$criteria = new CDbCriteria;
		($subspecialty_id == null) ? $criteria->addCondition('subspecialty_id is null') : $criteria->addCondition('subspecialty_id = '.$subspecialty_id);
		$criteria->order = 'display_order asc';
		return Report::model()->findAll($criteria);
	}

	public function delete()
	{
		foreach ($this->graphs as $graph) {
			if (!$graph->delete()) {
				throw new Exception("Unable to delete graph: ".print_r($graph->getErrors(),true));
			}
		}

		foreach ($this->validationRules as $rule) {
			if (!$rule->delete()) {
				throw new Exception("Unable to delete rule: ".print_r($rule->getErrors(),true));
			}
		}

		foreach ($this->datasets as $dataset) {
			if (!$dataset->delete()) {
				throw new Exception("Unable to delete dataset: ".print_r($dataset->getErrors(),true));
			}
		}

		return parent::delete();
	}
}
