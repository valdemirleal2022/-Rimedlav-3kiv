<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 
$nome_arquivo = "relatorio-motorista-negligencia";

header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";

	$html .= "<td>Nome</td>";
	$html .= "<td>Tipo</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Negligencia</td>";
	$html .= "<td>Pontuação</td>";
	$html .= "<td>Data</td>";

$html .= "</tr>";

$leitura = read('veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");

foreach($leitura as $mostra):

	 		$motoristaId =$mostra[ 'id_motorista' ];
			$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		
			$negligenciaId = $mostra['id_negligencia'];
			$negligencia = mostra('veiculo_motorista_motivo_negligencia',"WHERE id ='$negligenciaId'");
			
			$rotaId = $mostra['rota'];
			$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
		
			$html .= "<tr>";
				$html .= "<td>".$motorista['nome']."</td>";
				if($motorista['tipo']=='1'){
					$html .= "<td>Motorista</td>";
				}elseif($motorista['tipo']=='2'){
					$html .= "<td>Ajudante</td>";
				}else{
					$html .= "<td>-</td>";
				}
				$html .= "<td>".$rota['nome']."</td>";
				$html .= "<td>" . $negligencia['nome'] . "</td>";
				$html .= "<td>" .$negligencia['pontuacao'] . "</td>";
				$html .= "<td>" . converteData($mostra['data']) . "</td>";
			$html .= "</tr>";

 
			
  endforeach;

echo $html;

?>