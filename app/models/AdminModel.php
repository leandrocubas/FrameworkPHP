<?php 

/**
* 
*/
class AdminModel extends Model
{
	

	public $tabela = "admin";

		public function ListaProtocolo($where = null, $tabela=null, $limit =null, $offset=null, $orderby=null){
		return $this->read($where, $limit, $offset, $orderby);
	}

 		public function DeletaBrindes($where = null, $limit=null, $offset=null, $orderby=null){
			return $this->delete($where);
		}

		public function CadastraProtocolo(Array $dados ){
			return $this->insert($dados);
		}

		public function ConsultaAdmin($where = null, $tabela=null, $limit =null, $offset=null, $orderby=null){
		return $this->read($where, $limit, $offset, $orderby);
	}

}