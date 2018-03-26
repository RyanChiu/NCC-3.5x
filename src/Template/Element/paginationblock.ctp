<?php
if ($this->Paginator->hasPage(null, 2)) {
?>
<table>
<tr>
<td>
<!-- Shows the page numbers -->
<?php 
	echo $this->Paginator->numbers(
		array(
			'first' => '|<<', 'last' => '>>|',
			'before' => ' | ', 'after' => ' | ',
			'modulus' => 11
		)
	);
?>
</td>
<td>
<!-- Shows the next and previous links -->
<?php
	echo $this->Paginator->prev(
		$this->Html->image('prev.gif', array('style' => 'border:0px;margin-top:2px;')),
		array('escape' => false),
		$this->Html->image('prev_dis.gif', array('style' => 'border:0px;margin-top:2px;')),
		array('escape' => false, 'class' => 'disabled')
	);
?>
</td>
<td>
<?php
	echo $this->Paginator->next(
		$this->Html->image('next.gif', array('style' => 'border:0px;margin-top:2px;')),
		array('escape' => false),
		$this->Html->image('next_dis.gif', array('style' => 'border:0px;margin-top:2px;')),
		array('escape' => false, 'class' => 'disabled')
	);
?>
</td>
<td>
<!-- prints X of Y, where X is current page and Y is number of pages -->
<?php echo $this->Paginator->counter(); ?>
</td>
</tr>
</table>
<?php
}
?>