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
			$this->assetPath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.'.$this->getModule()->name.'.assets'), false, -1);

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
}
