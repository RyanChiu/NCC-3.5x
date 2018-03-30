<?php
// src/Model/Entity/MustRead.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class MustRead extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}