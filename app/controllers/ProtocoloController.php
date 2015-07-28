<?php 

	/**
	* cadastro de Sorteios, participantes e brindes
	* Desenvolvido por Leandro Cubas de Macedo
	*/

	class Protocolo extends Controller
	{
		private $users;
		private $brindes, $auth, $redirect, $idusuario, $protocoloBd;

		public function init(){			
 			$this->redirect= new RedirectorHelper();
 			$this->idusuario =$_SESSION['userData']['idusuario'];	
 			$this->auth = new authHelper();
 			$sessionHelper = new SessionHelper();
 			$this->protocoloBd = new ProtocoloModel();
 			$this->users= new IndexModel();
 		if(!$sessionHelper->checkSession("userAuth")){
 				$this->redirect->goToControllerAction('index', 'login'); // direciona se não estiver logado
 			}
 			
 		}

 		public function Index_action(){
 			$this->ConsultaProtocolo();
 		}

 		public function ConsultaProtocolo(){
 			$tabelas = "manifestacao b, protocolo c, usuario a";
 				$where = "a.idusuario =".$this->idusuario." and a.idusuario = b.idusuario and b.idmanifestacao = c.manifestacao_idmanifestacao";
 				$dados['lista_protocolos'] = $this->protocoloBd->ListaProtocolo($where, $tabelas);
 				if($this->getParams('status') == 'sucesso'){
	 				$this->view('ConsultaProtocolosSucesso',$dados);
	 			} else $this->view('ConsultaProtocolos',$dados);
 	
 		}

}
	

