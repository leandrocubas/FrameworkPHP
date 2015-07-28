<?php 

	/**
	* Index que controla o Login e cadastro de acesso ao sistema
	* Desenvolvido por Leandro Cubas de Macedo
	*/
	
 class Admin extends Controller{

 	private $auth, $db, $dados, $redirect, $iduser, $sessionHelper, $protocoloBd;
 		
 		public function init(){			
 			$this->auth = new authHelper();
 			$this->redirect= new RedirectorHelper();
 			$this->db = new AdminModel();
 			$this->sessionHelper = new SessionHelper();
 			$this->protocoloBd = new ProtocoloModel();
 			
 		}

 		public function Index_action(){
 			if($this->sessionHelper->checkSession("userAuth")){
 				$nome = $_SESSION['userData']['nome'];
 				$permissao = $this->auth->ValidaPermissao($this->auth->RetornaNome());
	 			if($permissao)
 				$this->redirect->goToAction('PortalAdmin');
 				 else $this->redirect->goToControllerAction('sistema', 'index');
 			}
 			else
 				$this->view('AdminLogin');
 		}

 		public function login(){
 				if($this->getParams('acao')){
	 				if(empty($_POST['email']) and empty($_POST['password']))
		 				die($this->redirect->goToControllerAction('admin', 'index')); 
		 				// se estiver vazio os campos direciona para login novamente
	 				$this->auth->setTableName('admin')
	 				->setUserColumn('email')
	 				->setPassColumn('password') 				
	 				->setUser($_POST['email'])
	 				->setPass(md5($_POST['password']))
	 				->setLoginControllerAction('admin', 'PortalAdmin') 
	 				->login();
 			} 
 			else  $this->redirect->goToControllerAction('sistema', 'index');// direciona para pagina caso nao tenha vindo do Form	
	 			
 		}

 		public function PortalAdmin(){
 			if($this->sessionHelper->checkSession("userAuth")){
 				$nome = $_SESSION['userData']['nome'];
 				$permissao = $this->auth->ValidaPermissao($this->auth->RetornaNome());
	 			if($permissao){
	 				$tabelas = "protocolo a, manifestacao b, usuario c";
	 				$where = "b.idmanifestacao = a.manifestacao_idmanifestacao and b.idusuario = c.idusuario";
	 				$dados['lista_protocolos'] = $this->protocoloBd->ListaProtocolo($where, $tabelas, null, null, "data_geracao desc");
	 				if($this->verificaSucessoURL()){
	 					$result= $this->verificaSucessoURL();
	 					echo $result;
	 				}
	 				if($this->verificaErroURL()){
		 				$result = $this->verificaErroURL();
		 				echo $result;
		 			}
	 				$this->view('PortalAdmin',$dados);

	 			}else $this->redirect->goToControllerAction('sistema', 'index');
 			} 
 			else $this->redirect->goToControllerAction('sistema', 'index');// direciona para pagina após logado
 			
 		}

 		public function verificaErroURL(){
 			if($this->getParams('status')=='error') {
		 		$result ='<div class="alert alert-danger" style="margin:0 auto; width:960px; margin-top:60px;z-index:99; margin-bottom:-60px;">
		    			<a href="#" class="close" data-dismiss="alert">&times;</a>
					      <span class="glyphicon glyphicon-hand-right"></span>
					      <strong> OPS recebemos um ERRO!</strong>
					      <hr class="message-inner-separator"><p>
					       Seus dados não puderam ser salvos, Tente Novamente.
						</div>';
				return $result;
 			}else 
 			return false;
					 
 		}
 		public function verificaSucessoURL(){
 			if($this->getParams('status')=='sucesso') {
		 		$result ='<div class="alert alert-success" style="margin:0 auto; width:960px; margin-top:60px;z-index:99; margin-bottom:-60px;">
		    			<a href="#" class="close" data-dismiss="alert" aria-hidden= "true">&times;</a>
					    <span class="glyphicon glyphicon-ok"></span>
					    <strong> Parabens!</strong>
					    <hr class="message-inner-separator"><p>
					     Seus dados foram Salvos com sucesso.
						</div>';
				return $result;
 			}else 
 			return false;
					 
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
 			$this->auth->setLogoutControllerAction('admin', 'index')->logout();
 		}

 		public function AlteraStatusProtocolo(){
 			$permissao = $this->auth->ValidaPermissao($this->auth->RetornaNome());
	 			if($permissao){
	 				if($this->getParams('id')){
	 					$data = date("Y-m-d");
	 					$status = $_POST['status'];
	 					if($status == 'Finalizado'){
	 						$id = $this->getParams('id');
		 					$campos =Array("status = :status, data_conclusao = :data_conclusao");
		 					$valores=array($status, $id, $data);
		 					$dados = array(":status", ":idprotocolo", ":data_conclusao");
		 					$where = "idprotocolo = :idprotocolo";
		 						if($this->protocoloBd->AtualizaProtocolo($campos,$valores,$where, $dados))
		 							$this->redirect->goToAction('PortalAdmin/status/sucesso');
		 						else $this->redirect->goToAction('PortalAdmin/status/error');
	 					}
	 					$id = $this->getParams('id');
	 					$campos =Array("status = :status");
	 					$valores=array($status, $id);
	 					$dados = array(":status", ":idprotocolo");
	 					$where = "idprotocolo = :idprotocolo";
	 						if($this->protocoloBd->AtualizaProtocolo($campos,$valores,$where, $dados))
	 							$this->redirect->goToAction('PortalAdmin/status/sucesso');
	 						else $this->redirect->goToAction('PortalAdmin/status/error');
	 				}

	 			}else $this->redirect->goToControllerAction('sistema', 'index');
 		}

 		public function export(){
 			$permissao = $this->auth->ValidaPermissao($this->auth->RetornaNome());
	 			if($permissao){
	 				if($this->getParams('relatorio')){
	 					switch ($this->getParams('relatorio')) {
	 						case 'tudo':
				 				$tabelas = "usuario u, protocolo p, manifestacao m";
				 				$where = "u.idusuario = m.idusuario and m.idmanifestacao = p.manifestacao_idmanifestacao";
				 				$dados = $this->protocoloBd->ListaProtocolo($where, $tabelas);
				 				$campos = array('idusuario','nome','email', 'idprotocolo', 'data_geracao', 'data_conclusao', 'protocolo', 'status', 'idmanifestacao', 'manifestacao', 'setor_unidade', 'vinculo_manifestante', 'outros');
	 							$html = $this->GeraHtml($campos, $dados, 'Relatorio_Completo');
	 							break;
	 						case 'protocolos':
	 							$dados = $this->protocoloBd->ListaProtocolo(null, 'protocolo');
	 							$campos = array('idprotocolo','data_geracao','data_conclusao', 'manifestacao_idmanifestacao', 'protocolo', 'status');
	 							$html = $this->GeraHtml($campos, $dados, 'Relatorio_Protocolos');
	 							break;
	 						case 'manifestacao':
	 							$manifestacaoBd = new SistemaModel();
	 							$dados = $manifestacaoBd->ListaManifestacao(null, 'manifestacao');
	 							$campos = array('idmanifestacao','idusuario','manifestacao', 'setor_unidade', 'sigilo', 'tipo_manifestacao', 'vinculo_manifestante', 'outros', );
	 							$html = $this->GeraHtml($campos, $dados, 'Relatorio_Manifestacoes');
	 							break;
	 						case 'users':
	 							$user = new IndexModel();
	 							$dados = $user->listaUsers();
	 							$campos = array('idusuario','nome','email');
	 							$html = $this->GeraHtml($campos, $dados, 'Relatorio_Manifestantes');
	 							break;
	 						default:
	 							# code...
	 							break;
	 					}
	 				}else $this->redirect->goToControllerAction($this->redirect->getCurrentController(), $this->redirect->getCurrentAction());

 				}else $this->redirect->goToControllerAction('sistema', 'index');
 		}

 		public function GeraHtml(Array $campos, Array $dados, $nome){

 			$html = '';  
			$html .= '<table border="1" class="table table-hover">';  
			$html .= '<thead>';
			$html .= '<tr style="background: #428bca;">';
			foreach ($campos as $colunas) {
				$html .= '<th style="color: #fff;">'.utf8_decode($colunas).'</th>';
			}
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			foreach ($dados as $linhas) {
				$html .= '<tr>';
				foreach ($campos as $colunas) {	
					if(isset($linhas[$colunas])){
						$html .= '<td align="center">'.utf8_decode($linhas[$colunas]).'</td>';
					} else
					$html .= '<td align="center"> </td>';
				}
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
			$arquivo = $nome.'_'.date('Y-m-d-h-i').'.xls';
			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
			header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
			header ("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header ("Pragma: no-cache");
			//header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header ("Content-type: application/x-msexcel");  
			header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );  
			header ("Content-Description: PHP Generated Data" );
			echo $html;  
			exit;  
			return $html;

 		}

 }