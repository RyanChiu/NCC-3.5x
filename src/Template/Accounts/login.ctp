<center>
<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->control('username', ['style' => 'width:360px;']) ?>
<?= $this->Form->control('password', ['style' => 'width:360px;']) ?>

<!-- show captcha image html -->
    <?= captcha_image_html() ?>

    <!-- Captcha code user input textbox -->
    <?= $this->Form->input('CaptchaCode', [
		'label' => 'Retype the characters from the picture:',
		'maxlength' => '10',
    	'style' => 'width:360px;',
		'id' => 'CaptchaCode'
    ]) ?>

<?= $this->Form->button('Login', ['style' => 'width:360px;', 'class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>
<br/>
</center>