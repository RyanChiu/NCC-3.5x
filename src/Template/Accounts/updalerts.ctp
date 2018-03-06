<?php
$userinfo = $Auth->user();
echo $this->Form->create($admin);
?>
<div class="row">
	<div class="col-md-2">
		Alerts
	</div>
	<div class="col-md-8">
		<?php
		echo $this->Form->control('notes', array('label' => '', 'rows' => '20', 'cols' => '60'));
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<?php echo $this->Form->button(__('Update'), array('style' => 'width:112px;')); ?></td>
	</div>
</div>

<?php
echo $this->Form->control('id', array('type' => 'hidden'));
echo $this->Form->end();
?>

<script type="text/javascript">
	CKEDITOR.replace('notes',
		{
	        filebrowserUploadUrl : '/NCC/accounts/upload',
	        filebrowserWindowWidth : '640',
	        filebrowserWindowHeight : '480'
	    }
	);
	CKEDITOR.config.height = '300px';
	CKEDITOR.config.width = '830px';
	CKEDITOR.config.resize_maxWidth = '830px';
	CKEDITOR.config.toolbar =
		[
		    ['Source','-','NewPage','Preview','-','Templates'],
		    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
		    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		    '/',
		    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		    ['Link','Unlink','Anchor'],
		    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		    '/',
		    ['Styles','Format','Font','FontSize'],
		    ['TextColor','BGColor']
		];
</script>
