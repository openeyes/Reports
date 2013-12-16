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
 * This is the model class for table "report_item".
 *
 * The followings are the available columns in table 'report_item':
 * @property integer $id
 * @property string $name
 *
 */
class ReportItem extends BaseActiveRecordVersioned
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ReportItem the static model class
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
		return 'report_item';
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
			'dataType' => array(self::BELONGS_TO, 'ReportItemDataType', 'data_type_id'),
			'listItems' => array(self::HAS_MANY, 'ReportItemListItem', 'item_id', 'order'=>'display_order'),
			'displayListItems' => array(self::HAS_MANY, 'ReportItemListItem', 'item_id', 'order'=>'display_order', 'condition'=>'display=1'),
			'dataset' => array(self::BELONGS_TO, 'ReportDataset', 'dataset_id'),
			'pairFields' => array(self::HAS_MANY, 'ReportItemPairField', 'item_id'),
			'element' => array(self::BELONGS_TO, 'ReportDatasetElement', 'element_id'),
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

	public function addPairField($params) {
		if (!$pair_field = ReportItemPairField::model()->find('item_id=? and name=?',array($this->id,$params['name']))) {
			$pair_field = new ReportItemPairField;
			$pair_field->item_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$pair_field->{$key} = $value;
		}

		if (!$pair_field->save()) {
			throw new Exception("Unable to save item pair field: ".print_r($pair_field->getErrors(),true));
		}
	}

	public function addListItem($params) {
		if (!$item = ReportItemListItem::model()->find('item_id=? and data_field=?',array($this->id,$params['data_field']))) {
			$item = new ReportItemListItem;
			$item->item_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$item->{$key} = $value;
		}

		if (!$item->save()) {
			throw new Exception("Unable to save list item: ".print_r($item->getErrors(),true));
		}

		return $item;
	}

	public function compute($dataset, $inputs) {
		switch ($this->dataType->name) {

			case 'total':
				return count($dataset);

			case 'mean_and_range':
				$lowest = 999999999;
				$highest = 0;
				$values = array();

				foreach ($dataset as $dataItem) {
					if ($this->data_input_field) {
						if ($this->data_input_field == 'age') {
							$dataItem['age'] = Helper::getAge($dataItem['dob']);
						}

						if ($dataItem[$this->data_input_field] < $lowest) {
							$lowest = $dataItem[$this->data_input_field];
						}
						if ($dataItem[$this->data_input_field] > $highest) {
							$highest = $dataItem[$this->data_input_field];
						}
						$values[] = $dataItem[$this->data_input_field];
					}
				}

				if (empty($values)) {
					return array(
						'from' => 0,
						'to' => 0,
						'mean' => 0,
					);
				}

				return array(
					'from' => $lowest,
					'to' => $highest,
					'mean' => number_format(array_sum($values)/count($values),2),
				);

			case 'number_and_percentage':
				if ($this->data_input_field) {
					return $this->numberWithPercentageFromField($dataset, $inputs, $this->data_input_field, $inputs[$this->data_input_field]);
				}

				return $this->numberWithPercentageFromElementRelation($dataset);

			case 'number_and_percentage_pair':
				$result = array();

				foreach ($this->pairFields as $field) {
					$result[$field->name] = $this->numberWithPercentageFromField($dataset, $inputs, $field->field, $field->value);
				}

				return $result;

			case 'list':
				$result = array();

				foreach ($dataset as $dataItem) {
					$dataListItem = array();

					foreach ($this->listItems as $listItem) {
						$dataListItem[$listItem->data_field] = $listItem->compute($dataItem, $inputs);
					}

					$result[] = $dataListItem;
				}

				return $result;
		}
	}

	public function numberWithPercentageFromField($dataset, $inputs, $field, $value) {
		$result = array('number'=>0);

		foreach ($dataset as $dataItem) {
			if ($dataItem[$field] == $value) {
				$result['number']++;
			}
		}

		return $this->calcPercentage($result, count($dataset));
	}

	public function numberWithPercentageFromElementRelation($dataset) {
		$result = array('number'=>0);

		$model = $this->element->elementType->class_name;

		foreach ($dataset as $dataItem) {
			if ($element = $model::model()->findByPk($dataItem["el{$this->element->id}_id"])) {

				$matches = false;
				foreach ($element->{$this->element_relation} as $related_model) {
					if ($this->element_relation_field && $this->element_relation_value) {
						if ($related_model->{$this->element_relation_field} == $this->element_relation_value) {
							$matches = true;
						}
					} else {
						$matches = true;
					}
				}
				if ($matches) {
					$result['number']++;
				}
			}
		}

		return $this->calcPercentage($result, count($dataset));
	}

	public function calcPercentage($result, $total) {
		if ($result['number'] >0) {
			$result['percentage'] = number_format($result['number'] / ($total/100),2);
		} else {
			$result['percentage'] = 0;
		}

		return $result;
	}

	public function delete()
	{
		foreach ($this->listItems as $listItem) {
			if (!$listItem->delete()) {
				throw new Exception("Unable to delete list item: ".print_r($listItem->getErrors(),true));
			}
		}

		foreach ($this->pairFields as $pairField) {
			if (!$pairField->delete()) {
				throw new Exception("Unable to delete pair field: ".print_r($pairField->getErrors(),true));
			}
		}

		return parent::delete();
	}
}
