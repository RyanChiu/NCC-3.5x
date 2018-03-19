<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

require_once ROOT . DS . "src" . DS . "Driver" . DS . "zrkits" . DS . "extrakits.inc.php";

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
	protected $__locatekey = 'GTYHNBvfr4567ujm';
	
    public function initialize()
    {
        parent::initialize();
        
        $this->loadModel("Agents");
        $this->loadModel("Admins");
        $this->loadModel("Sites");
        $this->loadModel("SiteExcludings");
        $this->loadModel("TerminalCookies");
        $this->loadModel("MustReads");

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
        
        $this->loadComponent ( 'Auth', [ 
			'authenticate' => [ 
				'Form' => [ 
					'userModel' => 'Accounts',
					'fields' => [ 
						'username' => 'username',
						'password' => 'password' 
					],
					'passwordHasher' => [
						'className' => 'Legacy',
					]
				] 
			],
			'loginAction' => [ 
				'controller' => 'Accounts',
				'action' => 'login' 
			],
			// If unauthorized, return them to page they were just on
			'unauthorizedRedirect' => $this->referer()
        ]);
        // Allow the display action so our PagesController
        // continues to work. Also enable the read only actions.
        $this->Auth->allow(['accounts/login', 'accounts/logout', 'accounts/index']);
    }
    
    public function beforeFilter(Event $event) {
    	$excludedsites = array();
    	if ($this->Auth->user('id') && $this->Auth->user('role') == 2) {
    		/*$aginfo = $this->Agent->find('first',
    			array('conditions' => array('id' => $userinfo->id))
    		);*/
    		$aginfo = $this->Agents->find()
    			->where(['id' => $this->Auth->user('id')])
    			->first();
    		/*$excludedsites = $this->SiteExcluding->find('list',
    				array(
    						'fields' => array('id', 'siteid'),
    						'conditions' => array(
    								'OR' => array(
    										'companyid' => array(-1, $aginfo['Agent']['companyid']),
    										'agentid' => $this->curuser['id']
    								)
    						)
    				)
    		);*/
    		$excludedsites = $this->SiteExcludings->find()
    			->where(['OR' => ['companyid' => $aginfo->companyid, 'agentid' => $this->Auth->user('id')]])
    			->all()
    			->combine('id', 'siteid');
    		$excludedsites = json_decode(json_encode($excludedsites), true);
    		$excludedsites = array_unique($excludedsites);
    		/*$excludedsites = $this->Site->find('list',
    				array(
    						'fields' => array('id', 'sitename'),
    						'conditions' => array(
    								'id' => $excludedsites
    						)
    				)
    		);*/
    		$excludedsites = $this->Sites->find()
    			->where(['id in' => array_values($excludedsites)])
    			->all()
    			->combine('id', 'sitename');
    		$excludedsites = json_decode(json_encode($excludedsites), true);	
    		/*
    		 * prepare the "agent must read" part
    		 */
    		/*$mustread = $this->MustRead->find("first",
    				array(
    						'conditions' => array(
    								"accountid" => $this->curuser["id"]
    						),
    						'order' => "time desc",
    						'limit' => "1"
    				)
    		);*/
    		$mustread = $this->MustReads->find()
    			->where(['accountid' => $this->Auth->user('id')])
    			->order(['time' => 'desc'])
    			->first();
    		if (!empty($mustread)) {
    			$this->set("mustread", $mustread->content);
    		}
    	}
    	$this->set(compact("excludedsites"));
    	
    	//$alerts = $this->Admin->field('notes', array('id' => 1));//HARD CODE: we put alerts here
    	$alerts = $this->Admins->find()
    		->select(['notes'])
    		->where(['id' => 1])
    		->first();
    	$this->set(compact('alerts'));
    	
    	/*
    	 * setting cookies part--start
    	 */
    	$locatecookie = __crucify_cookie(LOCATE_COOKIE_NAME);
    	if ($locatecookie == null) {
    		$locatecookie = __crucify_cookie(LOCATE_COOKIE_NAME, md5($this->__locatekey . time()));
    	}
    	
    	$t = microtime(true);
    	$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
    	$d = new \DateTime( date('Y-m-d H:i:s.' . $micro,$t) );
    	$data = $this->TerminalCookies->newEntity();
    	$r = $this->TerminalCookies->find()
    		->where(['cookie' => $locatecookie])
    		->first();
    	if (empty($r)) {
    		if ($locatecookie == null) {
    			$r = $this->TerminalCookies->newEntity();
    			$r->id = -1;
    			$r->cookie = '-';
    			$r->time = null;
    		} else {
    			$data->time = $d->format("Y-m-d H:i:s.u");
    			$data->cookie = $locatecookie;
    			if ($this->TerminalCookies->save($data)) {
	    			$r = $data;
    			}
    		}
    	}
    	$this->request->session()->write(['terminalcookie' => $r]);
    	/*
    	 * setting cookies part--end
    	 */
    	
    	parent::beforeFilter($event);
    }
    
    public function beforeRender(Event $event) {
    	$this->set('Auth', $this->Auth);
    	
    	parent::beforeRender($event);
    }
    /**
     *  bootstrap helper
     */
    public $helpers = [
    		'Form' => [
    				'className' => 'Bootstrap.Form'
    		],
    		'Html' => [
    				'className' => 'Bootstrap.Html'
    		],
    		'Modal' => [
    				'className' => 'Bootstrap.Modal'
    		],
    		'Navbar' => [
    				'className' => 'Bootstrap.Navbar',
        			'autoActiveLink' => true
    		],
    		'Paginator' => [
    				'className' => 'Bootstrap.Paginator'
    		],
    		'Panel' => [
    				'className' => 'Bootstrap.Panel'
    		]
    ];
}
