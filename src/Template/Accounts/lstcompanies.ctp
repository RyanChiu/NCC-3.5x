<?php
//echo '<br/>';
//echo print_r($rs, true);
?>
<?php
/*searching part*/
?>
<div style="width:100%;" id="search">
<?php
echo $this->Form->create(
	null, [
		"url" => ['controller' => 'accounts', 'action' => 'lstcompanies/frmSearch'],
		"class" => 'form-inline'
	]);
?>
<table style="width:100%;border:0;">
	<caption>
	<?php echo $this->Html->image('iconSearch.png', ['style' => 'width:16px;height:16px;']) . 'Search'; ?>
	</caption>
	<tr>
		<td class="search-label" style="width:105px;">Username:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $this->Form->control('username', ['label' => '', 'type' => 'text', 'style' => 'width:260px;', 'class' => '']); ?>
		</div>
		<div style="float:left;width:112px;">
		<?php echo $this->Form->button(__('Search'), ['style' => 'float:left;width:96px;', 'class' => 'btn btn-primary btn-sm']); ?>
		</div>
		<div style="float:left;">
		<?php echo $this->Form->button(__('Clear'), ['style' => 'float:left;width:64px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'javascript:__zClearForm("frmSearch");']); ?>
		</div>
		</td>
	</tr>
</table>
<?php
echo $this->Form->end();
?>
</div>
<br/>

<?php
/*showing the results*/
?>
<script type="text/javascript">
function __setActSusLink() {
	var checkboxes;
	checkboxes = document.getElementsByName("selComs");
	var ids = "";
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked && checkboxes[i].value != 0) {
			ids += checkboxes[i].value + ",";
		}
	}
	document.getElementById("linkActivateSelected").href =
		document.getElementById("linkActivateSelected_").href + "/ids:" + ids + "/status:1/from:0";
	document.getElementById("linkSuspendSelected").href =
		document.getElementById("linkSuspendSelected_").href + "/ids:" + ids + "/status:0/from:0";
}
function __setCurSelectedToBeInformed() {
	var checkboxes;
	checkboxes = document.getElementsByName("selComs");
	var ids = "";
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked && checkboxes[i].value != 0) {
			ids += checkboxes[i].value + ",";
		}
	}
	document.getElementById("hidCurSelectedToBeInformed").value = ids;
}
function __setInfLink() {
	document.getElementById("linkInform").href =
		document.getElementById("linkInform_").href + "/from:0"
			+ "/ids:" + document.getElementById("hidCurSelectedToBeInformed").value
			+ "/notes:" + document.getElementById("txtNotes").value;
}
function __checkAll() {
	var checkboxes;
	checkboxes = document.getElementsByName("selComs");
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = document.getElementById("checkboxAll").checked;
	}
}
</script>

<div style="margin-bottom:3px">
<?php
echo $this->Form->button(__('Add Office'), [
	'onclick' => 'javascript:location.href=\''
		. $this->Url->build(['controller' => 'accounts', 'action' => 'regcompany']) . '\'',
	'style' => 'width:120px;',
	'class' => 'btn btn-info btn-xs'
]);
?>
</div>
<table style="width:100%" class="table table-condensed">
<thead>
<tr>
	<th><b>
	<?php
	echo $this->Form->checkbox('', [
		'id' => 'checkboxAll', 'value' => -1,
		'style' => 'border:0px;width:16px;',
		'onclick' => 'javascript:__checkAll();__setActSusLink();'
	]);
	?>
	</b></th><?php $icon = "<i class='glyphicon glyphicon-chevron-up'></i>"; ?>
	<th><b><?php echo $this->ExPaginator->sort('ViewCompanies.officename', 'Office'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewCompanies.agenttotal', 'Total Agents'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewCompanies.username4m', 'Username'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewCompanies.originalpwd', 'Password'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewCompanies.manemail', 'Email'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewCompanies.regtime', 'Registered'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewCompanies.status', 'Status'); ?></b></th>
	<th><b><a href="#">Operation</a></b></th>
</tr>
</thead>
<?php
$i = 0;
foreach ($rs as $r):
?>
<tr <?php echo $i % 2 == 0 ? '' : 'class="odd"'; ?>>
	<td>
	<?php
	echo $this->Form->checkbox('selected', [
		'name' => 'selComs',
		'value' => $r->companyid,
		'style' => 'border:0px;width:16px;',
		'onclick' => 'javascript:__setActSusLink();'
	]);
	?>
	</td>
	<td>
	<?php
	/*
	echo $this->Html->link(
		$r['ViewCompany']['officename'],
		array('controller' => 'accounts', 'action' => 'lstagents', 'id' => $r['ViewCompany']['companyid']),
		array('title' => 'Click to the agents.')
	);
	*/
	echo $r->officename;
	?>
	</td>
	<td align="center">
	<?php
	echo $this->Html->link(
		$r->agenttotal . '&nbsp;' . $this->Html->image('iconList.gif', ['border' => 0]),
		['controller' => 'accounts', 'action' => 'lstagents/' . $r->companyid],
		['title' => 'Click to the agents.', 'escape' => false]
	);
	?>
	</td>
	<td><?php echo $r->username; ?></td>
	<td><?php echo $r->originalpwd; ?></td>
	<td><?php echo $r->manemail; ?></td>
	<td><?php echo $r->regtime; ?></td>
	<td><?php echo $status[$r->status]; ?></td>
	<td align="center">
	<?php
	echo $this->Html->link(
		$this->Html->image('iconEdit.png', ['border' => 0, 'width' => 16, 'height' => 16]) . '&nbsp;',
		['controller' => 'accounts', 'action' => 'updcompany', 'id' => $r->companyid],
		['title' => 'Click to edit this record.', 'escape' => false]
	);
	echo $this->Html->link(
		$this->Html->image('iconActivate.png', ['border' => 0, 'width' => 16, 'height' => 16]) . '&nbsp;',
		['controller' => 'accounts', 'action' => 'activatem', 'ids' => $r->companyid, 'status' => 1, 'from' => 0],
		['title' => 'Click to activate the user.', 'escape' => false]
	);
	echo $this->Html->link(
		$this->Html->image('iconSuspend.png', ['border' => 0, 'width' => 16, 'height' => 16]) . '&nbsp;',
		['controller' => 'accounts', 'action' => 'activatem', 'ids' => $r->companyid, 'status' => 0, 'from' => 0],
		['title' => 'Click to suspend the user.', 'escape' => false, 'confirm' => "Are you sure?"]
	);
	$iconEye = '';
	$statusEye = -2;
	$styleEye = 'hide';
	if ($r->status != -2) {
		$iconEye = 'eye-half-icon.png';
		$statusEye = -2;
		$styleEye = 'hide';
	} else {
		$iconEye = 'eye-icon.png';
		$statusEye = 1;
		$styleEye = 'show';
	}
	echo $this->Html->link(
		$this->Html->image($iconEye, ['border' => 0, 'width' => 20, 'height' => 20]) . '&nbsp;',
		['controller' => 'accounts', 'action' => 'activatem', 'ids' => $r->companyid, 'status' => $statusEye, 'from' => 0],
		['title' => 'Click to ' . $styleEye . ' the office.', 'escape' => false, 'confirm' => "Are you sure to " . $styleEye . " this one?"]
	);
	?>
	</td>
</tr>
<?php
$i++;
endforeach;
?>
</table>

<div style="margin-top:3px;">
<font color="green">With selected :&nbsp;</font>
<?php
/*activate selected*/
echo $this->Html->link(
	$this->Html->image('iconActivate.png', ['border' => 0, 'width' => 16, 'height' => 16]) . '&nbsp;&nbsp;',
	['controller' => 'accounts', 'action' => 'activatem'],
	['id' => 'linkActivateSelected', 'title' => 'Click to activate the selected users.', 'escape' => false]
);
echo $this->Html->link(
	'',
	['controller' => 'accounts', 'action' => 'activatem'],
	['id' => 'linkActivateSelected_']
);
/*suspend selected*/
echo $this->Html->link(
	$this->Html->image('iconSuspend.png', ['border' => 0, 'width' => 16, 'height' => 16]) . '&nbsp;&nbsp;',
	['controller' => 'accounts', 'action' => 'activatem'],
	['id' => 'linkSuspendSelected', 'title' => 'Click to suspend the selected users.', 'escape' => false, 'confirm' => "Are you sure?"]
);
echo $this->Html->link(
	'',
	['controller' => 'accounts', 'action' => 'activatem'],
	['id' => 'linkSuspendSelected_']
);
/*inform selected --*/
/*undim this block to function it
echo $this->Html->link(
	$this->Html->image('iconInform.png',
		array('id' => 'open_message',
			'border' => 0, 'width' => 16, 'height' => 16,
			'onclick' => 'javascript:__setCurSelectedToBeInformed();__setInfLink();'
		)
	),
	'#',
	array('title' => 'Click to inform the selected users.', 'escape' => false),
	false
);
*/
?>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~the floating message box for "inform selected"~~~~~~~~~~~~~~~~~~~ -->
<div id="message_box" style="display:none;">
	<table style="width:100%">
	<thead><tr><th>
		<div style="float:left">Please enter your notes below.</div>
		<?php echo $this->Html->image('iconClose.png', ['id' => 'close_message', 'style' => 'float:right;cursor:pointer']); ?>
	</th></tr></thead>
	<tr><td><textarea id="txtNotes" style="width:99%" rows="6" onchange="javascript:__setInfLink();"></textarea></td></tr>
	<tr><td>
		<?php
		echo $this->Form->control('', ['type' => 'hidden', 'id' => 'hidCurSelectedToBeInformed']);
		echo $this->Html->link(
			'',
			['controller' => 'accounts', 'action' => 'informem'],
			['id' => 'linkInform_']
		);
		echo $this->Html->link(
			'Inform',
			['controller' => 'accounts', 'action' => 'informem'],
			['id' => 'linkInform']
		);
		?>
	</td></tr>
	</table>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<?php
echo $this->element('paginationblock');
?>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->