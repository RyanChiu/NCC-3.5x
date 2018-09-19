<?php
$userinfo = $Auth->user();
echo $this->Form->create(null, [
	'url' => ['controller' => 'accounts', 'action' => 'updcompany'],
	"class" => 'form-inline'
]);
?>
<table style="width:100%;border:0;">
	<caption>Fields marked with an asterisk (*) are required.</caption>
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
				'disabled' => 'false',
				'value' => $selsites
			]
		);
		if ($userinfo['role'] != 0) {//means not an administrator
		?>
			<div id="msgbox_nochange" style="display:none;float:left;background-color:#ffffcc;">
			<font color="red">
			Sorry, you can't do this.If you want to, please contact your administrator.
			</font>
			</div>
			<script type="text/javascript">
			jQuery(":checkbox").click(
				function () {
					jQuery("#msgbox_nochange").show("normal");
					return false;
				}
			);
			jQuery("#msgbox_nochange").click(
				function () {
					jQuery(this).toggle("normal");
				}
			);
			</script>
		<?php	
		}
		?>
		</td>
	</tr>
	<tr>
		<td>
		<label id="labelUAS">Activated</label>
		<?php
		echo $this->Form->checkbox(
			'Account.status',
			['id' => 'AccountStatus']
		);
		?>
		</td>
		<td>
		<?php
		echo $this->Form->button(__('Update'), [
			'style' => 'width:112px;',
			'style' => 'width:112px;',
			'class' => 'btn btn-info btn-xs'
		]);
		?>
		</td>
	</tr>
</table>
<script type="text/javascript">
jQuery(":checkbox").attr({
	style: "border: 0px; width: 16px; margin-left: 2px; vertical-align: middle;"
});

jQuery("#AccountStatus").click(function() {
	if (jQuery("#AccountStatus").attr("checked")) {
		jQuery("#AccountStatus").val(1);
	} else {
		jQuery("#AccountStatus").val(0);
	}
});

if (jQuery("#AccountStatus").val() == "-1") {
	jQuery("#labelUAS").text("Approved");
	jQuery("#AccountStatus").attr("checked", false);
	jQuery("#AccountStatus").val(-1);
	jQuery("#AccountStatus_").val(-1);
} else {
	jQuery("#labelUAS").text("Activated");
}
</script>
<?php
echo $this->Form->input('Account.id', array('type' => 'hidden'));
echo $this->Form->input('Account.role', array('type' => 'hidden', 'value' => '1'));//the value 1 as being an office
echo $this->Form->input('Company.id', array('type' => 'hidden'));
echo $this->Form->end();
?>
