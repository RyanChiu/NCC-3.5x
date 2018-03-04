<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$NCCDescription = 'Ninja\'s Chat Club';
$userinfo = $Auth->user();
$role = -1;//means everyone
if ($userinfo) {
	$role = $userinfo['role'];
}

$menuitemscount = 0;
$curmenuidx = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $NCCDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    
    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->script('jquery-1.12.4.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('zrkits/extrakits.inc.js') ?>
    
    <!-- include the BotDetect layout stylesheet -->
	<?= $this->Html->css(captcha_layout_stylesheet_url(), ['inline' => false]) ?>
    
</head>
<body style="background-color:black;">
    <?php 
    if ($userinfo) {
	    echo $this->Navbar->create($this->Html->icon('home') . ' HOME', ['fluid' => true, 'inverse' => true, 'style' => 'margin-bottom:0;']);
		    echo $this->Navbar->beginMenu();
			    echo $this->Navbar->beginMenu('NEWS', '/accounts/news');
			   		echo $this->Navbar->link('ALERTS', '/');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->beginMenu('OFFICE', '/accounts/office');
			    	echo $this->Navbar->link('MANAGE OFFICE', '/');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->beginMenu('AGENT', '/accounts/agent');
			    	echo $this->Navbar->link('MANAGE AGENT', '/');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->link('APPROVE NEW AGENT', '/');
			    echo $this->Navbar->beginMenu('LINK', '/links/');
			    	echo $this->Navbar->link('CONFIG SITE', '/');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->beginMenu('STATS', '/stats/');
			    	echo $this->Navbar->link('OFFICE PERFORMANCE CHARTS', '/');
			    	echo $this->Navbar->link('TOP 10 ARCHIVES', '/');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->beginMenu('LOG', '/');
			    	echo $this->Navbar->link('CHAT LOG', '/');
			    	echo $this->Navbar->link('CLICK LOG', '/');
			    	echo $this->Navbar->link('LOGIN LOG', '/');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->link('PROFILE', '/');
			    echo $this->Navbar->link('HOW TO SELL', '/');
		    echo $this->Navbar->endMenu();
		    echo $this->Navbar->text('<a href="/accounts/logout">' 
		    	. $this->Html->icon('log-out') . 'Log Out</a>'
		    		. '<br/>'
		    		. '<label id="lblClock" style="color:white;margin-top:2px;padding:0;"></label>', 
		    	['style' => 'float:right;margin:5px 0 0 0;padding:0;']);
	    echo $this->Navbar->end();
    } else {
    	echo $this->Navbar->create('', ['fluid' => true, 'inverse' => true]);
    		echo $this->Navbar->text(
    			$this->Html->image('topbanner.png', ['style' => 'border:0'])
    		);
    	echo $this->Navbar->end();
    }
    ?>
    <?= $this->Panel->create(['style' => 'background-color:black;padding-top:2px;']) ?>
    	<?= $this->Panel->body() ?>
    	<?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
        <?= $this->Panel->footer() ?>
        <?= $this->Html->icon('stats'); ?>
        <?= "<div style='float:right'>Copyright &copy; 2015 www.NinjasChatClub.com All
					Rights Reserved.</div>" ?>
    <?= $this->Panel->end() ?>
    
    <!-- js scripts -->
    <script type="text/javascript">
    __zShowClock();
    </script>
</body>
</html>
