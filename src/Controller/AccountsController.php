<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class AccountsController extends AppController {
	
	public $__limit = 10;
	
	public function initialize()
	{
		parent::initialize();
		$this->loadModel("Accounts");
		$this->loadModel("Bulletins");
		$this->loadModel("Top10s");
		$this->loadModel("TrboTop10s");
		$this->loadModel("Companies");
		$this->loadModel("ViewCompanies");
		$this->loadModel("ViewAgents");
		$this->loadModel("AgentSiteMappings");
		
		$this->loadComponent('Paginator');
	}
    
    public function login() {
    	/*
    	 * some test moves
    	 */
    	$someBulletins = $this->Bulletins->find('all', [
    		'limit' => 5
    	]);
    	$this->set("someBulletins", $someBulletins);
    	
    	// load the Captcha component and set its parameter
    	$this->loadComponent('CakeCaptcha.Captcha', [
    		'captchaConfig' => 'LoginCaptcha'
    	]);
    	
    	if ($this->request->is('post')) {
    		// validate the user-entered Captcha code
    		$isHuman = captcha_validate($this->request->data['CaptchaCode']);
    		
    		// clear previous user input, since each Captcha code can only be validated once
    		unset($this->request->data['CaptchaCode']);
    		
    		if ($isHuman) {
	    		$account = $this->Auth->identify();   		
	    		if ($account) {
	    			$this->Auth->setUser($account);
	    			return $this->redirect(["controller" => "accounts", "action" => "index"]);
	    		}
	    		$this->Flash->set('Your username or password is incorrect.', ['element' => 'error']);
    		} else {
    			$this->Flash->set('Wrong validation colde, please try again.', ['element' => 'error']);
    		}
    	}
    }
    
    public function index($id = null, $b_id = null) {
    	$userinfo = $this->Auth->user();
		/*try to archive the bulletin*/
		if ($id == -1 && $userinfo['role'] == 0) {
			$bulletinsTable = TableRegistry::get("Bulletins");
			$curBulletin = $bulletinsTable->get($b_id);
			$bulletin = $bulletinsTable->newEntity();
			$bulletin->archdate = date('Y-m-d h:i:s');
			$bulletin->info = $curBulletin->info;
			
			if ($bulletinsTable->save($bulletin)) {
				$this->Flash->set("Bulletin archived.", ['element' => 'success']);
				$this->redirect(['controller' => 'accounts', 'action' => 'index']);
			} else {
				$this->Flash->set("No current bulletin exists.", ['element' => 'error']);
			}
		}
		/*prepare the historical bulletins*/
		/*
		$archdata = $this->Bulletins->find('all',
			array(
				'fields' => array('id', 'title', 'archdate'),
				'conditions' => array('archdate not' => null),
				'order' => array('archdate desc')
			)
		);*/
		$archdata = $this->Bulletins->find()
			->select(['id', 'title', 'archdate'])
			->where(['archdate is not' => null])
			->order(['archdate' => 'desc'])
			->all();
		$this->set(compact('archdata'));
		/*prepare the ALERTS for the current logged-in user*/
		
		$data = '';
		if ($id == null) {
            /*
			$info = $this->Bulletins->find('first',
				array(
					'fields' => array('info'),
					'conditions' => array('archdate' => null)
				)
			);*/
            $data = $this->Bulletins->find()
				->select(['id', 'info'])
				->where(['archdate is not' => null])
				->first();
		} else {
            /*
			$info = $this->Bulletin->find('first',
				array(
					'fields' => array('info'),
					'conditions' => array('id' => $id)
				)
			);*/
            $data = $this->Bulletins->find()
                ->select(['id', 'info'])
                ->where(['id' => $id])
                ->first();
		}
		$this->set('topnotes',  $data);
		if ($userinfo['role'] == 0) {//means an administrator
			$this->set('notes', '');//set the additional notes here
		} else if ($userinfo['role'] == 1) {//means a company
			/*
			$cominfo = $this->Company->find('first',
				array(
					'fields' => array('agentnotes'),
					'conditions' => array('id' => $this->Auth->user('Account.id'))
				)
			);
			*/
			$cominfo = $this->Companies->find()
				->select(['agentnotes'])
				->where(['id' => $userinfo['id']])
				->first();
			$this->set('notes', '');//set the additional notes here
		} else {//means an agent
			/*
			$aginfo = $this->Agent->find('first',
				array(
					'fields' => array('companyid'),
					'conditions' => array('id' => $this->Auth->user('Account.id'))
				)
			);*/
			$aginfo = $this->Agents->find()
				->select(['companyid'])
				->where(['id' => $userinfo['id']])
				->first();
			/*
			$cominfo = $this->Company->find('first',
				array(
					'fileds' => array('agentnotes'),
					'conditions' => array('id' => $aginfo['Agent']['companyid'])
				)
			);*/
			$cominfo = $this->Companies->find()
				->select(['agentnotes'])
				->where(['id' => $aginfo->companyid])
				->first();
			$this->set('notes', '<font size="3"><b>Office news&nbsp;&nbsp;</b></font>' 
				. $cominfo['Company']['agentnotes']);
		}
		
		/*
		 * for the "selling contestants" stuff
		 */
		//avoid those data which are not in types
		$conds['startdate'] = '0000-00-00';
		$conds['enddate'] = date('Y-m-d');
		/*
        $rs = $this->Top10->find('all',
			array(
				'conditions' => array('flag' => 0),
				'order' => 'sales desc'
			)
		);*/
        $rs = $this->Top10s->find()
            ->where(['flag' => 0])
            ->order(['sales' => 'desc'])
            ->all();
		$this->set(compact('rs'));
		$weekend = date("Y-m-d", strtotime(date('Y-m-d') . " Saturday"));
		$weekstart = date("Y-m-d", strtotime($weekend . " - 6 days"));
		$curbiweek = __getCurBiweek();
		$curbiweekse = explode(",", $curbiweek);
		$biweekstart = $curbiweekse[0];
		$biweekend = $curbiweekse[1];
		$conds['startdate'] = $weekstart;
		$conds['enddate'] = $weekend;
		$weekrs = $this->Top10s->find('all',
			array(
				'conditions' => array('flag' => 1),
				'order' => 'sales desc'
			)
		);
		$conds['startdate'] = $biweekstart;
		$conds['enddate'] = $biweekend;
		/*
		$biweekrs = $this->Top10->find('all',
			array(
				'conditions' => array('flag' => 3),
				'order' => 'sales desc'
			)
		);
		$biweekrs0 = $this->Top10->find('all',
			array(
				'conditions' => array('flag' => 4),
				'order' => 'sales desc'
			)
		);*/
		$biweekrs = $this->Top10s->find()
			->where(['flag' => 3])
			->order(['sales' => 'desc'])
			->all();
		$biweekrs0 = $this->Top10s->find()
			->where(['flag' => 4])
			->order(['sales' => 'desc'])
			->all();
		$this->set(compact('weekrs'));
		$this->set(compact('weekstart'));
		$this->set(compact('weekend'));
		$this->set(compact('biweekrs'));
		$this->set(compact('biweekrs0'));
		$this->set(compact('biweekstart'));
		$this->set(compact('biweekend'));
		
		/*
		 * trials and bonus top 10 for new style (2 steps) sales
		 */
		/*
		$trbors = $this->TrboTop10->find('all',
			array(
				'conditions' => array('flag' => 0),
				'order' => 'sales desc'
			)
		);
		$trboweekrs = $this->TrboTop10->find('all',
			array(
				'conditions' => array('flag' => 1),
				'order' => 'sales desc'
			)
		);*/
		$trbors = $this->TrboTop10s->find()
			->where(['flag' => 0])
			->order(['sales' => 'desc'])
			->all();
		$trboweekrs = $this->TrboTop10s->find()
			->where(['flag' => 1])
			->order(['sales' => 'desc'])
			->all();
		$this->set(compact('trbors'));
		$this->set(compact('trboweekrs'));
    }
    
    public function logout(){
    	$this->request->session()->destroy();
    	return $this->redirect($this->Auth->logout());
    }
    
    function pass() {
    	$this->autoRender = false;
    	$this->request->session()->write(['switch_pass' => 1]);
    }
    
    public function updnews() {
    	if (empty($this->request->getData())) {
    		/*prepare the notes for the current logged in user*/
    		/*
    		$info = $this->Bulletin->find('first',
    				array(
    						'fields' => array('id', 'info'),
    						'conditions' => array('archdate' => null)
    				)
    		);*/
    		$data = $this->Bulletins->find()
    			->select(['id', 'info'])
    			->where(['archdate is not' => null])
    			->first();
    		$this->set(compact('data'));
    	} else {
    		$id = $this->request->getData('id');
    		$bulletinsTable = TableRegistry::get("Bulletins");
    		$bulletin = $bulletinsTable->get($id);
    		$bulletin->info = $this->request->getData('info');
    		
    		if ($bulletinsTable->save($bulletin)) {
    			$this->Flash->set("NEWS updated", ['element' => 'success']);
    			$this->redirect(['controller' => 'accounts', 'action' => 'index']);
    		} else {
    			$this->Flash->set("Something wrong, please contact your administrator.", ['element' => 'error']);
    		}
    	}
    }
    
    function updalerts() {
    	/*prepare the notes for the current logged in user*/
    	$adminsTable = TableRegistry::get("Admins");
    	$admin = $adminsTable->get(1); //HARD CODE: we put the alerts into here
    	$this->set(compact('admin'));
    	if (empty($this->request->getData())) {
    		//$this->Admin->id = 1;//HARD CODE: we put the alerts into here
    		//$this->request->data = $this->Admin->read();
    		if (empty($admin)) {
    			$this->Flash->set('Please create your first admin account, so we could continue the alerts setup.');
    			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
    		}
    	} else {
    		$admin->notes = $this->request->getData("notes");
    		if ($adminsTable->save($admin)) {
    			$this->Flash->set('Alerts updated.', ['element' => 'success']);
    			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
    		} else {
    			$this->Flash->set("Something wrong, please contact your administrator.", ['element' => 'error']);
    		}
    	}
    }
    
    function lstcompanies($id = null) {
    	/*prepare for the searching part*/
    	if (!empty($this->request->getData())) {
    		$username = $this->request->getData('username');
    		if (strlen($username) == 0 && empty($username)) {
    			$conditions = ['1' => '1'];
    		} else {
    			$conditions = [
    				'username like' => ('%' . $username . '%')
    			];
    		}
    	} else {
    		if ($id == null || !is_numeric($id)) {
    			if ($this->request->session()->check('conditions_com')) {
    				$conditions = $this->request->session()->read('conditions_com');
    			} else {
    				$conditions = ['1' => '1'];
    			}
    		} else {
    			if ($id != -1) {
    				$conditions = ['companyid' => $id];
    			} else {//"-1" is specially for the administrator
    				$conditions = ['1' => '1'];
    			}
    		}
    	}
    
    	$this->request->session()->write(['conditions_com' => $conditions]);

    	$this->set('status', $this->Accounts->status);
    	$this->set('online', $this->Accounts->online);

    	$this->paginate = [
    		'limit' => $this->__limit,
    		'order' => ['regtime desc']
    	];
    	$query = $this->ViewCompanies->find()
    		->where($conditions);
    	$this->set('rs',
    		$this->paginate($query)
    	);
    }
    
    function lstagents($id = null) {
    	$userinfo = $this->Auth->user();
    	$coms = [];
    	if ($userinfo['role'] <= 1) {
    		/*$coms = $this->ViewCompany->find('list',
    				array(
    						'fields' => array('companyid', 'officename'),
    						'conditions' => array('status >= 0'),
    						'order' => 'officename'
    				)
    		);*/
    		$coms = $this->ViewCompanies->find()
    			->where(['status >=' => 0])
    			->order(['officename'])
    			->all()
    			->combine('companyid', 'officename')
    			->toArray();
    	}
    	//$coms = ['0' => 'All'] + $coms;
    	//$coms = json_decode(json_encode($coms), true);
    	$coms[0] = 'All';
    	asort($coms);
    	$this->set(compact('coms'));
    
    	/*$sites = $this->Site->find('list',
    			array(
    					'fields' => array('id', 'sitename'),
    					'conditions' => array('status' => 1),
    					'order' => 'sitename'
    			)
    	);*/
    	$sites = $this->Sites->find()
    		->where(['status' => 1])
    		->order(['sitename'])
    		->all()
    		->combine('id', 'sitename')
    		->toArray();
    	//$sites = ['-1' => '-----------------------'] + $sites;
    	//$sites = json_decode(json_encode($sites), true);
    	$sites['-1'] = '-----------------------';
    	asort($sites);
    	$this->set(compact('sites'));
    
    	$this->set('status', $this->Accounts->status);
    	$this->set('online', $this->Accounts->online);
    	$this->set('limit', $this->__limit);
    
    	/*prepare for the searching part*/
    	if (!empty($this->request->getData())) {// if there are any POST data
    		$conditions = [
    			'username like' => ('%' . trim($this->request->getData('ViewAgent.username')) . '%'),
    			'lower(ag1stname) like' => ('%' . strtolower(trim($this->request->getData('ViewAgent.ag1stname'))) . '%'),
    			'lower(aglastname) like' => ('%' . strtolower(trim($this->request->getData('ViewAgent.aglastname'))) . '%'),
    			'lower(email) like' => ('%' . strtolower(trim($this->request->getData('ViewAgent.email'))) . '%')
    		];
    		if ($userinfo['role'] == 0) {
    			$companyid = $this->request->getData('Company.id');
    			if ($companyid != 0) {
    				$conditions['companyid IN'] = [-1, $companyid];
    			}
    		} else if ($userinfo['role'] == 1){
    			$companyid = $this->request->getData('ViewAgent.companyid');
    			$conditions['companyid IN'] = [-2, $companyid];
    		}
    		$status = $this->request->getData('ViewAgent.status');
    		if ($status != -1) {
    			$conditions['status'] = $status;
    		}
    		$campaignid = trim($this->request->getData('AgentSiteMapping.campaignid'));
    		if (!empty($campaignid)) {
    			/*$ags = $this->AgentSiteMapping->find('list',
    					array(
    							'fields' => array('id', 'agentid'),
    							'conditions' => array(
    									'campaignid like' => ('%' . $campaignid . '%')
    							)
    					)
    			);*/
    			$ags = $this->AgentSiteMappings->find()
    				->where(['campaignid like' => '%' . $campaignid . '%'])
    				->all()
    				->combine('id', 'agentid')
    				->toArray();
    			$ags = array_unique($ags);
    			$conditions['id IN'] = $ags;
    		}
    		$exsite = $this->request->getData('SiteExcluding.siteid');
    		if ($exsite != -1) {
    			/*$exags = $this->SiteExcluding->find('list',
    					array(
    							'fields' => array('id', 'agentid'),
    							'conditions' => array('siteid' => $exsite)
    					)
    			);*/
    			$exags = $this->SiteExcludings->find()
    				->where(['siteid' => $exsite])
    				->all()
    				->combine('id', 'agentid')
    				->toArray();
    			$exags = array_unique($exags);
    			if (array_key_exists('id IN', $conditions)) {
    				$conditions['id IN'] = array_intersect($conditions['id IN'], $exags);
    			} else {
    				$conditions['id IN'] = $exags;
    			}
    		}
    	} else {
    		if ($id == null || !is_numeric($id)) {
    			if ($this->request->session()->check('conditions_ag')) {
    				$conditions = $this->request->session()->read('conditions_ag');
    			} else {
    				$conditions = ['1' => '1'];
    			}
    		} else {
    			if ($id != -1) {
    				$arr = [-3, $id];//!!!important!!!we must do this to ensure that the "order by" in MYSQL could work normally but not being misunderstanding
    				$conditions = ['companyid IN' => $arr];
    			} else {//"-1" is specially for the administrator
    				$conditions = ['1' => '1'];
    			}
    		}
    	}
    
    	$this->set(compact('conditions'));
    	$this->request->session()->write('conditions_ag', $conditions);
    
    	/*$this->paginate = array(
    		'ViewAgent' => array(
    			'conditions' => $conditions,
    			'limit' => $this->__limit,
    			'order' => 'username4m'
    		)
    	);*/
    	$this->paginate = [
    		'limit' => $this->__limit,
    		'order' => ['username4m']
    	];
    	$query = $this->ViewAgents->find()
    		->where($conditions)
    		->where([
    			'companyid IN' => array_keys($coms),
    			'status >=' => 0
    		]);
    	$this->set('rs',
    		$this->paginate($query)
    	);
    }
}
?>
