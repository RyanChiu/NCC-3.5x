<?php
// src/Model/Entity/Site.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Site extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
	public $status = ['0' => 'suspended', '1' => 'activated'];
	
	public $types = [
		'WEBCAMS' => 'WEBCAMS', 
		'ADULT' => 'ADULT', 
		'DATING' => 'DATING', 
		'CASINO' => 'CASINO'
	];
}