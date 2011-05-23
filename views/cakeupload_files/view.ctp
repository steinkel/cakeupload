<div class="cakeuploadFiles view">
<h2><?php  __('Cakeupload File');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cakeuploadFile['CakeuploadFile']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Group'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cakeuploadFile['CakeuploadFile']['group']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('OriginalFilename'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cakeuploadFile['CakeuploadFile']['originalFilename']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('UploadedFilename'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cakeuploadFile['CakeuploadFile']['uploadedFilename']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fspath'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $cakeuploadFile['CakeuploadFile']['fspath']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cakeupload File', true), array('action' => 'edit', $cakeuploadFile['CakeuploadFile']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Cakeupload File', true), array('action' => 'delete', $cakeuploadFile['CakeuploadFile']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $cakeuploadFile['CakeuploadFile']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cakeupload Files', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cakeupload File', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
