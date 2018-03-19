<?php
// src/Model/Entity/TerminalCookie.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class TerminalCookie extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}