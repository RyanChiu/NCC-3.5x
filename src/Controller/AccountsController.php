<?php
namespace App\Controller;

use App\Controller\AppController;

class AccountsController extends AppController {
    
    public function login() {
    	/*
    	 * some test moves
    	 */
    	$someAccounts = $this->Accounts->find('all', [
    		'limit' => 5,
    		'order' => 'Accounts.role desc'
    	]);
    	$this->set("someAccounts", $someAccounts);
    	
    	if ($this->request->is('post')) {
    		$user = $this->Auth->identify();
    		if ($user) {
    			$this->Auth->setUser($user);
    			return $this->redirect(["controller" => "NCC", "action" => "index"]);
    		}
    		$this->Flash->error('Your username or password is incorrect.');
    	}
    }
    
    public function logout(){
    	return $this->redirect($this->Auth->logout());
    }
}
?>
