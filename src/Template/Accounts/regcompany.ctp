<?php
echo $this->Form->create($data, [
	'url' => ['controller' => 'accounts', 'action' => 'regcompany'],
	"class" => 'form-inline',
	'id' => 'frmReg'
]);var_dump($tmp);
?>
<table style="width:100%;border:0;">
	<caption>Fields marked with an asterisk (<font color="red">*</font>) are required.</caption>
	<tr>
		<td width="222">Office Name : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.officename', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
		<!--  
		<td rowspan="15" align="center"><?php //echo $this->Html->image('iconGiveDollars.png', array('width' => '160')); ?></td>
		-->
	</tr>
	<tr>
		<td>Manager's First Name : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.man1stname', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Manager's Last Name : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.manlastname', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Manager's Email : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.manemail', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Manager's Cell NO. : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.mancellphone', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Username for this Office : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Account.username', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Password : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Account.password', ['label' => '', 'style' => 'width:390px;', 'type' => 'password']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Confirm password : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Account.originalpwd', ['label' => '', 'style' => 'width:390px;', 'type' => 'password']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Street Name &amp; Number : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.street', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td>City : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.city', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td>State &amp; Zip : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->input('Company.state', ['label' => '', 'style' => 'width:390px;']);
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td>Country : </td>
		<td>
		<div style="float:left">
		<?php
		echo $this->Form->select('Company.country', $cts, ['style' => 'width:390px;']);
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Agent Notes : </td>
		<td>
		<?php
		echo $this->Form->input('Company.agentnotes', ['label' => '', 'rows' => '9', 'cols' => '60']);
		?>
		</td>
	</tr>
	<tr>
		<td>Associated Sites: </td>
		<td>
		<?php
		$selsites = array_diff($sites, $exsites);
		$selsites = array_keys($selsites);
		echo $this->Form->select('SiteExcluding.siteid',
			$sites,
			[
				'multiple' => 'checkbox',
				'value' => $selsites
			]
		);
		?>
		</td>
	</tr>
	<tr>
		<td>
		<?php
		echo $this->Form->input('Account.status', ['type' => 'hidden', 'value' => '-1']);//the default status if unapproved
		?>
		</td>
		<td>
		<?php
		echo $this->Form->button(__('Add & New'), [
			'onclick' => 'javascript:__changeAction(\'frmReg\', \''
				. $this->Url->build([
					'controller' => 'accounts',
					'action' => 'regcompany',
					'id' => -1
				])
				. '\');',
			'style' => 'width:112px;',
			'class' => 'btn btn-info btn-xs'
		]);
		echo "&nbsp;";
		echo $this->Form->button(__('Add'), [
			'onclick' => 'javascript:__changeAction(\'frmReg\', \''
				. $this->Url->build([
					'controller' => 'accounts',
					'action' => 'regcompany'
				])
				. '\');',
			'style' => 'width:112px;',
			'class' => 'btn btn-info btn-xs'
		]);
		?>
		</td>
	</tr>
</table>
<script type="text/javascript">
jQuery(":checkbox").attr({style: "border:0px;width:16px;vertical-align:middle;"});
</script>
<?php
echo $this->Form->input('Account.role', array('type' => 'hidden', 'value' => '1'));//the value 1 as being an office
echo $this->Form->input('Account.regtime', array('type' => 'hidden', 'value' => ''));//should be set to current time when saving into the DB
echo $this->Form->input('Account.online', array('type' => 'hidden', 'value' => '0'));// the value 0 means "offline"
echo $this->Form->input('Company.id', array('type' => 'hidden'));
echo $this->Form->end();
?>