<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');
 
$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$divergencia = $_SESSION[ 'divergencia' ];
$status = $_SESSION[ 'status' ];

	$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC ");

	$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao DESC, hora_solicitacao DESC");
 
	if(!empty($status)){
		$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status'");
		$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status' ORDER BY data_solicitacao ASC");
	}
		

	if(!empty($divergencia)){
		$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_divergencia='$divergencia'");
		$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_divergencia='$divergencia' ORDER BY data_solicitacao ASC");
	}
		
	if(!empty($status) && !empty($divergencia ) ){
		$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' 
			AND status='$status' AND id_divergencia='$divergencia'");
		$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2'
			AND status='$status' AND id_divergencia='$divergencia' ORDER BY data_solicitacao ASC");
	}
 

$nome_arquivo = "relatorio-funcionario-divergencia";
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
	$html .= "<td>Nome</td>";
	$html .= "<td>Divergencia</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Solicitacao</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Solução</td>";
	$html .= "<td>Tipo</td>";
  
$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$funcionarioId = $mostra['id_funcionario'];

		$funcionarioId = $mostra['id_funcionario'];
		$divergenciaId = $mostra['id_divergencia'];
  	
		$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId '");
		$html .= "<td>".$funcionario['nome']."</td>";

		$divergencia = mostra('funcionario_divergencia_motivo',"WHERE id ='$divergenciaId'");
 		$html .= "<td>".$divergencia['nome']."</td>";
  
		$html .= "<td>". converteData($mostra['data_solicitacao']) ."</td>";
		$html .= "<td>". $mostra['solicitacao'] ."</td>";

		$html .= "<td>" . $mostra['status'] . "</td>";

		$html .= "<td>" . $mostra['solucao'] . "</td>";
	 
		if($mostra['procedente']=='1'){
			$html .= "<td>Em Procedente</td>";
		 
		}elseif($mostra['procedente']=='0'){
			$html .= "<td>Improcedente</td>";
	 
		}elseif($mostra['procedente']=='2'){
			$html .= "<td>Pagamento Extra</td>";
 
 		}
  
	$html .= "</tr>";
endforeach;

echo $html;

?>