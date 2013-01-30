<?php

class DefaultController extends BaseController {
	public $assetPath;
	public $page = 1;
	public $items_per_page = 10;
	public $pages = 1;
	//public $layout = 'main';
	public $title;
	public $event;
	public $report;

	public function filters()
	{
		return array('accessControl');
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@')
			),
			// non-logged in can't view anything
			array('deny',
				'users'=>array('?')
			),
		);
	}

	protected function beforeAction($action) {
		if ($action->id != 'download') {
			$this->assetPath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.'.$this->getModule()->name.'.assets'), false, -1, YII_DEBUG);

			Yii::app()->clientScript->registerCSSFile($this->assetPath.'/css/module.css');
			Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/d3.js');
			Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/d3.chart.js');
			Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/underscore.js');
			Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/oe_bulletgraph.js');
			Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/reports.js');
		}

		return parent::beforeAction($action);
	}

	public function actionIndex() {
		$this->report = Report::model()->find(array('order'=>'id'));
		$this->render('index');
	}

	public function actionView($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}
		$this->render('index');
	}

	public function actionExecute($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}

		$this->renderPartial('_report_summary',array('data'=>$this->report->execute($_POST)));
	}

	public function actionPrint($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}

		if (!isset($_POST['reportParams'])) {
			throw new Exception("Missing reportParams");
		}

		$_POST = json_decode(rawurldecode($_POST['reportParams']),true);

		$data = $this->report->execute($_POST);

		$this->layout = '//layouts/pdf';

		$pdf_print = new OEPDFPrint('Openeyes', 'OpenEyes Reports', 'OpenEyes Reports');

		$body = $this->renderPartial('_report_pdf', array(
			'data' => $data,
		), true);

		$pdf = new OELetter;
		$pdf->addBody($body);
		$pdf_print->addLetter($pdf);

		$pdf_print->output();
	}

	public function actionData($id) {
		if (!$report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}

		$json = array();
		$values = array();

		foreach ($report->items as $item) {
			$values[] = $item->value;
		}

		foreach ($values as $value) {
			$object = new stdClass;
			$object->title = '';
			$object->subtitle = '';
			$object->ranges = array(max($values));
			$object->measures = array($value);
			$object->markers = array($value);
			$json[] = $object;
		}

		echo json_encode($json);
	}

	public function actionCataract_complications() {
		$this->render('cataract_complications',array('graphs'=>$this->stubData($this->cataractComplicationGraphs,array(
			'subtitle' => '%',
			'ranges' => array(100),
			'measures' => array(0),
			'markers' => array(100),
		),true)));
	}

	public function actionCataract_complications_by_site() {
		preg_match('/([0-9]+)$/',$_SERVER['REQUEST_URI'],$m) and $site_id = $m[1];

		$this->uri = '/Reports/default/'.preg_replace('/^action/','',__FUNCTION__);

		$this->render('cataract_complications',array(
			'graphs' => $this->stubData($this->getSiteGraphs(@$site_id),array(
				'subtitle' => '%',
				'ranges' => array(100),
				'measures' => array(0),
				'markers' => array(100),
			),true),
			'site_id' => @$site_id,
			'site_ids' => $this->siteIDs,
		));
	}

	public function actionCataract_complications_by_surgeon() {
		if (preg_match('/([0-9]+)\/([0-9]+)$/',$_SERVER['REQUEST_URI'],$m)) {
			$this->page = $m[1];
			$surgeon_id = $m[2];
		} else if (preg_match('/([0-9]+)$/',$_SERVER['REQUEST_URI'],$m)) {
			$this->page = $m[1];
		}

		$this->uri = '/Reports/default/'.preg_replace('/^action/','',__FUNCTION__);

		$this->render('cataract_complications',array(
			'graphs'=>$this->stubData($this->getSurgeonGraphs(@$surgeon_id),array(
				'subtitle' => '%',
				'ranges' => array(100),
				'measures' => array(0),
				'markers' => array(100),
			),true),
			'surgeon_id' => @$surgeon_id,
			'surgeon_ids' => $this->surgeonIDs,
		));
	}

	public function getHomeGraphs() {
		$graphs = array(
			'Logins' => array(
				'uri' => "/Reports/default/loginStats",
				'properties' => array(
					"Logins since 1.1 training",
					"All logins",
				),
			),
			'Event types' => array(
				'uri' => "/Reports/default/eventTypeStats",
				'properties' => array(),
			),
		);

		foreach (EventType::model()->findAll(array('order'=>'name')) as $event_type) {
			if ($event_type->class_name != 'OphLeEpatientletter' && file_exists(Yii::app()->basePath."/modules/".$event_type->class_name)) {
				$graphs['Event types']['properties'][] = $event_type->name;
			}
		}

		return $graphs;
	}

	public function getCataractComplicationGraphs() {
		$graphs = array(
			'Complications' => array(
				'uri' => "/Reports/default/complicationStats",
				'properties' => array('Overall'),
			),
		);

		foreach (CataractComplications::model()->findAll(array('order'=>'name asc')) as $cc) {
			$graphs['Complications']['properties'][] = $cc->name;
		}

		return $graphs;
	}

	public function getSiteGraphs($site_id=null) {
		$graphs = array(
			'Sites' => array(
				'uri' => "/Reports/default/siteStats?page=".$this->page,
				'properties' => array(),
			),
		);

		if ($site_id == null) {
			foreach (StatsComplicationSite::model()->findAll('complication_id is null and value_total >0') as $s) {
				$graphs['Sites']['properties'][] = $s->site->name;
			}
		} else {
			$graphs['Sites']['uri'] .= "&site_id=$site_id";

			foreach (StatsComplicationSite::model()->findAll('site_id=? and value_total >0',array($site_id)) as $s) {
				if ($s->complication) {
					$graphs['Sites']['properties'][] = $s->complication->name;
				} else {
					$graphs['Sites']['properties'][] = 'Overall';
				}
			}
		}

		return $graphs;
	}

	public function getSiteIDs() {
		$site_ids = array();
		foreach (StatsComplicationSite::model()->findAll('complication_id is null and value_total >0') as $s) {
			$site_ids[$s->site->name] = $s->site_id;
		}
		return $site_ids;
	}

	public function getSurgeonGraphs($surgeon_id=null) {
		$graphs = array(
			'Surgeons' => array(
				'uri' => "/Reports/default/surgeonStats?page=".$this->page,
				'properties' => array(),
			),
		);

		if ($surgeon_id == null) {
			$offset = ($this->page-1) * $this->items_per_page;

			foreach (StatsComplicationSurgeon::model()->findAll('complication_id is null and value_total >0') as $i => $s) {
				if ($i >= $offset && count($graphs['Surgeons']['properties']) <$this->items_per_page) {
					$graphs['Surgeons']['properties'][] = $s->surgeon->first_name.' '.$s->surgeon->last_name;
				}
			}

			$this->pages = ceil(($i+1)/$this->items_per_page);
		} else {
			$graphs['Surgeons']['uri'] .= "&surgeon_id=".$surgeon_id;

			foreach (StatsComplicationSurgeon::model()->findAll('surgeon_id = ? and value_total >0',array($surgeon_id)) as $s) {
				$graphs['Surgeons']['properties'][] = ($s->complication ? $s->complication->name : 'Overall');
			}
		}

		return $graphs;
	}

	public function getSurgeonIDs() {
		$surgeon_ids = array();
		foreach (StatsComplicationSurgeon::model()->findAll('complication_id is null and value_total >0') as $s) {
			$surgeon_ids[$s->surgeon->first_name.' '.$s->surgeon->last_name] = $s->surgeon_id;
		}
		return $surgeon_ids;
	}

	public function stubData($graphdata, $params, $percentage=false) {
		$graphs = array();

		foreach ($graphdata as $name => $data) {
			$objects = array();

			foreach ($data['properties'] as $property) {
				$obj = new stdClass;
				$obj->title = $property;
				$obj->subtitle = $params['subtitle'];
				$obj->ranges = $params['ranges'];
				$obj->measures = $params['measures'];
				$obj->markers = $params['markers'];
				$objects[] = $obj;
			}

			$graphs[$name] = array(
				'uri' => $data['uri'],
				'initialData' => json_encode($objects),
				'percentage' => $percentage,
			);
		}

		return $graphs;
	}

	public function actionLoginStats() {
		$json = array();

		$data = $this->stats();

		$obj = new stdClass;
		$obj->title = "Logins since 1.1 training";
		$obj->subtitle = "number of";
		$obj->ranges = array(2000);
		$obj->measures = array(397); //$data['Logins since 1.1 training']);
		$obj->markers = array(397);
		$json[] = $obj;

		$obj = new stdClass;
		$obj->title = "All logins";
		$obj->subtitle = "number of";
		$obj->ranges = array(2000);
		$obj->measures = array(997); //$data['All logins']);
		$obj->markers = array(997);
		$json[] = $obj;

		$currently_logged_in = Yii::app()->db->createCommand("select count(*) from user_session where expire < now();")->queryScalar();

		$obj = new stdClass;
		$obj->title = "Currently logged in";
		$obj->subtitle = "number of";
		$obj->ranges = array($currently_logged_in + (($currently_logged_in/100) * 20));
		$obj->measures = array($currently_logged_in);
		$obj->markers = array($currently_logged_in);
		$json[] = $obj;

		echo json_encode($json);
	}

	public function actionEventTypeStats() {
		$data = $this->stats();

		$json = array();

		foreach (EventType::model()->findAll(array('order'=>'name')) as $event_type) {
			if ($event_type->class_name != 'OphLeEpatientletter' && file_exists(Yii::app()->basePath."/modules/".$event_type->class_name)) {
				$count = $data[$event_type->name];
				$range = $this->getRangeFromCount($count);

				$obj = new stdClass;
				$obj->title = $event_type->name;
				$obj->subtitle = "number of";
				$obj->ranges = array($range);
				$obj->measures = array($count);
				$obj->markers = array($count);
				$json[] = $obj;
			}
		}

		echo json_encode($json);
	}

	public function getRangeFromCount($count) {
		$count_20pc = ($count/100) * 120;
		$range = round($count_20pc,-3);
		while ($range < $count_20pc) {
			$range += 50;
		}

		if ($range == 0) {
			$range = 1000;
		}

		return $range;
	}

	public function actionComplicationStats() {
		$json = array();

		$opnote = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$cataract = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementCataract'));

		$range = ceil(Yii::app()->db->createCommand()->select("max(value_percent)")->from("stats_complication")->where("event_type_id=? and element_type_id=?",array($opnote->id,$cataract->id))->queryScalar());

		foreach (StatsComplication::model()->findAll('event_type_id=? and element_type_id=?',array($opnote->id,$cataract->id)) as $c) {
			$obj = new stdClass;

			if ($c->complication) {
				$obj->title = $c->complication->name;
			} else {
				$obj->title = "Overall";
			}

			$obj->subtitle = $c->value_raw.' / '.$c->value_total;
			$obj->ranges = array($range);
			$obj->measures = array($c->value_percent);
			$obj->markers = array($c->value_percent);
			$json[] = $obj;
		}

		echo json_encode($json);
	}

	public function actionSurgeonStats() {
		$json = array();

		$opnote = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$cataract = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementCataract'));

		if (ctype_digit(@$_GET['surgeon_id'])) {
			$range = ceil(Yii::app()->db->createCommand()->select("max(value_percent)")->from("stats_complication_surgeon")->where("event_type_id=? and element_type_id=? and surgeon_id=?",array($opnote->id,$cataract->id,$_GET['surgeon_id']))->queryScalar());

			// get institution values for comparison
			$institution_values = array();
			foreach (StatsComplication::model()->findAll('value_total >0') as $c) {
				$institution_values[$c->complication_id] = $c->value_percent;
				if ($c->value_percent > $range) {
					$range = ceil($c->value_percent);
				}
			}

			foreach (StatsComplicationSurgeon::model()->findAll('surgeon_id=? and value_total >0',array($_GET['surgeon_id'])) as $c) {
				$obj = new stdClass;
				$obj->title = $c->complication ? $c->complication->name : 'Overall';
				$obj->subtitle = $c->value_raw.' / '.$c->value_total;
				$obj->ranges = array($range);
				$obj->measures = array((float)$institution_values[$c->complication_id],(float)$c->value_percent);
				$obj->markers = array($c->value_percent);
				$json[] = $obj;
			}
		} else {
			$page = $_GET['page'];
			$offset = ($page-1) * $this->items_per_page;

			$range = ceil(Yii::app()->db->createCommand()->select("max(value_percent)")->from("stats_complication_surgeon")->where("event_type_id=? and element_type_id=? and complication_id is null",array($opnote->id,$cataract->id))->queryScalar());

			$institution_rate = StatsComplication::model()->find('event_type_id=? and element_type_id=? and complication_id is null',array($opnote->id,$cataract->id))->value_percent;

			foreach (StatsComplicationSurgeon::model()->findAll('complication_id is null and value_total >0') as $i => $c) {
				if ($i >= $offset && count($json) < $this->items_per_page) {
					$obj = new stdClass;
					$obj->title = $c->surgeon->first_name.' '.$c->surgeon->last_name;
					$obj->subtitle = $c->value_raw.' / '.$c->value_total;
					$obj->ranges = array($range);
					$obj->measures = array((float)$c->value_percent,(float)$institution_rate);
					$obj->markers = array($c->value_percent);
					$json[] = $obj;
				}
			}
		}

		echo json_encode($json);
	}

	public function actionSiteStats() {
		$json = array();

		$opnote = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$cataract = ElementType::model()->find('event_type_id=? and class_name=?',array($opnote->id,'ElementCataract'));

		if (ctype_digit(@$_GET['site_id'])) {
			$range = ceil(Yii::app()->db->createCommand()->select("max(value_percent)")->from("stats_complication_site")->where("event_type_id=? and element_type_id=? and site_id=?",array($opnote->id,$cataract->id,$_GET['site_id']))->queryScalar());

			$institution_rates = array();
			foreach (StatsComplication::model()->findAll('event_type_id=? and element_type_id=? and value_total >0',array($opnote->id,$cataract->id)) as $c) {
				$institution_rates[$c->complication_id] = $c->value_percent;
			}

			foreach (StatsComplicationSite::model()->findAll('event_type_id=? and element_type_id=? and site_id=? and value_total >0',array($opnote->id,$cataract->id,$_GET['site_id'])) as $c) {
				$obj = new stdClass;
				$obj->title = ($c->complication ? $c->complication->name : 'Overall');
				$obj->subtitle = $c->value_raw.' / '.$c->value_total;
				$obj->ranges = array($range);
				$obj->measures = array((float)$c->value_percent,(float)$institution_rates[$c->complication_id]);
				$obj->markers = array($c->value_percent);
				$json[] = $obj;
			}
		} else {
			$range = ceil(Yii::app()->db->createCommand()->select("max(value_percent)")->from("stats_complication_site")->where("event_type_id=? and element_type_id=? and complication_id is null",array($opnote->id,$cataract->id))->queryScalar());

			$institution_rate = StatsComplication::model()->find('event_type_id=? and element_type_id=? and complication_id is null',array($opnote->id,$cataract->id))->value_percent;

			foreach (StatsComplicationSite::model()->findAll('event_type_id=? and element_type_id=? and complication_id is null and value_total >0',array($opnote->id,$cataract->id)) as $c) {
				$obj = new stdClass;
				$obj->title = $c->site->name;
				$obj->subtitle = $c->value_raw.' / '.$c->value_total;
				$obj->ranges = array($range);
				$obj->measures = array($c->value_percent,(float)$institution_rate);
				$obj->markers = array($c->value_percent);
				$json[] = $obj;
			}
		}

		echo json_encode($json);
	}

	public function actionCataractComplications() {
		Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/gauge.js');

		$this->render('cataract_complications',array('data' => $this->stats()));
	}

	public function stats() {
		return array(
			'Logins since 1.1 training' => Yii::app()->db->createCommand()->select("count(distinct ucase(data))")->from("audit")->where("action = 'login-successful' and created_date > '2012-09-04 05:00:00'")->queryScalar(),
			'All logins' => Yii::app()->db->createCommand()->select("count(distinct ucase(data))")->from("audit")->where("action = 'login-successful'")->queryScalar(),
			'Operation note' => $this->countEventsByType('OphTrOperationnote'),
			'Prescription' => $this->countEventsByType('OphDrPrescription'),
			'Correspondence' => $this->countEventsByType('OphCoCorrespondence'),
			'Examination' => $this->countEventsByType('OphCiExamination'),
			'Anaesthetic Satisfaction Audit' => $this->countEventsByType('OphOuAnaestheticsatisfactionaudit'),
			'Cataract operations' => $this->cataractStats(),
		);
	}

	public function countEventsByType($class_name) {
		$event_type = EventType::model()->find('class_name=?',array($class_name));

		return Yii::app()->db->createCommand()
			->select("count(event.id)")
			->from("event")
			->join("episode","event.episode_id = episode.id")
			->where("event.event_type_id = $event_type->id and episode.deleted = 0 and event.deleted = 0")
			->queryScalar();
	}

	public function cataractStats() {
		$data = array(
			'Cataract operations' => Yii::app()->db->createCommand()
				->select("count(event.id)")
				->from("event")
				->join("episode","event.episode_id = episode.id")
				->join("et_ophtroperationnote_cataract","et_ophtroperationnote_cataract.event_id = event.id")
				->where("event_type_id = 4 and event.deleted = 0 and episode.deleted = 0")
				->queryScalar(),
		);

		$criteria = new CDbCriteria;
		$criteria->order = 'name asc';
		$criteria->compare('institution_id',1);

		$site_cataracts = array();

		foreach (Site::model()->findAll($criteria) as $site) {
			$site_cataracts[$site->id] = Yii::app()->db->createCommand()
				->select("count(event.id)")
				->from("event")
				->join("episode","event.episode_id = episode.id")
				->join("et_ophtroperationnote_cataract","et_ophtroperationnote_cataract.event_id = event.id")
				->join("et_ophtroperationnote_procedurelist","et_ophtroperationnote_procedurelist.event_id = event.id")
				->join("element_operation","et_ophtroperationnote_procedurelist.element_operation_id = element_operation.id")
				->join("booking","booking.element_operation_id = element_operation.id")
				->join("session","booking.session_id = session.id")
				->join("theatre","session.theatre_id = theatre.id")
				->where("event_type_id = 4 and event.deleted = 0 and episode.deleted = 0 and theatre.site_id = $site->id")
				->queryScalar();
		}

		$all_surgeons = array();

		foreach (CataractComplications::model()->findAll(array('order'=>'display_order')) as $cc) {
			$data[$cc->name]['Total'] = Yii::app()->db->createCommand()->select("count(event.id)")->from("event")->join("episode","event.episode_id = episode.id")->join("et_ophtroperationnote_cataract","et_ophtroperationnote_cataract.event_id = event.id")->join("et_ophtroperationnote_cataract_complication","et_ophtroperationnote_cataract_complication.cataract_id = et_ophtroperationnote_cataract.id")->where("event_type_id = 4 and event.deleted = 0 and episode.deleted = 0 and et_ophtroperationnote_cataract_complication.complication_id = $cc->id")->queryScalar();

			$data[$cc->name]['Total'] .= " (".number_format($data[$cc->name]['Total'] / ($data['Cataract operations']/100),2)."%)";

			$criteria = new CDbCriteria;
			$criteria->order = 'name asc';
			$criteria->compare('institution_id',1);

			foreach (Site::model()->findAll($criteria) as $site) {
				$data[$cc->name][$site->name]['Total'] = Yii::app()->db->createCommand()
					->select("count(event.id)")
					->from("event")
					->join("episode","event.episode_id = episode.id")
					->join("et_ophtroperationnote_cataract","et_ophtroperationnote_cataract.event_id = event.id")
					->join("et_ophtroperationnote_cataract_complication","et_ophtroperationnote_cataract_complication.cataract_id = et_ophtroperationnote_cataract.id")
					->join("et_ophtroperationnote_procedurelist","et_ophtroperationnote_procedurelist.event_id = event.id")
					->join("element_operation","et_ophtroperationnote_procedurelist.element_operation_id = element_operation.id")
					->join("booking","booking.element_operation_id = element_operation.id")
					->join("session","booking.session_id = session.id")
					->join("theatre","session.theatre_id = theatre.id")
					->where("event_type_id = 4 and event.deleted = 0 and episode.deleted = 0 and et_ophtroperationnote_cataract_complication.complication_id = $cc->id and theatre.site_id = $site->id")
					->queryScalar();

				if ($data[$cc->name][$site->name]['Total'] >0) {
					$data[$cc->name][$site->name]['Total'] .= "/".$site_cataracts[$site->id];
					$data[$cc->name][$site->name]['Total'] .= " (".number_format($data[$cc->name][$site->name]['Total'] / ($site_cataracts[$site->id]/100),2)."%)";
				} else {
					unset($data[$cc->name][$site->name]);
				}

				$surgeons = array();

				foreach (Yii::app()->db->createCommand()
					->select("user.id, user.first_name, user.last_name")
					->from("event")
					->join("episode","event.episode_id = episode.id")
					->join("et_ophtroperationnote_cataract","et_ophtroperationnote_cataract.event_id = event.id")
					->join("et_ophtroperationnote_cataract_complication","et_ophtroperationnote_cataract_complication.cataract_id = et_ophtroperationnote_cataract.id")
					->join("et_ophtroperationnote_procedurelist","et_ophtroperationnote_procedurelist.event_id = event.id")
					->join("element_operation","et_ophtroperationnote_procedurelist.element_operation_id = element_operation.id")
					->join("booking","booking.element_operation_id = element_operation.id")
					->join("session","booking.session_id = session.id")
					->join("theatre","session.theatre_id = theatre.id")
					->join("et_ophtroperationnote_surgeon","et_ophtroperationnote_surgeon.event_id = event.id")
					->join("user","et_ophtroperationnote_surgeon.surgeon_id = user.id")
					->where("event_type_id = 4 and event.deleted = 0 and episode.deleted = 0 and et_ophtroperationnote_cataract_complication.complication_id = $cc->id and theatre.site_id = $site->id")
					->queryAll() as $row) {

					$name = $row['first_name'].' '.$row['last_name'];

					if (!isset($surgeons[$name])) {
						$surgeons[$name] = 1;
					} else {
						$surgeons[$name]++;
					}

					if (!isset($all_surgeons[$row['id']])) {
						$all_surgeons[$row['id']] = 1;
					} else {
						$all_surgeons[$row['id']]++;
					}
				}

				foreach ($surgeons as $surgeon => $count) {
					$data[$cc->name][$site->name][$surgeon] = $count;
				}
			}
		}

		$data['surgeons'] = array();

		foreach ($all_surgeons as $user_id => $count) {
			$user = User::model()->findByPk($user_id);
			$name = $user->first_name.' '.$user->last_name;

			$data['surgeons'][$name]['count'] = $count;

			$data['surgeons'][$name]['total'] = Yii::app()->db->createCommand()
				->select("count(event.id)")
				->from("event")
				->join("episode","event.episode_id = episode.id")
				->join("et_ophtroperationnote_cataract","et_ophtroperationnote_cataract.event_id = event.id")
				->join("et_ophtroperationnote_procedurelist","et_ophtroperationnote_procedurelist.event_id = event.id")
				->join("element_operation","et_ophtroperationnote_procedurelist.element_operation_id = element_operation.id")
				->join("booking","booking.element_operation_id = element_operation.id")
				->join("session","booking.session_id = session.id")
				->join("theatre","session.theatre_id = theatre.id")
				->join("et_ophtroperationnote_surgeon","et_ophtroperationnote_surgeon.event_id = event.id")
				->join("user","et_ophtroperationnote_surgeon.surgeon_id = user.id")
				->where("event_type_id = 4 and event.deleted = 0 and episode.deleted = 0 and et_ophtroperationnote_surgeon.surgeon_id = $user_id")
				->queryScalar();

			$pc = number_format(($count / ($data['surgeons'][$name]['total']/100)),2);

			$data['surgeons'][$name]['pc'] = $pc;
		}

		ksort($data['surgeons']);

		return $data;
	}

	public function getCommonOphthalmicDisorders() {
		return CommonOphthalmicDisorder::getList(Firm::model()->findByPk($this->selectedFirmId));
	}
}
