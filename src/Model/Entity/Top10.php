<?php
// src/Model/Entity/Top10.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Top10 extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}
