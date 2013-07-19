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
 * This is the model class for table "report_input".
 *
 * The followings are the available columns in table 'report_input':
 * @property integer $id
 * @property string $name
 *
 */
class ReportInput extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ReportInput the static model class
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
		return 'report_input';
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
			'dataType' => array(self::BELONGS_TO, 'ReportInputDataType', 'data_type_id'),
			'dataset' => array(self::BELONGS_TO, 'ReportDataset', 'dataset_id'),
			'relatedEntity' => array(self::BELONGS_TO, 'ReportDatasetRelatedEntity', 'related_entity_id'),
			'options' => array(self::HAS_MANY, 'ReportInputOption', 'input_id', 'order' => 'display_order'),
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

	public function getDefaultValue() {
		if ($this->dataType->name == 'date') {
			if ($this->default_value == 'now') {
				return date('j M Y');
			} else if ($this->default_value) {
				return date('j M Y',strtotime($this->default_value));
			}
			return '';
		}

		return $this->default_value;
	}

	public function getPostedValue() {
		switch ($this->dataType->name) {
			case 'number':
				return @$_REQUEST[$this->name];

			case 'dropdown_from_table':
				$model = $this->data_type_param1;
				$model = $model::model()->findByPk(@$_REQUEST[$this->name]);
				return $model ? $model->reportDisplay : '-';

			case 'date':
				return @$_REQUEST[$this->name];

			case 'diagnoses':
				$disorders = '';

				foreach (Disorder::model()->findAll('id in ('.implode(',',@$_REQUEST['selected_diagnoses']).')') as $i => $disorder) {
					if ($i) $disorders.=', ';
					$disorders .= $disorder->term;
				}

				return '"'.$disorders.'"';

			case 'checkbox':
			case 'checkbox_optional_match':
				return @$_REQUEST[$this->name] ? 'Yes' : 'No';

			case 'multi_string':
				return implode(', ',@$_REQUEST[$this->name]);

			case 'radio_buttons':
				return ReportInputOption::model()->findByPk(@$_REQUEST[$this->name])->name;
		}

		throw new Exception("Unhandled data input type: {$this->dataType->name}");
	}

	public function addOption($name) {
		if (!$option = ReportInputOption::model()->find('input_id=? and name=?',array($this->id,$name))) {
			$option = new ReportInputOption;
			$option->input_id = $this->id;
			$option->name = $name;

			if (!$option->save()) {
				throw new Exception("Unable to save report input option: ".print_r($option->getErrors(),true));
			}
		}

		return $option;
	}

	public function delete()
	{
		foreach ($this->options as $option) {
			if (!$option->delete()) {
				throw new Exception("Unable to delete input option: ".print_r($option->getErrors(),true));
			}
		}

		return parent::delete();
	}
}
