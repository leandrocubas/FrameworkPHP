<?php

/**
* 
*/
class IndexModel extends Model
{
	public $tabela = "usuario";
	

	public function cadastrar($dados){
		if(isset($dados['email'])){
			$db = new Model();
 			$db->tabela= $this->tabela;
 			$where = "email ='".$dados['email']."'";
 			$sql = $db->read($where);
 			if(count($sql) > 0)
 				die("Email já cadastrada, se cadastre com um novo Email");

 			elseif($db->insert($dados)){
	 			$redirect= new RedirectorHelper();
	 			$redirect->goToAction('cadastrar/sucesso/y');
 			}
 			else{
 				die(var_dump($dados). " não foi possivel cadastrar");
 			}
 			$this->redirectorHelper->goToControllerAction($this->loginController, $this->loginAction);
 			return $this;
 		}
 		die("Impossivel realizar esta ação. Parametros de Cadastro vazio");
	}

	public function listaUsers($where = null, $limit=null, $offset=null, $orderby=null){
			return $this->read(null, null, null, $orderby);
		}

	public function DeletaUser($where = null, $limit=null, $offset=null, $orderby=null){
		return $this->delete($where);

	}

}