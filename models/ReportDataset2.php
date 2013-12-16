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
 * This is the model class for table "report_dataset".
 *
 * The followings are the available columns in table 'report_dataset':
 * @property integer $id
 * @property string $name
 *
 */
class ReportDataset extends BaseActiveRecordVersioned
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ReportDataset the static model class
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
		return 'report_dataset';
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
			'report' => array(self::BELONGS_TO, 'Report', 'report_id'),
			'elements' => array(self::HAS_MANY, 'ReportDatasetElement', 'dataset_id'),
			'inputs' => array(self::HAS_MANY, 'ReportInput', 'dataset_id'),
			'items' => array(self::HAS_MANY, 'ReportItem', 'dataset_id', 'order' => 'display_order'),
			'displayItems' => array(self::HAS_MANY, 'ReportItem', 'dataset_id', 'condition' => 'display = 1', 'order' => 'display_order'),
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

	public function addElement($element_type_id, $optional=0) {
		if (!$element = ReportDatasetElement::model()->find('dataset_id=? and element_type_id=?',array($this->id,$element_type_id))) {
			$element = new ReportDatasetElement;
			$element->dataset_id = $this->id;
			$element->element_type_id = $element_type_id;
		}

		$element->optional = $optional;

		if (!$element->save()) {
			throw new Exception("Unable to save dataset element: ".print_r($element->getErrors(),true));
		}

		return $element;
	}

	public function addInput($params) {
		if (!$input = ReportInput::model()->find('dataset_id=? and name=?',array($this->id,$params['name']))) {
			$input = new ReportInput;
			$input->dataset_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$input->{$key} = $value;
		}

		if (!$input->save()) {
			throw new Exception("Unable to save report input: ".print_r($element->getErrors(),true));
		}

		return $input;
	}

	public function addItem($params) {
		if (!$item = ReportItem::model()->find('dataset_id=? and data_field=?',array($this->id,$params['data_field']))) {
			$item = new ReportItem;
			$item->dataset_id = $this->id;
		}

		foreach ($params as $key => $value) {
			$item->{$key} = $value;
		}

		if (!$item->save()) {
			throw new Exception("Unable to save report item: ".print_r($element->getErrors(),true));
		}

		return $item;
	}

	public function addRelatedEntity($name) {
		if (!$entity = ReportDatasetRelatedEntity::model()->find('dataset_id=? and name=?',array($this->id,$name))) {
			$entity = new ReportDatasetRelatedEntity;
			$entity->dataset_id = $this->id;
			$entity->name = $name;

			if (!$entity->save()) {
				throw new Exception("Unable to save related entity: ".print_r($entity->getErrors(),true));
			}
		}

		return $entity;
	}

	public function compute($inputs) {
		$method = "compute_{$this->report->queryType->name}";

		if (method_exists($this,$method)) {
			return $this->{$method}($inputs);
		}

		throw new Exception("Dataset compute method for query type '{$this->report->queryType->name}' has not been implemented.");
	}

	public function compute_Letters($inputs) {
		$params = array();

		
	}

	public function compute_Events($inputs) {
		$params = array();
		$whereOr = array();

		foreach ($this->inputs as $input) {
			if ($input->include) {
				if ($input->dataType->name == 'checkbox_optional_match') {
					if ($inputs[$input->name]) {
						if ($input->or_id) {
							$whereOr[$input->or_id][$input->data_type_param2] = $inputs[$input->data_type_param1];
						} else {
							$params['where'][$input->data_type_param2] = $inputs[$input->data_type_param1];
						}
					}
				} else {
					if ($input->or_id) {
						$whereOr[$input->or_id][$input->name] = $inputs[$input->name];
					} else {
						$params['where'][$input->name] = $inputs[$input->name];
					}
				}
			}
		}

		foreach ($whereOr as $or_id => $fields) {
			$whereOrItem = array();
			foreach ($fields as $field => $value) {
				$whereOrItem['fields'][$field] = $value;
			}
			$params['whereOr'][] = $whereOrItem;
		}

		$select = array('e.datetime,p.id as patient_id,p.dob,p.hos_num,c.first_name,c.last_name');
		$where = "e.deleted = :notdeleted and ep.deleted = :notdeleted and p.deleted = :notdeleted and c.deleted = :notdeleted";
		$whereParams = array(':notdeleted' => 0);

		if (@$params['where']['firm_id']) {
			$where .= " and ep.firm_id = ?";
			$whereParams[] = $params['where']['firm_id'];
		}
		if (@$params['where']['date_from']) {
			$where .= " and e.datetime >= ?";
			$whereParams[] = date('Y-m-d',strtotime($params['where']['date_from']))." 00:00:00";
		}
		if (@$params['where']['date_to']) {
			$where .= " and e.datetime <= ?";
			$whereParams[] = date('Y-m-d',strtotime($params['where']['date_to']))." 23:59:59";
		}

		if (@$params['whereOr']) {
			foreach ($params['whereOr'] as $whereOr) {
				$clause = '';
				foreach ($whereOr['fields'] as $field => $value) {
					if ($clause) $clause .= ' or ';
					$clause .= "$field = ?";
					$whereParams[] = $value;
				}
				if ($clause) {
					$where .= " and ($clause)";
				}
			}
		}

		$data = Yii::app()->db->createCommand()
			->from('event e')
			->join('episode ep','e.episode_id = ep.id')
			->join('patient p','ep.patient_id = p.id')
			->join('contact c',"c.parent_class = 'Patient' and c.parent_id = p.id");

		foreach ($this->elements as $element) {
			$model = new $element->elementType->class_name;
			$table = $model->tableName();

			if ($element->optional) {
				$data->leftJoin("$table el{$element->id}","el{$element->id}.event_id = e.id");
				$where .= " and (el{$element->id}.id is null or el{$element->id}.deleted = :notdeleted)";
			} else {
				$data->join("$table el{$element->id}","el{$element->id}.event_id = e.id");
				$where .= " and el{$element->id}.deleted = :notdeleted";
			}

			$select[] = "el{$element->id}.id as el{$element->id}_id";

			foreach ($element->fields as $field) {
				$select[] = "el{$element->id}.{$field->field}";
			}

			foreach ($element->joins as $join) {
				$data->join($join->join_table,"el{$element->id}.{$join->join_clause}");
				$select[] = $join->join_select;

				$where .= "$join->join_table.deleted = :notdeleted";
			}
		}

		return $data->select(implode(',',$select))->where($where,$whereParams)->queryAll();
	}

	public function compute_Patients($inputs) {
		$select = array("p.id as patient_id, p.hos_num, c.first_name, c.last_name");
		$where = '';

		$command = Yii::app()->db->createCommand()
			->from('patient p')
			->join("contact c","c.parent_class = 'Patient' and c.parent_id = p.id");

		foreach ($this->inputs as $input) {
			if ($input->relatedEntity) {
				foreach ($input->relatedEntity->tables as $table) {
					if (isset($inputs[$table->type->name])) {
						foreach ($inputs[$table->type->name] as $i => $inputItem) {
							$command->join("{$table->table_name} {$table->type->name}_$i","{$table->type->name}_$i.{$table->table_related_field} = p.id");
							$select[] = "{$table->type->name}_$i.{$table->table_query_field} as {$table->type->name}_query_$i";
							$select[] = "{$table->type->name}_$i.{$table->table_date_field} as {$table->type->name}_date_$i";
							if ($where) $where .= ' and ';
							$where .= "{$table->type->name}_$i.{$table->table_query_field} = $inputItem";

							foreach ($table->relations as $j => $relation) {
								$command->join("{$relation->related_table} {$table->type->name}_relation_{$i}_$j","{$table->type->name}_$i.{$relation->local_field} = {$table->type->name}_relation_{$i}_$j.id");
								$select[] = "{$table->type->name}_relation_{$i}_$j.{$relation->select_field} as {$table->type->name}_relation_{$i}_{$relation->select_field_as}";
							}
						}
					}
				}
			}
		}

		$results = array();

		foreach ($command->select(implode(',',$select))->where($where)->queryAll() as $row) {
			$dates = array();

			foreach ($row as $key => $value) {
				if (preg_match('/_date_([0-9]+)$/',$key,$m)) {
					$dates[strtotime($value)] = $value;
				}
			}

			ksort($dates);
			$row['date'] = array_shift($dates);

			foreach ($this->inputs as $input) {
				if ($input->relatedEntity) {
					$row[$input->relatedEntity->name] = array();

					foreach ($input->relatedEntity->types as $type) {
						$i=0;
						while (1) {
							$relatedItem = array();

							foreach ($row as $key => $value) {
								if (preg_match('/^'.$type->name.'_relation_'.$i.'_(.*)$/',$key,$m)) {
									$relatedItem[$m[1]] = $value;
								}
							}

							if (empty($relatedItem)) break;

							$row[$input->relatedEntity->name][] = $relatedItem;

							$i++;
						}
					}
				}
			}

			$results[] = $row;
		}

		return $results;
	}
}
