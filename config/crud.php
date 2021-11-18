<?php 

	require_once("config.php");
	
	// php 7.1
	// atualizar phpemail
	// define("MAILPORT",'587');
	//ultimoId

	// atualizado em 11/12/2019
	
	//FUNÇÃO PARA CADASTRO NO BANCO DE DADOS: CREATE
	function create($tabela, array $dados){
		
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		global $ultimoId;
		$ultimoId='';

		$campos = implode(", ",array_keys($dados));
		$values = "'".implode("','", array_values($dados))."'";
		$createQR = "INSERT INTO {$tabela} ($campos) VALUES ($values)";
		$execQR = mysqli_query($conectaMsqli,$createQR)  or trigger_error("Cadastro : $createQR - Erro : ".mysqli_error($conectaMsqli), E_USER_ERROR);
		if($execQR){
			$ultimoId=mysqli_insert_id($conectaMsqli);
			mysqli_close($conectaMsqli);
			return true;
		}
		

	}

	


	//FUNÇÃO PARA LEITURA NO BANCO DE DADOS: READ
	function read($tabela, $cond = NULL){
		
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		$res=array();
		
		$readQR = "SELECT * FROM {$tabela} {$cond}";
		$execQR = mysqli_query($conectaMsqli,$readQR) or die (mysqli_error());
		$camposQR = mysqli_num_fields($execQR);
		
		for($y = 0; $y < $camposQR; $y++){
			$names[$y]= mysqli_field_name($execQR,$y);
		}for($x = 0; $resultado = mysqli_fetch_assoc($execQR); $x++){
			for($i = 0; $i < $camposQR; $i++){
				$res[$x][$names[$i]] = $resultado[$names[$i]];
			}	
		}
		mysqli_close($conectaMsqli);
		return $res;
		
	}
	
	//FUNÇÃO PARA LEITURA NO BANCO DE DADOS: READ
	function mostra($tabela, $cond = NULL){
		
		global $res;
		global $resmostra;
		
		$res=array();
		$resmostra=array();
	 	
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		$readQR = "SELECT * FROM {$tabela} {$cond}";
		$execQR = mysqli_query($conectaMsqli,$readQR) or die (mysqli_error());
		$camposQR = mysqli_num_fields($execQR);
		
		for($y = 0; $y < $camposQR; $y++){
			$names[$y]= mysqli_field_name($execQR,$y);
		}for($x = 0; $resultado = mysqli_fetch_assoc($execQR); $x++){
			for($i = 0; $i < $camposQR; $i++){
				$res[$x][$names[$i]] = $resultado[$names[$i]];
			}	
		}
		
		if($res){
		  foreach($res as $resmostra);
		}
		mysqli_close($conectaMsqli);
		return $resmostra;
		
	}


//FUNÇÃO PARA ATUALIZAR NO BANCO DE DADOS: UPDATE

	function update($tabela, array $campos, $where){
		
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		foreach($campos as $keys => $valor)	{
			$dados[] = "$keys = '$valor'";	
		}
		
		$dados = implode(", ",$dados);
		$updateQR = "UPDATE {$tabela} SET $dados WHERE {$where}";
		
		$exeQR    = mysqli_query($conectaMsqli,$updateQR) or die (mysqli_error()); 
		
		if($exeQR){
			mysqli_close($conectaMsqli);
			return true;
		}
	}
	
//FUNÇÃO PARA DELETAR NO BANCO DE DADOS: DELETE

	function delete($tabela, $where){
		
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		$deleteQR = "DELETE FROM {$tabela} WHERE {$where}";
		$deleteEX = mysqli_query($conectaMsqli ,$deleteQR) or die (mysqli_error());
		mysqli_close($conectaMsqli);
	}


	function LimpaTabela($tabela){
		
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		$LimpaQR = "TRUNCATE TABLE {$tabela}";
		$LimpaEX = mysqli_query($conectaMsqli ,$LimpaQR) or die (mysqli_error());
	}
	
	
	//FUNÇÃO PARA LEITURA NO BANCO DE DADOS: READ
	function soma($tabela, $where, $campo){
		
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		$sql = "SELECT sum({$campo}) AS soma FROM {$tabela} {$where}";
		$result = mysqli_query($conectaMsqli,$sql) or die (mysqli_error());
		if($result){
			$dados=mysqli_fetch_assoc($result);
			mysqli_close($conectaMsqli);
			return $dados['soma'];
		}else{
			$dados['soma']='0';
			mysqli_close($conectaMsqli);
			return $dados['soma'];
		}
	}
	
	function conta($tabela, $where){
		
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		
		$sql = "SELECT COUNT(*) AS total FROM {$tabela} {$where}";
		$result = mysqli_query($conectaMsqli,$sql) or die (mysqli_error());
		if($result){
			$dados=mysqli_fetch_assoc($result);
			mysqli_close($conectaMsqli);
			return $dados['total'];
		
		 }else{
			$dados['total']='0';
			mysqli_close($conectaMsqli);
			return $dados['total'];
		}
 
	}


	function mysqli_field_name($result, $field_offset){
		$properties = mysqli_fetch_field_direct($result, $field_offset);
		return is_object($properties) ? $properties->name : null;
	}

	
	function mysql_real_escape_string($campos){
		$conectaMsqli = mysqli_connect(HOST,USER,PASS,BANCO);
		$campos = mysqli_real_escape_string($conectaMsqli,$campos);
		return $campos;
		mysqli_close($conectaMsqli);
	}

?>