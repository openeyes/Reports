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
 * This is the model class for table "report_dataset_element".
 *
 * The followings are the available columns in table 'report_dataset_element':
 * @property integer $id
 * @property string $name
 *
 */
class ReportDatasetElement extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ReportDatasetElement the static model class
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
		return 'report_dataset_element';
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
			'dataset' => array(self::BELONGS_TO, 'ReportDataset', 'dataset_id'),
			'elementType' => array(self::BELONGS_TO, 'ElementType', 'element_type_id'),
			'fields' => array(self::HAS_MANY, 'ReportDatasetElementField', 'element_id'),
			'joins' => array(self::HAS_MANY, 'ReportDatasetElementJoin', 'element_id'),
			'relatedEntities' => array(self::HAS_MANY, 'ReportDatasetRelatedEntity', 'element_id'),
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

	public function addField($field_name) {
		if (!$field = ReportDatasetElementField::model()->find('element_id=? and field=?',array($this->id,$field_name))) {
			$field = new ReportDatasetElementField;
			$field->element_id = $this->id;
			$field->field = $field_name;

			if (!$field->save()) {
				throw new Exception("Unable to save element field: ".print_r($field->getErrors(),true));
			}
		}
	}

	public function addJoin($params) {
		if (!$join = ReportDatasetElementJoin::model()->find('element_id=? and join_table=?',array($this->id,$params['join_table']))) {
			$join = new ReportDatasetElementJoin;
			$join->element_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$join->{$key} = $value;
		}

		if (!$join->save()) {
			throw new Exception("Unable to save element join: ".print_r($join->getErrors(),true));
		}

		return $join;
	}

	public function addRelatedEntity($name) {
		if (!$entity = ReportDatasetRelatedEntity::model()->find('element_id=? and name=?',array($this->id,$name))) {
			$entity = new ReportDatasetRelatedEntity;
			$entity->element_id = $this->id;
			$entity->name = $name;

			if (!$entity->save()) {
				throw new Exception("Unable to save related entity: ".print_r($entity->getErrors(),true));
			}
		}

		return $entity;
	}

	public function delete()
	{
		foreach ($this->fields as $field) {
			if (!$field->delete()) {
				throw new Exception("Unable to delete field: ".print_r($field->getErrors(),true));
			}
		}

		foreach ($this->joins as $join) {
			if (!$join->delete()) {
				throw new Exception("Unable to delete join: ".print_r($join->getErrors(),true));
			}
		}

		foreach ($this->relatedEntities as $relatedEntity) {
			if (!$relatedEntity->delete()) {
				throw new Exception("Unable to delete related entity: ".print_r($relatedEntity->getErrors(),true));
			}
		}

		return parent::delete();
	}
}
