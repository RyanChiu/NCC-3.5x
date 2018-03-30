<?php
// src/Model/Entity/Bulletin.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Bulletin extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}