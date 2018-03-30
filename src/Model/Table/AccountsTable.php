<?php
// src/Model/Table/AccountsTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
class AccountsTable extends Table {
	public $status = array('-1' => 'Unapproved', '0' => 'Suspended', '1' => 'Activated', '-2' => 'Hidden');
	public $online = array('0' => 'offline', '1' => 'online');
	
	public function initialize(array $config) {
		
	}
}