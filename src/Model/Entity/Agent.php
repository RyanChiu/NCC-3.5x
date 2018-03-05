<?php
// src/Model/Entity/Agent.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Agent extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}