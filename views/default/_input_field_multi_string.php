<div class="multiString">
	<div class="stringInput">
		<?php echo CHtml::textField($input->name.'[]',$input->default_value,array('autocomplete'=>Yii::app()->params['html_autocomplete']))?>
	</div>
</div>
<div class="multiStringAdd">
	<button class="addString classy green mini" type="button">
		<span class="button-span button-span-green">Add</span>
	</button>
</div>
