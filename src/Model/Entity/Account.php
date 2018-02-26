<?php
// src/Model/Entity/Account.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Account extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}