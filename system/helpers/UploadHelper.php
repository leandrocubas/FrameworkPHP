<?php 
	/**
	* 
	*/
	class UploadHelper
	{
		
		protected $pasta = '/sorteio/app/uploads/';
		private $name; //name do input que o usuário colocará a imagem 
		private $nome_substituto; //nome que irá sobrescrever o nome da imagem atual 
		private $permite = array("JPG", "JPEG", "PNG"); //Tipo de imagem permitida, ex:png,jpg,gif,pjpeg,jpeg



		public function uploadImagem($file, $nome_principal, $tmpName, $fileName){ 
			if(!empty($file)){
				$this->name = $fileName; 
				$nome = $this->name;
				$extencao = (explode(".", $nome)); 
				unset($extencao[0]);
				$this->nome_substituto = $nome_principal;
				if(empty($extencao[1]))
					return $caminho= "http://placehold.it/100x100";
				$upload_arquivo = $this->pasta.$this->nome_substituto.".".$extencao[1];
				 if(!empty($file) and in_array($extencao[1],$this->permite)){ 
				 	if(move_uploaded_file($tmpName, $_SERVER["DOCUMENT_ROOT"].$this->pasta.$nome))
				 	 	return $this->pasta.$nome;
				 	 else return false;
				 	} else die("formato nao aceito ");
				} else return false;
		}


		public function setPath($path){
			$this->path = $path;
		}

		public function setFile( $file){
			$this->file = $_FILES[$file]['name'];
			$this->setFileName();
			$this->setFileTmpName();
			$this->extencao = end(explode(".",$this->filename));
		}

		protected function setFileName(){
			$this->filename = $this->file['name'];
			var_dump($this->filename);
		}

		protected function setFileTmpName(){
			$this->fileTmpName = $this->file['tmp_name'];
		}


		public function upload(){
			
				if(!empty($this->filename) and in_array($this->extencao,$this->permite)){ 
					if(move_uploaded_file($this->fileTmpName, $_SERVER["DOCUMENT_ROOT"].$this->path.$this->filename))
						return true;
					else
						return false;
				}

				//die("tipo de imagem nao permitido");
		} 
	}