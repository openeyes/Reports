<div id="graph" class="ReportGraph whiteBox"></div>
<script type="text/javascript">
	var g = new OE_BulletGraph({
		"id": "graph",
		"uri": baseUrl+'/Reports/default/data/<?php echo $this->report->id?>',
		"initialData": <?php echo json_encode($this->report->initialData)?>,
		"refreshRate": 5000,
		"percentage": false,
		"width": 730,
	});
</script>
