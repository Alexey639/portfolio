<?php
class FrontController {
	protected $_controller, $_action, $_params, $_body;
	static $_instance;

	public static function getInstance() {
		if(!(self::$_instance instanceof self)) 
			self::$_instance = new self();
		return self::$_instance;
	}
	private function __construct(){
        $request = parse_url($_SERVER['REQUEST_URI']);
        $request = $request['path'];
		$splits = explode('/', trim($request,'/'));
		//Controller
		$this->_controller = !empty($splits[0]) ? ucfirst($splits[0]).'Controller' : 'IndexController';
		//Action
		$this->_action = !empty($splits[1]) ? $splits[1].'Action' : 'indexAction';
		//���� �� ��������� � �� ��������?
		if(!empty($splits[2])){
			$keys = $values = array();
				for($i=2, $cnt = count($splits); $i<$cnt; $i++){
					if($i % 2 == 0){
						//׸���� = ���� (��������)
						$keys[] = $splits[$i];
					}else{
						//�������� ���������;
						$values[] = $splits[$i];
					}
				}
			$this->_params = array_combine($keys, $values);

		}
		$this->db =  $this->getDb();
        $this->is_ajax = $this->is_ajax();
	}

    public  function getDb()
    {
        if($this->db)
            return  $this->db;
        $dbfile = realpath(ROOT.'/db/db.sqlite3');
        if(!$dbfile)
            throw new Exception('�� �� �������');
      return    new PDO('sqlite:'.$dbfile);



	}
	public function route() {
		if(class_exists($this->getController())) {
			$rc = new ReflectionClass($this->getController());
			if($rc->implementsInterface('IController')) {
				if($rc->hasMethod($this->getAction())) {
					$controller = $rc->newInstance();
					$method = $rc->getMethod($this->getAction());
					$method->invoke($controller);
				} else {
					throw new Exception("Action");
				}
			} else {
				throw new Exception("Interface");
			}
		} else {
			throw new Exception("Controller");
		}
	}
	public function getParams() {
		return $this->_params;
	}
	public function getController() {
		return $this->_controller;
	}
	public function getAction() {
		return $this->_action;
	}
	public function getBody() {
		return $this->_body;
	}
	public function setBody($body) {
		$this->_body = $body;
	}

    private function is_ajax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }
}	