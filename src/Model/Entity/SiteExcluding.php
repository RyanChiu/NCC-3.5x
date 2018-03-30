<?php
// src/Model/Entity/SiteExcluding.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class SiteExcluding extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
}