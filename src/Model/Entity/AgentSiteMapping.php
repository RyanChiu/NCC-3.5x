<?php
// src/Model/Entity/AgentSiteMapping.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class AgentSiteMapping extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}