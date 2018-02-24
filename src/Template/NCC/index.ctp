<?php
echo "index page, here you go...<hr/>";

echo $this->Form->create();
echo $this->Form->input('username', ['type' => 'text']);
echo $this->Form->input('password', ['type' => 'password']);
echo $this->Form->input('remember', ['type' => 'checkbox']);
echo $this->Form->submit('Log In');
echo $this->Form->end();

?>
