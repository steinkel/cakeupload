<div class="cakeuploadFiles form">
<?php echo $this->Form->create('CakeuploadFile');?>
	<fieldset>
 		<legend><?php __('Add Cakeupload File'); ?></legend>
	<?php
		echo $this->Form->input('group');
		echo $this->Form->input('originalFilename');
		echo $this->Form->input('uploadedFilename');
		echo $this->Form->input('fspath');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Cakeupload Files', true), array('action' => 'index'));?></li>
	</ul>
</div>