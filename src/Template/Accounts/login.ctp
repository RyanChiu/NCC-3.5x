<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->control('username') ?>
<?= $this->Form->control('password') ?>

<!-- show captcha image html -->
    <?= captcha_image_html() ?>

    <!-- Captcha code user input textbox -->
    <?= $this->Form->input('CaptchaCode', [
      'label' => 'Retype the characters from the picture:',
      'maxlength' => '10',
      'id' => 'CaptchaCode'
    ]) ?>

<?= $this->Form->button('Login') ?>
<?= $this->Form->end() ?>