<tr>
	<td colspan="2">
		Found <?php echo count($data[$item->data_field])?> results.
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php if (empty($data[$item->data_field])) {?>
			None
		<?php }else{?>
			<table class="grid reduceheight">
				<thead>
					<tr>
						<?php foreach ($item->displayListItems as $list_item) {?>
							<th><?php echo $list_item->name?></th>
						<?php }?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data[$item->data_field] as $i => $data_item) {?>
						<tr class="<?php if ($i%2 == 0 ){?>even<?php }else{?>odd<?php }?>">
							<?php foreach ($item->displayListItems as $list_item) {?>
								<td>
									<?php if (is_array($data_item[$list_item->data_field])) {?>
										<?php foreach ($data_item[$list_item->data_field] as $i => $data_list_item) {
											if ($i>0) echo "<br/>";
											foreach ($data_list_item as $data_list_item_key => $data_list_item_value) {
												echo $data_list_item_value." ";
											}
										}
										?>
									<?php }else{?>
										<?php if ($list_item->link) {?>
											<a href="<?php echo Yii::app()->createUrl($list_item->generateLink($data_item))?>">
										<?php }?>
										<?php echo $data_item[$list_item->data_field]?>
										<?php if ($list_item->link) {?>
											</a>
										<?php }?>
									<?php }?>
								</td>
							<?php }?>
						</tr>
					<?php }?>
				</tbody>
			</table>
		<?php }?>
	</td>
</tr>
