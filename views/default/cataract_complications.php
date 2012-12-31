<h2>OpenEyes Reporting Module</h2>
<?php
echo $this->renderPartial('_menu');
echo $this->renderPartial('_pagination');
?>
<?Php if (@$site_id || @$surgeon_id) {?>
	<div class="selectionTabs">
		<?php if (@$site_id) {?>
			<span class="selectionTab">
				<?php echo Site::model()->findByPk($site_id)->name?>
			</span>
		<?php }?>
		<?php if (@$surgeon_id) {?>
			<span class="selectionTab">
				<?php echo User::model()->findByPk($surgeon_id)->fullName?>
			</span>
		<?php }?>
	</div>
<?php }?>
<div class="graphTabs">
	<span class="graphTab<?php if (preg_match('/\/Reports\/default\/cataract_complications(\/[0-9]+)?$/i',$_SERVER['REQUEST_URI'])) {?> active<?php }?>">
		<a href="<?php echo Yii::app()->createUrl('/Reports/default/cataract_complications')?>">
			Institution
		</a>
	</span>
	<span class="graphTab<?php if (preg_match('/\/Reports\/default\/cataract_complications_by_site/i',$_SERVER['REQUEST_URI'])) {?> active<?php }?>">
		<a href="<?php echo Yii::app()->createUrl('/Reports/default/cataract_complications_by_site')?>">
			Sites
		</a>
	</span>
	<span class="graphTab<?php if (preg_match('/\/Reports\/default\/cataract_complications_by_surgeon/i',$_SERVER['REQUEST_URI'])) {?> active<?php }?>">
		<a href="<?php echo Yii::app()->createUrl('/Reports/default/cataract_complications_by_surgeon')?>">
			Surgeons
		</a>
	</span>
</div>
<div id="graph0" class="ReportGraph whiteBox"></div>
<?php echo $this->renderPartial('_pagination')?>
<script type="text/javascript">
	var site_id = <?php echo (@$site_id ? $site_id : 'false')?>;

	var site_ids = {};
	<?php if (isset($site_ids)) {?>
		<?php foreach ($site_ids as $name => $id) {?>
			site_ids["<?php echo $name?>"] = <?php echo $id?>;
		<?php }?>
	<?php }?>

	var surgeon_id = <?php echo (@$surgeon_id ? $surgeon_id : 'false')?>;

	var surgeon_ids = {};
	<?php if (isset($surgeon_ids)) {?>
		<?php foreach ($surgeon_ids as $name => $id) {?>
			surgeon_ids["<?php echo $name?>"] = <?php echo $id?>;
		<?php }?>
	<?php }?>

	<?php $i=0; foreach ($graphs as $name => $data) {?>
		var g<?php echo $i?> = new OE_BulletGraph({
			"id": "graph<?php echo $i?>",
			"uri": baseUrl+"<?php echo $data['uri']?>",
			"initialData": <?php echo $data['initialData']?>,
			"refreshRate": 5000,
			"percentage": <?php echo $data['percentage'] ? 'true' : 'false'?>,
		});
	<?php $i++; }?>
	$('text.title').click(function() {
		if (!site_id && window.location.href.match(/cataract_complications_by_site/i)) {
			window.location.href = baseUrl+'/Reports/default/cataract_complications_by_site/'+site_ids[$(this).text()];
		}
		if (!surgeon_id && window.location.href.match(/cataract_complications_by_surgeon/i)) {
			window.location.href = baseUrl+'/Reports/default/cataract_complications_by_surgeon/1/'+surgeon_ids[$(this).text()];
		}
		return false;
	});
</script>
