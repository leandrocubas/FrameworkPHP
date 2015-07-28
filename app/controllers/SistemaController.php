<?php 

/**
* Desenvolvido por Leandro Cubas de Macedo
*/
class Sistema extends Controller
{
	private $users;
	private $iduser;
	private $sistemaBd;
	private $protocoloBd;
	private $redirect, $protocolo;

	public function init(){	
			$this->redirect= new redirectorHelper();	
			$this->sistemaBd = new SistemaModel();
			$this->protocoloBd = new ProtocoloModel();	
 			$this->auth = new authHelper();
 			$this->users= new IndexModel();
 			$sessionHelper = new SessionHelper();
 		if(!$sessionHelper->checkSession("userAuth")){
 				$this->redirect->goToControllerAction('Index', 'login'); // direciona se não estiver logado
 			}
 			$this->iduser = $_SESSION['userData']['idusuario'];
 			//$redirect->goToAction('index');
 		}

 		public function Index_action(){
 			$nome = $_SESSION['userData']['nome'];
 			$where = "nome ='".$nome."'";
		 	$sql = $this->users->read($where);
		 	$dados['nome'] = $nome;	
		 	$dados['ultimo_id'] = $this->sistemaBd->UltimoId();
			 			//$lista_sorteios = $sorteios->listaSorteios($tabelas,$where);
			 			//$dados['lista_sorteios'] = $lista_sorteios; // receber como view_lista_sorteios
			$this->view('sistema',$dados);
		 		
 		}

 		public function Cadastrar(){
 			if(isset($_POST['check_sigilo'])) {
				$sigilo = 's';
			}	else
				$sigilo = 'n';
				
			if(isset($_POST["vinculo"])) 
    			$vinculo= $_POST["vinculo"];
			
			if(isset($_POST["setor"])) 
    			$setor=$_POST["setor"];
		
			if(isset($_POST["manifest"])) {
    			$manifest = $_POST["manifest"];
			} else {
				$manifest = '';
				}

			if(isset($_POST["outros"])) 
    			$outros=$_POST["outros"];
    			else $outros = '';

			if(isset($_POST['manifestacao']))
				$manifestacao= $_POST['manifestacao'];
			$campos =array("idusuario", "manifestacao", "setor_unidade", "sigilo", "tipo_manifestacao", "vinculo_manifestante", "outros");
		 	$valores=array($this->iduser, $manifest, $setor, $sigilo, $manifestacao, $vinculo, $outros);
		 	$dados= array_combine($campos, $valores);
			if($this->sistemaBd->cadastraManifestacao($dados)){
				$this->CadastraProtocolo();
				$email  = $_SESSION['userData']['email'];
				$assunto = "Protocolo da Ouvidoria FSA";
				$mensagem ="<strong>PROTOCOLO DE MANIFESTAÇÃO</strong>
				Prezado (a)<br />
				Agradecemos o contato e informamos que sua manifestação foi protocolada sob nº".$this->protocolo. "em". date("Y-m-d").".<br />
				Informamos que sua manifestação será analisada e seu andamento poderá ser acompanhado por meio do site www.fsa.br (.................), informando o número deste protocolo e a data de registro.<br />
				Esclarecemos que o prazo máximo de resposta será o de até 20 (vinte) dias corridos a partir da data de registro da manifestação, conforme previsto no Artigo 20 do Decreto nº 60.399, de 29 de abril de 2014.
			    Este prazo poderá ser prorrogado por mais 10 (dez) dias corridos, mediante justificativa fundamentada.<p />
				Atenciosamente,<br />
				<strong>Ouvidoria da Fundação Santo André</strong>";
				$email = new MailController($email, $assunto, $mensagem);
				return $this->redirect->goToControllerAction('Protocolo', 'index/status/sucesso');
			}else{
				die("Não foi possivel realizar o cadastro da sua Manifestação, Tente novamente");
			}
 		}

 		public function GeraProtocolo(){
 			$data=date("Ydhis"); //recuperando ano,dia,hora,minuto,segundos
			$date=substr($data, 0, 12); //removendo os milesimos e ficando apenas com os segundos
			$protocolo=$date.$this->sistemaBd->UltimoId(); //gerando o protocolo com a data junto com o ultimo ID do insert
			return $protocolo;
 		}

 		public function CadastraProtocolo() {
 			$ultimo_id = $this->sistemaBd->UltimoId();
 			$data = date("Y-m-d");
 			$this->protocolo = $this->GeraProtocolo();
 			$campos =array("data_geracao", "data_conclusao" , "manifestacao_idmanifestacao", "protocolo","status");
		 	$valores=array($data, '' , $ultimo_id, $this->protocolo ,'Recebido');
		 	$dados= array_combine($campos, $valores);
		 	return $this->protocoloBd->CadastraProtocolo($dados);
 		}

 		
}