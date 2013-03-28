<?php

class DefaultController extends BaseController {
	public $assetPath;
	public $title;
	public $report;
	public $jsVars = array();

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

	public function selectDefaultReport() {
		if (!$firm = Firm::model()->findByPk($this->selectedFirmId)) {
			throw new Exception("No firm selected");
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("subspecialty_id = {$firm->serviceSubspecialtyAssignment->subspecialty_id}");
		$criteria->order = 'display_order asc';

		if (!$this->report = Report::model()->find($criteria)) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("subspecialty_id is null");
			$criteria->order = 'display_order asc';
			$this->report = Report::model()->find($criteria);
		}

		$this->jsVars['OE_report_id'] = $this->report ? $this->report->id : null;
	}

	public function actionIndex() {
		$this->selectDefaultReport();
		$this->render('index');
	}

	public function actionView($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}
		$this->jsVars['OE_report_id'] = $this->report->id;
		$this->render('index');
	}

	public function actionExecute($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}

		$this->renderPartial('_report_summary',array('data'=>$this->report->execute($_REQUEST)));
	}

	public function actionValidate($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}

		$errors = $this->report->validateInput($_REQUEST);

		if (!empty($errors)) {
			$this->renderPartial('//elements/form_errors',array('errors'=>$errors));
		}
	}

	public function actionPrint($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}

		$data = $this->report->execute($_GET);

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

	public function actionDownload($id) {
		if (!$this->report = Report::model()->findByPk($id)) {
			throw new Exception("Report not found: $id");
		}

		$data = $this->report->execute($_REQUEST);

		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename="OpenEyes Report '.date('d.m.Y').'.csv"');
		header("Pragma: no-cache");
		header("Expires: 0");

		$csv = $this->renderPartial('_report_csv',array('data'=>$data),true);

		echo $csv;
	}

	public function getCommonOphthalmicDisorders() {
		return CommonOphthalmicDisorder::getList(Firm::model()->findByPk($this->selectedFirmId));
	}

	protected function beforeRender($view) {
		foreach ($this->jsVars as $key => $value) {
			$value = CJavaScript::encode($value);
			Yii::app()->getClientScript()->registerScript('scr_'.$key, "$key = $value;",CClientScript::POS_READY);
		}
		return parent::beforeRender($view);
	}

	public function actionReportOperationSummary() {
		$this->render('report_operation_summary');
	}

	public function actionReportOperationSummaryValidate() {
		if (!@$_POST['event_type_id']) {
			$this->renderPartial('//elements/form_errors',array('errors'=>array('Event type'=>array('please select an event type'))));
		}
	}

	public function actionReportOperationSummaryExecute() {
		$cells = array();
		$site_ids = array();

		foreach (Site::model()->findAll() as $i => $site) {
			$site_ids[$site->name] = $i;

			$cells['header'][$site->name] = array(
				'< 16 yrs' => array('M','F','T'),
				'16 - 49 yrs' => array('M','F','T'),
				'50+ yrs' => array('M','F','T'),
				'Total' => array('M','F','T'),
			);
		}

		$subspecialty_ids = array();

		foreach (Site::model()->findAll() as $site) {
			foreach (Subspecialty::model()->findAll(array('order'=>'name asc')) as $i => $subspecialty) {
				$subspecialty_ids[$subspecialty->name] = $i;

				$cells['data'][$subspecialty->name][$site->name] = array(
					'< 16 yrs' => array(
						'M' => 0,
						'F' => 0,
						'Total' => 0,
					),
					'16 - 49 yrs' => array(
						'M' => 0,
						'F' => 0,
						'Total' => 0,
					),
					'50+ yrs' => array(
						'M' => 0,
						'F' => 0,
						'Total' => 0,
					),
					'Total' => array(
						'M' => 0,
						'F' => 0,
						'Total' => 0,
					),
				);
			}
		}

		foreach (Site::model()->findAll() as $n => $site) {
			for ($i=0;$i<12+($n*12);$i++) {
				$cells['Total'][$i] = 0;
			}
		}

		foreach (Subspecialty::model()->findAll(array('order'=>'name')) as $subspecialty) {
			$cells['Subspecialties'][$subspecialty->name] = 0;
		}

		$grand_total = 0;

		foreach (Yii::app()->db->createCommand()
			->select("p.dob, p.gender, s.name as subspecialty, si.name as site, e.site_id")
			->from("event e")
			->join("episode ep","e.episode_id = ep.id")
			->join("patient p","ep.patient_id = p.id")
			->join("firm f","ep.firm_id = f.id")
			->join("service_subspecialty_assignment ssa","f.service_subspecialty_assignment_id = ssa.id")
			->join("subspecialty s","ssa.subspecialty_id = s.id")
			->leftJoin("et_ophtroperationnote_procedurelist pl","pl.event_id = e.id")
			->leftJoin("et_ophtroperationbooking_operation eo","eo.event_id = pl.booking_event_id")
			->leftJoin("ophtroperationbooking_operation_booking b","b.element_id = eo.id")
			->leftJoin("ophtroperationbooking_operation_session se","b.session_id = se.id")
			->leftJoin("ophtroperationbooking_operation_theatre t","se.theatre_id = t.id")
			->leftJoin("site si","t.site_id = si.id")
			->where("e.event_type_id = ?",array($_POST['event_type_id']))
			->queryAll() as $event) {

			if (!$event['site']) {
				$event['site'] = Site::model()->findByPk($event['site_id'])->name;
			}

			$cells['Subspecialties'][$event['subspecialty']]++;

			$grand_total++;

			$total_id = $site_ids[$event['site']] * 12;

			$cells['data'][$event['subspecialty']][$event['site']]['Total'][$event['gender']]++;
			$cells['data'][$event['subspecialty']][$event['site']]['Total']['Total']++;

			if ($event['gender'] == 'M') {
				$cells['Total'][$total_id+9]++;
			} else {
				$cells['Total'][$total_id+10]++;
			}
			$cells['Total'][$total_id+11]++;

			$age = Helper::getAge($event['dob']);

			if ($age <16) {
				$cells['data'][$event['subspecialty']][$event['site']]['< 16 yrs'][$event['gender']]++;
				$cells['data'][$event['subspecialty']][$event['site']]['< 16 yrs']['Total']++;

				if ($event['gender'] == 'M') {
					$cells['Total'][$total_id]++;
				} else {
					$cells['Total'][$total_id+1]++;
				}
				$cells['Total'][$total_id+2]++;
			} else if ($age >= 16 and $age <= 49) {
				$cells['data'][$event['subspecialty']][$event['site']]['16 - 49 yrs'][$event['gender']]++;
				$cells['data'][$event['subspecialty']][$event['site']]['16 - 49 yrs']['Total']++;
				if ($event['gender'] == 'M') {
					$cells['Total'][$total_id+3]++;
				} else {
					$cells['Total'][$total_id+4]++;
				}
				$cells['Total'][$total_id+5]++;
			} else if ($age >= 50) {
				$cells['data'][$event['subspecialty']][$event['site']]['50+ yrs'][$event['gender']]++;
				$cells['data'][$event['subspecialty']][$event['site']]['50+ yrs']['Total']++;
				if ($event['gender'] == 'M') {
					$cells['Total'][$total_id+6]++;
				} else {
					$cells['Total'][$total_id+7]++;
				}
				$cells['Total'][$total_id+8]++;
			}
		}

		$this->renderPartial('_report_operation_summary_output',array('cells'=>$cells,'grand_total'=>$grand_total));
	}
}
