<div class="cakeuploadFiles form">
<?php echo $this->Form->create('CakeuploadFile');?>
	<fieldset>
 		<legend><?php __('Edit Cakeupload File'); ?></legend>
	<?php
		echo $this->Form->input('id');
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

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('CakeuploadFile.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('CakeuploadFile.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Cakeupload Files', true), array('action' => 'index'));?></li>
	</ul>
</div>