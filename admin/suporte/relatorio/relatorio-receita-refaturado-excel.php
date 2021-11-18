<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$contratoTipo = $_SESSION['contratoTipo'];

$leitura = read('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' ORDER BY refaturamento_data ASC");
$total = conta('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' ORDER BY refaturamento_data ASC");
$valor_total = soma('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2'",'refaturamento_valor');

if(!empty($contratoTipo )){
	$leitura = read('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' AND contrato_tipo='$contratoTipo' ORDER BY refaturamento_data ASC");
	$total = conta('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2'AND contrato_tipo='$contratoTipo'");
	$valor_total = soma('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' AND contrato_tipo='$contratoTipo'",'refaturamento_valor');
}

$nome_arquivo = "relatorio-receita-refaturado";
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
	$html .= "<td>Controle</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Vl Atual</td>";
	$html .= "<td>Vl Refaturar</td>";
	$html .= "<td>Faturamento</td>";
	$html .= "<td>Motivo</td>";
	$html .= "<td>Autorização</td>";
	$html .= "<td>Data de Emissão</td>";
	$html .= "<td>Refaturado</td>";

	$html .= "<td>Data de Solicitação do Cliente</td>";
	$html .= "<td>Data do Refaturamento</td>";
	$html .= "<td>Data de Autorização</td>";

$html .= "</tr>";

foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	 
		$html .= "<tr>";

			$html .= "<td>".$mostra['id']."</td>";

			$contratoId = $mostra['id_contrato'];
			$contrato = mostra('contrato',"WHERE id ='$contratoId '");

			$html .= "<td>".$contrato['controle']."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			$html .= "<td>".$cliente['nome']."</td>";

			$contratoTipoId = $mostra['contrato_tipo'];
			$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
			$html .= "<td>".$contratoTipo['nome']."</td>";	
			
			$html .= "<td>".converteValor($mostra['valor_anterior'])."</td>";
			$html .= "<td>".converteValor($mostra['refaturamento_valor'])."</td>";
			$html .= "<td>".converteData($mostra['refaturamento_data'])."</td>";

			$motivoId=$mostra['refaturamento_motivo'];
			$motivo = mostra('motivo_refaturamento',"WHERE id ='$motivoId'");
			if($motivo){
				$html .= "<td>".$motivo['nome']."</td>";
			}else{
				$html .= "<td>-</td>";
			}
			
			
			$autorizacao='';
			if($mostra['refaturamento_autorizacao']=='1'){
				$autorizacao='Autorizado';
			}elseif($mostra['refaturamento_autorizacao']=='2'){
				$autorizacao='Não Autorizado';
			}elseif($mostra['refaturamento_autorizacao']=='0'){
				$autorizacao='guardando';
			}
			$html .= "<td>".$autorizacao."</td>";


			$html .= "<td>".converteData($mostra['emissao'])."</td>";
 
			if($mostra['refaturado']=='1'){
					$html .= "<td>Sim</td>";
			}else{
					$html .= "<td>Não</td>";
			}


			$html .= "<td>".converteData($mostra['refaturamento_data_cliente'])."</td>";
 
			$html .= "<td>".converteData($mostra['refaturamento_data'])."</td>";
 
			$html .= "<td>".converteData($mostra['refaturamento_autorizacao_data'])."</td>";
 
		
		$html .= "</tr>";
	 

endforeach;


echo $html;

?>