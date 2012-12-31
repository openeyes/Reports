<?php if ($this->pages >1) {?>
	<div class="reportsPagination whiteBox">
		<?php for ($i=1; $i<=$this->pages; $i++) {?>
			<?php if ($this->page == $i) {?>
				<span><?php echo $i?></span>
			<?php }else{?>
				<a href="<?php echo Yii::app()->createUrl($this->uri)?>/<?php echo $i?>"><?php echo $i?></a>
			<?php }?>
		<?php }?>
	</div>
<?php }?>
