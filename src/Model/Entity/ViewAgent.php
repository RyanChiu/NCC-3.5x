<?php
// src/Model/Entity/ViewAgent.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class ViewAgent extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}