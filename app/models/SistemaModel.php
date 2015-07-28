<?php 

	/**
	*  
	*/
	class SistemaModel extends Model
	{
		public $tabela = "manifestacao";

		
	public function CadastraManifestacao(Array $dados){
		return $this->insert($dados);
		}
	
	public function UltimoID(){
		return $this->RetornaUltimoID();
	}

	public function ListaManifestacao($where = null, $tabela=null, $limit =null, $offset=null, $orderby=null){
		$where = ($where !=null ? "where {$where}" : "");
		$tabela = ($tabela !=null ? "{$tabela}" : "");
		$limit = ($limit !=null ? "limit {$limit}" : "");
		$offset = ($offset !=null ? "offset {$offset}" : "");
		$orderby = ($orderby !=null ? "order by {$orderby}" : "");
		$q =$this->conexao->query("SELECT * FROM {$tabela} {$where} {$orderby} {$limit} {$offset}");
		$q->setFetchMode(PDO::FETCH_ASSOC);
		return $q->fetchAll();
		}
	}