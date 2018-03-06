<?php
// src/Model/Entity/Admin.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Admin extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}