<?php 

	/**
	* Index que controla o Login e cadastro de acesso ao sistema
	* Desenvolvido por Leandro Cubas de Macedo
	*/
	
 class Index extends Controller{

 	private $auth, $db, $dados;
 		
 		public function init(){			
 			$this->auth = new authHelper();
 			$redirect= new RedirectorHelper();
 			$this->db = new IndexModel();
 		}

 		public function Index_action(){
 			$redirect= new RedirectorHelper();
 			$redirect->goToAction('login');
 		}

 		public function login(){
 			$redirect= new RedirectorHelper();
 			if($this->getParams('acao')){
 				if(empty($_POST['email']) and empty($_POST['senha']))
	 				die($redirect->goToControllerAction('index', 'login')); 
	 				// se estiver vazio os campos direciona para login novamente
 				$this->auth->setTableName('usuario')
 				->setUserColumn('email')
 				->setPassColumn('password') 				
 				->setUser($_POST['email'])
 				->setPass(md5($_POST['senha']))
 				->setLoginControllerAction('index', 'index') 
 				->login();
 			}
 			$sessionHelper = new SessionHelper();
 			if($sessionHelper->checkSession("userAuth")){
 				$nome = $_SESSION['userData']['nome'];
 				$redirect->goToControllerAction('sistema', 'index');// direciona para pagina após logado
 			}
 			else 
 				$this->view('login');
 		}

 		public function cadastrar(){
 			$redirect= new RedirectorHelper();
	 		if($this->getParams('acao')){
	 			if(empty($_POST['senha']) || empty($_POST['email'])
	 			 || empty($_POST['msisdn']) || empty($_POST['nome']))
	 				die($redirect->goToControllerAction('index', 'cadastrar'));
	 			$campos =array("nome", "telefone", "email", "password");
	 			$valores=array($_POST['nome'], $_POST['msisdn'], $_POST['email'], md5($_POST['senha']));
	 			$this->dados= array_combine($campos, $valores);
	 			$this->db->cadastrar($this->dados);
	 		}
	 		$sessionHelper = new SessionHelper();
 			if($sessionHelper->checkSession("userAuth")){
 				$nome = $_SESSION['userData']['nome'];
 				$this->auth->ConsultaPermissao($nome); // direciona se logado
 			}

	 		else{
 				$this->view('login');
 				if($this->getParams('sucesso')=='y'){
	 				echo'<div class="alert alert-success" style="margin:0 auto; width:960px; ">
	    			<a href="#" class="close" data-dismiss="alert">&times;</a>
				    <strong>Parabéns!</strong> Seus dados foram gravados com sucesso.
					</div>';
	 			}
	 		}
 		}	
 		public function logout(){
 			$this->auth->setLogoutControllerAction('index', 'index')->logout();
 		}
 	}