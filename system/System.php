<?php 

	/**
	* 
	*/
	class System {
		private $url;
		private $_explode;
		public $controller;
		public $action;
		public $params;

	public function __construct()
		{
			$this->setURL();
			$this->setExplode();
			$this->setController();
			$this->setAction();
			$this->setParams();
		}

		private function setURL(){
			 $_GET['url'] = (isset( $_GET['url']) ?  $_GET['url'] : 'index/index_action');
			 $this->url = $_GET['url'];
		}

		private function setExplode(){
			$this->_explode = explode('/', $this->url);
		}

		private function setController(){
			$this->controller = $this->_explode[0];
		}

		private function setAction(){
			$ac = (!isset($this->_explode[1]) || $this->_explode[1] == null || $this->_explode[1] == 'index' ? "index_action" : $this->_explode[1]);
			$this->action = $ac;
		}

		private function setParams(){
			unset($this->_explode[0],$this->_explode[1]);
			if(end($this->_explode) == null )
				array_pop($this->_explode);

			$i =0 ;
			if(!empty($this->_explode)){
				foreach ($this->_explode as $val) {
					if($i % 2 == 0){
						$ind[] = $val;
					}else {
						$value[] = $val;
					}
					$i++;
				}

			}else{
				$ind = array();
				$value = array();
			}
			if(empty($value))
				$value = array();
			if(count($ind) == count($value) && !empty($ind) && !empty($value))
				$this->params = array_combine($ind, $value);
			else
				$this->params = array();
		}

		public function getParams( $name = null){
		  if($name != null)
			if( array_key_exists($name, $this->params))
				return  $this->params[$name];
			else
				return false;
			else
				return  $this->params;
		}

		public function run(){
			$controller_path = CONTROLLERS . $this->controller. 'Controller.php';
			if( !file_exists( $controller_path ) )
				die("Houve um erro. o Controller não existe. !");
			require_once ( $controller_path );
			$app= new $this->controller();
				if ( !method_exists($app, $this->action) )
					die(require_once('error_pages/404.phtml'));
			$action = $this->action;
			$app->init();
			$app->$action();
		}
	}