<?php
/*
echo '<br/>';
echo print_r($url, true);
echo print_r($pass, true);
echo print_r($passedArgs, true);
*/
$userinfo = $Auth->user();
?>
<?php
/*searching part*/
?>
<div style="width:100%;margin-top:5px;" id="search">
<?php
echo $this->Form->create(
	null, [
		"url" => ['controller' => 'accounts', 'action' => 'lstagents'], 
		'id' => 'frmSearch'
	]);
?>
<table style="width:100%;border:0;">
	<caption>
	<?php echo $this->Html->image('iconSearch.png', array('style' => 'width:16px;height:16px;')) . 'Search'; ?>
	</caption>
	<tr>
		<td class="search-label" style="width:105px;">Username:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $this->Form->input('ViewAgent.username', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<?php
		if ($userinfo['role'] == 0) {
		?>
		<td class="search-label" style="width:145px;">Office:</td>
		<?php
		} else {
		?>
		<td colspan="2" width="450px"></td>
		<?php
		}
		if ($userinfo['role'] == 0) {
			echo '<td>';
			echo $this->Form->input('Company.id',
				array('label' => '', 'type' => 'select',
					'options' => $coms,	'style' => 'width:260px;'
				)
			);
			echo '</td>';
		}
		?>
	</tr>
	<tr>
		<td class="search-label" style="width:105px;">Last Name:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $this->Form->input('ViewAgent.aglastname', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<td class="search-label">Campaign ID:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $this->Form->input('AgentSiteMapping.campaignid', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
	</tr>
	<tr>
		<td class="search-label" style="width:105px;">First Name:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $this->Form->input('ViewAgent.ag1stname', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<td class="search-label">Status:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php
		echo $this->Form->input('ViewAgent.status',
			array(
				'label' => '', 'style' => 'width:260px;',
				'type' => 'select', 'options' => (array('-1' => 'All') + $status)
			)
		);
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td class="search-label" style="width:105px;">Email:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $this->Form->input('ViewAgent.email', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<td class="search-label">Suspended Site:</td>
		<td>
		<?php
		echo $this->Form->input('SiteExcluding.siteid',
			array('label' => '', 'type' => 'select',
				'options' => $sites,	'style' => 'width:260px;'
			)
		);
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="2">
		<div style="float:left;width:112px;">
		<?php echo $this->Form->submit('Search', array('style' => 'float:left;width:96px;')) ?>
		</div>
		<div style="float:left;">
		<?php echo $this->Form->submit('Clear', array('style' => 'float:left;width:96px;', 'onclick' => 'javascript:__zClearForm("frmSearch");')); ?>
		</div>
		</td>
	</tr>
</table>
<?php
$companyid = 0;
if ($userinfo['role'] == 1) {//means an office
	$companyid = $userinfo['id'];
}
echo $this->Form->input('ViewAgent.companyid', array('type' => 'hidden', 'value' => $companyid));
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
	checkboxes = document.getElementsByName("data[ViewAgent][selected]");
	var ids = "";
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked && checkboxes[i].value != 0) {
			ids += checkboxes[i].value + ",";
		}
	}
	document.getElementById("linkActivateSelected").href =
		document.getElementById("linkActivateSelected_").href + "/ids:" + ids + "/status:1/from:1";
	document.getElementById("linkSuspendSelected").href =
		document.getElementById("linkSuspendSelected_").href + "/ids:" + ids + "/status:0/from:1";
}
function __setCurSelectedToBeInformed() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[ViewAgent][selected]");
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
		document.getElementById("linkInform_").href + "/from:1"
			+ "/ids:" + document.getElementById("hidCurSelectedToBeInformed").value
			+ "/notes:" + document.getElementById("txtNotes").value;
}
function __checkAll() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[ViewAgent][selected]");
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = document.getElementById("checkboxAll").checked;
	}
}
</script>

<div style="margin-bottom:3px">
<?php
if (in_array($userinfo['role'], array(0, 1))) {//means an administrator or an office
	//echo $this->Form->button($userinfo['role'] == 0 ? 'Add Agent' : 'Request New Agent',
	echo $this->Form->button('Add Agent',
		array(
			'onclick' => 'javascript:location.href=\'' .
				$this->Html->url(array('controller' => 'accounts', 'action' => 'regagent')) . '\'',
			'style' => 'width:160px;'
		)
	);
}
?>
</div>
<table style="width:100%">
<thead>
<tr>
	<th><b>
	<?php
	echo $this->Form->checkbox('',
		array('id' => 'checkboxAll', 'value' => -1,
			'style' => 'border:0px;width:16px;',
			'onclick' => 'javascript:__checkAll();__setActSusLink();'
		)
	);
	?>
	</b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.username4m', 'Username'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.originalpwd', 'Password'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.officename', 'Office'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.regtime', 'Registered'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.lastlogintime', 'Last Login'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.logintimes', 'Login Times'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.status', 'Status'); ?></b></th>
	<th><b><?php echo $this->ExPaginator->sort('ViewAgent.campaigns', 'Campaigns'); ?></b></th>
	<th><b>Operation</b></th>
	<th>
	<?php
	echo $this->Html->link(
		$this->Html->image('viewit.png', array('border' => 0)),
		array('controller' => 'accounts', 'action' => 'lstchatlogs'),
		array('title' => 'Click to all the chat logs.', 'escape' => false),
		false
	);
	?>
	</th>
</tr>
</thead>
<?php
$i = 0;
foreach ($rs as $r):
?>
<tr <?php echo $i % 2 == 0? '' : 'class="odd"'; ?>>
	<td>
	<?php
	echo $this->Form->checkbox('ViewAgent.selected',
		array('value' => $r->id,
			'style' => 'border:0px;width:16px;',
			'onclick' => 'javascript:__setActSusLink();'
		)
	);
	echo '<font size="1">' . ($i + 1 + $limit * ($this->Paginator->current() - 1)) . '</font>';
	?>
	</td>
	<td><?php echo $r->username; ?></td>
	<td><?php echo $r->originalpwd; ?></td>
	<td><?php echo $r->officename; ?></td>
	<td><?php echo $r->regtime; ?></td>
	<td>
	<?php
	$lastintm = $r->lastlogintime;
	echo empty($lastintm) ? '-' : $lastintm;
	?>
	</td>
	<td align="center">
	<?php
	if ($r->logintimes == 0) {
		echo $r->logintimes;
	} else {
		echo $this->Html->link(
			$r->logintimes . '&nbsp;' . $this->Html->image('iconList.gif', array('border' => 0)),
			array('controller' => 'accounts', 'action' => 'lstlogins', 'id' => $r->id),
			array('title' => 'Click to the login logs', 'escape' => false), 
			false
		);
	}
	?>
	</td>
	<td><?php echo $status[$r->status]; ?></td>
	<td align="center">
	<?php
	echo $this->Html->link(
		$r->campaigns . '&nbsp;' . $this->Html->image('iconList.gif', array('border' => 0)),
		array('controller' => 'links', 'action' => 'lstcampaigns', 'id' => $r->id),
		array('title' => 'Click to the campaigns.', 'escape' => false), 
		false
	);
	?>
	</td>
	<td align="center">
	<?php
	echo $this->Html->link(
		$this->Html->image('iconEdit.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'accounts', 'action' => 'updagent', 'id' => $r->id),
		array('title' => 'Click to edit this record.', 'escape' => false),
		false
	);
	echo $this->Html->link(
		$this->Html->image('iconActivate.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'accounts', 'action' => 'activatem', 'ids' => $r->id, 'status' => 1, 'from' => 1),
		array('title' => 'Click to activate the user.', 'escape' => false),
		false
	);
	echo $this->Html->link( 
		$this->Html->image('iconSuspend.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'accounts', 'action' => 'activatem', 'ids' => $r->id, 'status' => 0, 'from' => 1),
		array('title' => 'Click to suspend the user.', 'escape' => false),
		"Are you sure?"
	);
	?>
	</td>
	<td align="center">
	<?php
	echo $this->Html->link(
		$this->Html->image('chatlogs.png', array('border' => 0)),
		array('controller' => 'accounts', 'action' => 'lstchatlogs', 'id' => $r->id),
		array('title' => 'Click to the chat logs.', 'escape' => false),
		false
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
	$this->Html->image('iconActivate.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;&nbsp;',
	array('controller' => 'accounts', 'action' => 'activatem'),
	array('id' => 'linkActivateSelected', 'title' => 'Click to activate the selected users.', 'escape' => false),
	false
);
echo $this->Html->link(
	'',
	array('controller' => 'accounts', 'action' => 'activatem'),
	array('id' => 'linkActivateSelected_')
);
/*suspend selected*/
echo $this->Html->link(
	$this->Html->image('iconSuspend.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;&nbsp;',
	array('controller' => 'accounts', 'action' => 'activatem'),
	array('id' => 'linkSuspendSelected', 'title' => 'Click to suspend the selected users.', 'escape' => false),
	"Are you sure?"
);
echo $this->Html->link(
	'',
	array('controller' => 'accounts', 'action' => 'activatem'),
	array('id' => 'linkSuspendSelected_')
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
		<?php echo $this->Html->image('iconClose.png', array('id' => 'close_message', 'style' => 'float:right;cursor:pointer')); ?>
	</th></tr></thead>
	<tr><td><textarea id="txtNotes" style="width:99%" rows="6" onchange="javascript:__setInfLink();"></textarea></td></tr>
	<tr><td>
		<?php
		echo $this->Form->input('', array('type' => 'hidden', 'id' => 'hidCurSelectedToBeInformed'));
		echo $this->Html->link(
			'',
			array('controller' => 'accounts', 'action' => 'informem'),
			array('id' => 'linkInform_')
		);
		echo $this->Html->link(
			'Inform',
			array('controller' => 'accounts', 'action' => 'informem'),
			array('id' => 'linkInform'),
			false
		);
		?>
	</td></tr>
	</table>
</div>
<script type="text/javascript">
jQuery(":checkbox").attr({style: "border:0px;width:16px;vertical-align:middle;"}); 
</script>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<?php
echo $this->element('paginationblock');
?>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->