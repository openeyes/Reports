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
 * This is the model class for table "report_item_list_item".
 *
 * The followings are the available columns in table 'report_item_list_item':
 * @property integer $id
 * @property string $name
 *
 */
class ReportItemListItem extends BaseActiveRecordVersioned
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ReportItemListItem the static model class
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
		return 'report_item_list_item';
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
			'listItems' => array(self::HAS_MANY, 'ReportItemListItem', 'list_item_id'),
			'fields' => array(self::HAS_MANY, 'ReportItemListItemField', 'list_item_id'),
			'conditionals' => array(self::HAS_MANY, 'ReportItemListItemConditional', 'list_item_id', 'order' => 'display_order'),
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

	public function addListItem($params) {
		if (!$item = ReportItemListItem::model()->find('list_item_id=? and data_field=?',array($this->id,$params['data_field']))) {
			$item = new ReportItemListItem;
			$item->list_item_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$item->{$key} = $value;
		}

		if (!$item->save()) {
			throw new Exception("Unable to save list item: ".print_r($item->getErrors(),true));
		}

		return $item;
	}

	public function addField($params) {
		if (!$field = ReportItemListItemField::model()->find('list_item_id=? and name=?',array($this->id,$params['name']))) {
			$field = new ReportItemListItemField;
			$field->list_item_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$field->{$key} = $value;
		}

		if (!$field->save()) {
			throw new Exception("Unable to save list item field: ".print_r($field->getErrors(),true));
		}

		return $field;
	}

	public function addConditional($params) {
		if (!$conditional = ReportItemListItemConditional::model()->find('list_item_id=? and match_field=?',array($this->id,$params['match_field']))) {
			$conditional = new ReportItemListItemConditional;
			$conditional->list_item_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$conditional->{$key} = $value;
		}

		if (!$conditional->save()) {
			throw new Exception("Unable to save list item conditional: ".print_r($conditional->getErrors(),true));
		}

		return $conditional;
	}

	public function generateLink($data) {
		$link = $this->link;

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$link = str_replace('{'.$key.'}',$value,$link);
			}
		}

		return $link;
	}

	public function compute($dataItem, $inputs, $element_related_item=null) {
		switch ($this->dataType->name) {
			case 'NHSDate':
				return Helper::convertMySQL2NHS($dataItem[$this->data_field]);

			case 'string':
			case 'number':
				return $dataItem[$this->data_field];

			case 'link':
				return '<a href="'.$dataItem[$this->data_field].'">'.$dataItem[$this->data_field].'</a>';

			case 'list_from_element_relation':
				$model = $this->element->elementType->class_name;
				$listItems = array();

				//$element = Yii::app()->db_report->createCommand()->select("*")->from($model::model()->tableName())->where("id=:id",array(':id'=>$dataItem["el{$this->element_id}_id"]))->queryRow();

				if ($dataItem["el{$this->element_id}_id"]) {
					foreach ($model::model()->relations() as $name => $relation) {
						if ($name == $this->element_relation) {
							$_relation = $relation;
							break;
						}
					}

					if (!isset($_relation)) {
						throw new Exception("Relation not found: $this->element_relation");
					}

					switch ($relation[0]) {
						case $model::MANY_MANY:
							if (!preg_match('/^(.*?)\((.*?),(.*?)\)$/',$relation[2],$m)) {
								throw new Exception("Badly formed relation: $this->element_relation");
							}
							$related_table = $relation[1]::model()->tableName();
							$intermediary_table = trim($m[1]);
							$field1 = trim($m[2]);
							$field2 = trim($m[3]);
							$related_item_query = Yii::app()->db_report->createCommand()
								->select("$related_table.*")
								->from($related_table)
								->join($intermediary_table,"$intermediary_table.$field2 = $related_table.id")
								->where("$intermediary_table.$field1 = ".$dataItem["el{$this->element_id}_id"]);
								if (isset($relation['order'])) {
									$related_item_query->order($relation['order']);
								}
								$related_item_query = $related_item_query->queryAll();
							break;
						case $model::HAS_MANY:
							$related_table = $relation[1]::model()->tableName();
							$field = $relation[2];
							$related_item_query = Yii::app()->db_report->createCommand()
								->select("$related_table.*")
								->from($related_table)
								->where("$field = ".$dataItem["el{$this->element_id}_id"]);
							if (isset($relation['order'])) {
								$related_item_query->order($relation['order']);
							}
							$related_item_query = $related_item_query->queryAll();
							break;
						default:
							throw new Exception("Unsupported relation type: $this->element_relation");
					}

					if ($related_item_query) {
						foreach ($related_item_query as $element_related_item) {
							$listItem = array();

							foreach ($this->listItems as $listItemSpec) {
								$listItem[$listItemSpec->name] = $listItemSpec->compute($dataItem, $inputs, $element_related_item);
							}

							$listItems[] = $listItem;
						}
					}
				}

				return $listItems;

			case 'conditional':
				foreach ($this->conditionals as $condition) {
					if ($dataItem[$condition->match_field] == $inputs[$condition->input->name]) {
						return $condition->result;
					}
				}
				return null;

			case 'list':
				$result = array();

				foreach ($dataItem[$this->data_field] as $dataListItem) {
					$dataListItems = array();

					foreach ($this->listItems as $listItem) {
						$dataListItems[$listItem->data_field] = $listItem->compute($dataListItem, $inputs);
					}

					$result[] = $dataListItems;
				}

				return $result;

			case 'element_relation':
				if (!isset($element_related_item[$this->data_field])) {
					return 'MISSING VALUE';
				}

				return $element_related_item[$this->data_field];
		}

		throw new Exception("Unhandled item data type: {$this->dataType->name}");
	}

	public function delete()
	{
		foreach ($this->conditionals as $conditional) {
			if (!$conditional->delete()) {
				throw new Exception("Unable to delete list item conditional: ".print_r($conditional->getErrors(),true));
			}
		}

		foreach ($this->listItems as $listItem) {
			if (!$listItem->delete()) {
				throw new Exception("Unable to delete list item: ".print_r($listItem->getErrors(),true));
			}
		}

		return parent::delete();
	}
}
