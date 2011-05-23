<div class="cakeuploadFiles index">
	<h2><?php __('Cakeupload Files');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('group');?></th>
			<th><?php echo $this->Paginator->sort('originalFilename');?></th>
			<th><?php echo $this->Paginator->sort('uploadedFilename');?></th>
			<th><?php echo $this->Paginator->sort('fspath');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($cakeuploadFiles as $cakeuploadFile):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $cakeuploadFile['CakeuploadFile']['id']; ?>&nbsp;</td>
		<td><?php echo $cakeuploadFile['CakeuploadFile']['group']; ?>&nbsp;</td>
		<td><?php echo $cakeuploadFile['CakeuploadFile']['originalFilename']; ?>&nbsp;</td>
		<td><?php echo $cakeuploadFile['CakeuploadFile']['uploadedFilename']; ?>&nbsp;</td>
		<td><?php echo $cakeuploadFile['CakeuploadFile']['fspath']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $cakeuploadFile['CakeuploadFile']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $cakeuploadFile['CakeuploadFile']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $cakeuploadFile['CakeuploadFile']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $cakeuploadFile['CakeuploadFile']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cakeupload File', true), array('action' => 'add')); ?></li>
	</ul>
</div>