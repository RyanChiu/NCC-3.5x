<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<!-- the old one
<div class="message success" onclick="this.classList.add('hidden')"><?= $message ?></div>
-->
<div onclick="this.classList.add('hidden')">
	<p 
		class="bg-success" 
		style="color:green;font-weight:bold;text-align:center;padding:2px;">
		<?= $message ?></p>
</div>