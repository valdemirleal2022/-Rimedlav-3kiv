<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['data1'];
$data2 = $_SESSION['data2'];

if(isset($_SESSION[ 'veiculo' ])){
	$veiculoId =$_SESSION[ 'veiculo' ];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$veiculoNome = $veiculo['modelo'].' | '.$veiculo['placa'];
}


$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");

if(!empty($veiculoId)){
	$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_veiculo='$veiculoId' ORDER BY data_solicitacao ASC");
}


$nome_arquivo = "relatorio-veiculo-manutencao";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";

	$html .= "<td>Id</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Placa</td>";

	$html .= "<td>Motorista</td>";

	$html .= "<td>Tipo</td>";
	
	
	$html .= "<td>Descricao1</td>";
	$html .= "<td>Reparo</td>";
	$html .= "<td>Tempo</td>";
	$html .= "<td>responsavel</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Tipo</td>";
 
	$html .= "<td>Descricao2</td>";
	$html .= "<td>Reparo</td>";
	$html .= "<td>Tempo</td>";
	$html .= "<td>responsavel</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Tipo</td>";

	$html .= "<td>Descricao3</td>";
	$html .= "<td>Reparo</td>";
	$html .= "<td>Tempo</td>";
	$html .= "<td>responsavel</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Tipo</td>";

	$html .= "<td>Valor</td>";
	$html .= "<td>Status Geral</td>";


$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		
		$html .= "<td>".converteData($mostra['data_solicitacao'])."</td>";

		$veiculoId = $mostra['id_veiculo'];
		$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
		$html .= "<td>".$veiculo['placa']."</td>";

		$motoristaId = $mostra['id_motorista'];
		$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		if(!$motorista){
			$html .= "<td>-</td>"; 
		}else{
		 
			$html .= "<td>" .$motorista['nome']."</td>"; 
		}

		if($mostra['manutencao']=='1'){
	 		$html .= "<td>Preventiva</td>"; 
		}elseif($mostra['manutencao']=='2'){
		 	$html .= "<td>Corretiva</td>"; 
		}elseif($mostra['manutencao']=='3'){
			$html .= "<td>Socorro</td>"; 
 		}elseif($mostra['manutencao']=='4'){
			$html .= "<td>Diversos</td>"; 
 		}else{
			$html .= "<td>-</td>"; 
		}


		$html .= "<td>" .$mostra['descricao1']."</td>"; 
		$html .= "<td>" .$mostra['reparo1']."</td>"; 
	
		$HoraEntrada = new DateTime($mostra['inicio1']);
		$HoraSaida   = new DateTime( $mostra['termino1']);
		$diffHoras = $HoraSaida->diff($HoraEntrada)->format('%H:%I:%S');
		$html .= "<td>". $diffHoras."</td>";

		$responsavelId = $mostra['responsavel1'];
		$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
		$html .= "<td>".$responsavel['nome']."</td>";
		
		if($mostra['status1'] == '1'){
			$html .= "<td>Em Manutencao </td>";
		}else if($mostra['status1'] == '2'){
			$html .= "<td>Concluida </td>";
		}else{
			$html .= "<td>-</td>";
		}

		$tipoId = $mostra['tipo1'];
		$tipo = mostra('veiculo_manutencao_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>".$tipo['nome']."</td>";

		$html .= "<td>" .$mostra['descricao2']."</td>"; 
		$html .= "<td>" .$mostra['reparo2']."</td>"; 

		$HoraEntrada = new DateTime($mostra['inicio2']);
		$HoraSaida   = new DateTime( $mostra['termino2']);
		$diffHoras = $HoraSaida->diff($HoraEntrada)->format('%H:%I:%S');
		$html .= "<td>". $diffHoras."</td>";

		$responsavelId = $mostra['responsavel2'];
		$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
		$html .= "<td>".$responsavel['nome']."</td>";
		
		if($mostra['status2'] == '1'){
			$html .= "<td>Em Manutencao </td>";
		}else if($mostra['status2'] == '2'){
			$html .= "<td>Concluida </td>";
		}else{
			$html .= "<td>-</td>";
		}

		$tipoId = $mostra['tipo2'];
		$tipo = mostra('veiculo_manutencao_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>".$tipo['nome']."</td>";



		$html .= "<td>" .$mostra['descricao3']."</td>"; 
		$html .= "<td>" .$mostra['reparo3']."</td>"; 

		$HoraEntrada = new DateTime($mostra['inicio3']);
		$HoraSaida   = new DateTime( $mostra['termino3']);
		$diffHoras = $HoraSaida->diff($HoraEntrada)->format('%H:%I:%S');
		$html .= "<td>". $diffHoras."</td>";

		$responsavelId = $mostra['responsavel3'];
		$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
		$html .= "<td>".$responsavel['nome']."</td>";
		
		if($mostra['status3'] == '1'){
			$html .= "<td>Em Manutencao </td>";
		}else if($mostra['status3'] == '2'){
			$html .= "<td>Concluida </td>";
		}else{
			$html .= "<td>-</td>";
		}

		$tipoId = $mostra['tipo3'];
		$tipo = mostra('veiculo_manutencao_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>".$tipo['nome']."</td>";

		$manutencaoId = $mostra['id'];
		
		$valorManutencao=0;
		
		$leituraPecas = read('estoque_material_retirada',"WHERE id AND id_manutencao='$manutencaoId' ORDER BY id ASC");
		foreach($leituraPecas as $pecas):
			$materialId = $pecas['id_material'];
			$material = mostra('estoque_material',"WHERE id ='$materialId'");
			$valorManutencao=$valorManutencao+$pecas['quantidade']*$material['valor_unitario'];
		endforeach;
 
		$html .= "<td>".converteValor($valorManutencao)."</td>";

		$manutencao=0;
		$concluida=0;
		
				if(!empty($mostra['descricao1'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao2'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao3'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao4'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao5'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao6'])){
					$manutencao=$manutencao+1;
				}
		
				if($mostra['status1']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status2']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status3']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status4']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status5']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status6']=='2'){
					$concluida=$concluida+1;
				}

				$pendencias=$manutencao-$concluida;

				if($pendencias=='0'){
					$html .= "<td>Concluida</td>";
					 
					 
				}else{
					
					$html .= "<td>Em Manutencao</td>";
			 
				}
		

	$html .= "</tr>";

endforeach;

echo $html;

?>