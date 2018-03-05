<?php
// src/Model/Entity/Company.php
namespace App\Model\Entity;
use Cake\ORM\Entity;
class Company extends Entity {
	protected $_accessible = [
		'*' => true,
		'id' => false,
	];
	
	public $payouttype = [
		'0' => 'Pay by Check', 
		'1' => 'Pay by Webstern Union', 
		'2' => 'Pay by Wire'
	];
}