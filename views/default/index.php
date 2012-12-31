<h2>OpenEyes Reporting Module</h2>
<?php
echo $this->renderPartial('_menu');
echo $this->renderPartial('_pagination');
for ($i=0; $i<count($graphs); $i++) {?>
	<div id="graph<?php echo $i?>" class="ReportGraph whiteBox"></div>
<?php }
echo $this->renderPartial('_pagination')?>
<script type="text/javascript">
	<?php $i=0; foreach ($graphs as $name => $data) {?>
		var g<?php echo $i?> = new OE_BulletGraph({
			"id": "graph<?php echo $i?>",
			"uri": baseUrl+"<?php echo $data['uri']?>",
			"initialData": <?php echo $data['initialData']?>,
			"refreshRate": 5000,
			"percentage": <?php echo $data['percentage'] ? 'true' : 'false'?>,
		});
	<?php $i++; }?>
</script>
