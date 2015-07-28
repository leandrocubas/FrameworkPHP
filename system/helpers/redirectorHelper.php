<?php 

	/**
	* 
	*/
	class RedirectorHelper 
	{
		protected $parameters = array();


		protected function go( $data ){
			header("LOCATION: /".$data);
		}

		public function setUrlParameter($name, $value){
			$this->parameters[$name] = $value;
			return $this;
		}

		public function goToController($controller){

			$this->go("mvc-ouvidoria/".$controller. '/index/' .$this->getUrlParameters);
		}

		protected function getUrlParameters(){
			$parms= "";
			foreach ($this->parameters as $name => $value) {
				$parms.= $name . '/'.$value.'/';
			}
			return $parms;
		}

		public function goToAction($action){
			$this->go("mvc-ouvidoria/".$this->getCurrentController(). '/'.$action .'/' .$this->getUrlParameters() );
		}

		public function goToControllerAction($controller, $action){
			$this->go("mvc-ouvidoria/".$controller. '/'.$action .'/' .$this->getUrlParameters() );
		}

		public function goToIndex(){
			$this->go('mvc-ouvidoria/index');
		}

		public function goToUrl($url){
			header("LOCATION: ".$url);
		}

		public function getCurrentController(){
			global $start;
			return $start->controller;
		}

		public function getCurrentAction(){
			global $start;
			return $start->action;
		}


	}