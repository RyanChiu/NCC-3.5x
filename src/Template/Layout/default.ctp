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

    <?= ''//$this->Html->css('base.css') ?>
    <?= ''//$this->Html->css('cake.css') ?>
    <?= $this->Html->css("mine.css") ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    
    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('jquery.fancybox.min.css') ?>
    <?= $this->Html->script('jquery-3.2.1.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('ckeditor/ckeditor.js') ?>
    <?= $this->Html->script('zrkits/extrakits.inc.js') ?>
    <?= $this->Html->script('jquery.fancybox.min.js') ?>
    
    <!-- include the BotDetect layout stylesheet -->
	<?= $this->Html->css(captcha_layout_stylesheet_url(), ['inline' => false]) ?>
    
</head>
<body style="background-color:black;">
	<?=
	'<center>'
		. $this->Html->image('topbanner-f.png', ['style' => 'border:0'])
		. '</center>';
	?>
    <?php //echo "<font color='red'>here@" . print_r($mustread, true) . "</font>";
    if ($userinfo) {
	    echo $this->Navbar->create($this->Html->icon('home') . ' HOME', 
	    	['fluid' => true, 'inverse' => true, 'style' => 'margin-bottom:0;']);
		    echo $this->Navbar->beginMenu();
			    echo $this->Navbar->beginMenu('NEWS');
			    	echo $this->Navbar->link("UPDATE NEWS", '/accounts/updnews');
			   		echo $this->Navbar->link('ALERTS', '/accounts/updalerts');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->beginMenu('OFFICE');
			    	echo $this->Navbar->link('MANAGE OFFICE', '/accounts/lstcompanies/-1');
			    echo $this->Navbar->endMenu();
			    echo $this->Navbar->beginMenu('AGENT');
			    	echo $this->Navbar->link('MANAGE AGENT', '/accounts/lstagents/-1');
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
		    echo $this->Navbar->text(
		    	$this->Html->icon('user') . $userinfo['username'] . '&nbsp;&nbsp;&nbsp;&nbsp;'
		    	. '<a href="/accounts/logout">' 
		    	. $this->Html->icon('log-out') . 'Log Out</a>'
		    		. '<br/>'
		    		. '<label id="lblClock" style="color:white;margin-top:2px;padding:0;"></label>', 
		    	['style' => 'float:right;margin:5px 0 0 0;padding:0;']);
	    echo $this->Navbar->end();
    }
    ?>
    <?= $this->Panel->create(['style' => 'background-color:black;border:0;color:white;']) ?>
    	<?= $this->Panel->body(['style' => 'margin:0 16px 0 16px;padding:0;']) ?>
    	<?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
        <?= $this->Panel->footer(['style' => 'background-color: #011122;border:0;']) ?>
        <?= $this->Html->icon('stats'); ?>
        <?= "<div style='float:right'>Copyright &copy; 
			2015 www.NinjasChatClub.com All Rights Reserved.</div>" ?>
    <?= $this->Panel->end() ?>
    
    <!-- popup attention message part -->
	<div style="display:none;width:800px;" id="attentions_for_agents">
		<p style="padding: 3px;">
			<?php 
			echo !empty($alerts) ? $alerts->notes : '';
			?>
		</p>

		<hr style="margin: 6px 0px 6px 0px" />
		<hr style="margin: 6px 0px 6px 0px" />

		<?php
		if (!empty($excludedsites)) {
		?>
		<p style="font-weight: bold; font-size: 14px; color: red;">
			YOUR
			<?php echo '"' . implode("\", \"", $excludedsites) . '"'; ?>
			LINKS HAVE BEEN SUSPENDED, PLEASE CONTACT <a
				href="mailto:SUPPORT@ninjaschatclub.com"><font color="red">NinjasChatClub
					SUPPORT</font> </a> FOR MORE INFO.<br /> <a
				href="mailto:SUPPORT@ninjaschatclub.com"><font color="red">SUPPORT@ninjaschatclub.com</font>
			</a>
		</p>
		<div style="margin: 12px 2px 2px 2px; font-weight: bolder;">REASONS
			FOR TEMPORARY SUSPENSION</div>
		<p style="font-size: 14px; color: red;">
			<br/>
			1.SENDING LOW QUALITY SALES,  CUSTOMERS WHO DO NOT SPEND MONEY.<br/><br/>
			2.CREATING FAKE ACCOUNTS.<br/><br/>
			3.USING STOLEN CARDS.<br/><br/>
			4.TELLING CUSTOMER SITE IS FREE.<br/><br/>
			5.TELLING CUSTOMER YOU WILL MEET HIM.<br/>
		</p>
		<?php
		}
		?>

		<p style="text-align: center; margin: 9px 0px 0px 9px;">
			<?php
			echo $this->Html->link('<font style="font-weight:bold;font-size:36px;color:red;">ENTER</font>',
				"#",
				array('onclick' => 'javascript:jQuery.fancybox.close();jQuery.post(\'' 
					. 'accounts/pass' 
					. '\', function(data) {});',
					'escape' => false
				),
				false
			);
			?>
		</p>
	</div>
	
	<!-- for "agent must read" -->
	<div id="mustread_for_agents" style="display:none; width:500px;">
	<?php
	if (isset($mustread) && !empty($mustread)) {
		echo $mustread;
	?>
		<hr style="margin: 6px 0px 6px 0px" />
		<hr style="margin: 6px 0px 6px 0px" />
		<p style="text-align:center;color:red;font-size:10pt;margin:9px 0px 0px 9px;">
		If you have any questions, "YOU ARE" welcome to email us: Support@NinjasChatClub.Com.
		</p>
		<p style="text-align: center; margin: 9px 0px 0px 9px;">
		<?php
		echo $this->Html->link('<font style="font-weight:bold;">I\'ve read it, please let me in.</font>',
			"#",
			array('onclick' => 'javascript:jQuery(\'a#mustread_for_agents\').hide();javascript:jQuery.fancybox.close();;',
				'escape' => false
			),
			false
		);
		?>
		</p>
	<?php 
	}
	?>
	</div>
    
    <!-- js scripts -->
    <script type="text/javascript">
    __zShowClock();
    jQuery("[href='/']").attr("href", "/accounts");

    jQuery(document).ready(function() {
        <?php 
        if ($userinfo && !$this->request->session()->check('switch_pass')) {
        ?>
			jQuery.fancybox.open({
				'src' : '#attentions_for_agents',
				'type' : 'inline',
				'modal': true
			});
			<?php 
			if (isset($mustread) && !empty($mustread)) {
			?>
			jQuery.fancybox.open({
				'src' : '#mustread_for_agents',
				'type': 'inline',
				'modal': true
			});
			//jQuery("a#mustread_link").click();
			<?php 
			} else {
			?>
			//jQuery("a#attentions_link").click();
			<?php 
			}
			?>
		<?php 
		}
		?>
	});
    </script>
</body>
</html>
