<?php
// src/Model/Entity/ViewCompany.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class ViewCompany extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}