<?php

/**
* 
*/
class Model {
	protected  $conexao;
	private $dsn = 'mysql:dbname=ouvidoria;host=localhost';
	private $user = 'root';
	private $password = '';
	private $host = '127.0.0.1';
	private $dbname = 'ouvidoria';
	public $tabela;
	

	public function __construct(){
		try {
			
		$this->conexao = new PDO( $this->dsn , $this->user , $this->password );
		}
		catch ( PDOException $e ) {
			echo 'Connection failed: ' . $e->getMessage( ); 

			return false;
		}
		return ($this->conexao);

	}

	public function insert(Array $dados){
		$campos = implode(", ", array_keys($dados));
		$valores = "'".implode("','", array_values($dados))."'";
		return $this->conexao->query("INSERT INTO {$this->tabela} ({$campos}) VALUES ({$valores})");
	}

	public function read($where = null, $limit=null, $offset=null, $orderby=null){
		$where = ($where !=null ? "where {$where}" : "");
		$limit = ($limit !=null ? "limit {$limit}" : "");
		$offset = ($offset !=null ? "offset {$offset}" : "");
		$orderby = ($orderby !=null ? "order by {$orderby}" : "");
		$q =$this->conexao->query("SELECT * FROM {$this->tabela} {$where} {$orderby} {$limit} {$offset}");
		$q->setFetchMode(PDO::FETCH_ASSOC);
		return $q->fetchAll();
	}
	
	public function update(Array $dados, $where){
		foreach ($dados as $ind => $val) {
			$campos[] = "{$ind} = '{$val}'";
		}
		$campos = implode(", ", $campos);
		return $this->conexao->query(" UPDATE {$this->tabela} SET {$campos} WHERE {$where} ");
	}

	public function update2(Array $dados, Array $valores, $where, Array $final){
		$campos = null;
		foreach ($dados as $dados)
			$campos = $campos. " ".$dados;
		$where = ($where !=null ? "where {$where}" : "");
		$sql = ("UPDATE {$this->tabela} SET {$campos} {$where}");
		$stmt = $this->conexao->prepare($sql);
		if(!$stmt){
			$this->conexao->errorInfo();
			throw new RuntimeException('Falha na inserção de dados: '.$err[2]);
		}                                  
		$combine = array_combine($final, $valores);
		return $stmt->execute($combine);
	}

	public function delete($where){
		return $this->conexao->query(" DELETE FROM {$this->tabela} WHERE {$where}");
	}

	public function RetornaUltimoID(){
		return $this->conexao->lastInsertId();
	}
}