<?php
// src/Model/Entity/Company.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Company extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
}