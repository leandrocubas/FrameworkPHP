<?php 

/**
* 
*/
class ProtocoloModel extends Model
{
	

	public $tabela = "protocolo";

		public function ListaProtocolo($where = null, $tabela=null, $limit =null, $offset=null, $orderby=null){
		$where = ($where !=null ? "where {$where}" : "");
		$tabela = ($tabela !=null ? "{$tabela}" : "");
		$limit = ($limit !=null ? "limit {$limit}" : "");
		$offset = ($offset !=null ? "offset {$offset}" : "");
		$orderby = ($orderby !=null ? "order by {$orderby}" : "");
		$q =$this->conexao->query("SELECT * FROM {$tabela} {$where} {$orderby} {$limit} {$offset}");
		$q->setFetchMode(PDO::FETCH_ASSOC);
		return $q->fetchAll();
	}

		public function CadastraProtocolo(Array $dados ){
			return $this->insert($dados);
		}

		public function AtualizaProtocolo(Array $dados,Array $valores = null, $where = null, Array $final = null){
			return $this->update2($dados, $valores, $where, $final);
		}


}