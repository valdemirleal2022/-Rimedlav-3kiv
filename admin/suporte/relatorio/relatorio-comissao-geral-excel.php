<?php


ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 
$leitura = read('receber',"WHERE id AND pagamento>='$data1' AND pagamento<='$data2' AND status<>'Em Aberto'	ORDER BY pagamento ASC");

$nome_arquivo = "relatorio-comissao-geral";
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
	$html .= "<td>Consultor</td>";
	$html .= "<td>Emissao</td>";
	$html .= "<td>Pagamento</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>%</td>";
	$html .= "<td>Comissao</td>";
	$html .= "<td>Tipo</td>";
	
$html .= "</tr>";

foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
	$receberId = $mostra['id'];
				
	$totalPagamento = conta('receber',"WHERE id_contrato ='$contratoId'");
		
	$contador = 1;
	$primeiraParcela = 0;
	$leituraReceber = read('receber',"WHERE id_contrato ='$contratoId' ORDER BY emissao ASC");
	foreach($leituraReceber as $receber):
		if($contador==1 and $receberId==$receber['id']){
			break;
		}
		$contador = $contador + 1 ; 	
		$primeiraParcela = $primeiraParcela +1;
	endforeach;	

	if($primeiraParcela=='0' ){

		$html .= "<tr>";
		
			$html .= "<td>".$mostra['id']."</td>";
			$html .= "<td>".substr($contrato['controle'],0,6)."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			$html .= "<td>".$cliente['nome']."</td>";
			
			$consultorId = $contrato['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$html .= "<td>".$consultor['nome']."</td>";

			$html .= "<td>".converteData($mostra['emissao'])."</td>";
			$html .= "<td>".converteData($mostra['pagamento'])."</td>";

			$percentual=30.00;
		
			$comissao=($mostra['valor']*$percentual)/100;
			$html .= "<td>".converteValor($mostra['valor'])."</td>";

			$html .= "<td>".$percentual."</td>";
			$html .= "<td>".converteValor($comissao)."</td>";
		
			$tipoId = $contrato['contrato_tipo'];
			$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
		
			$html .= "<td>".$tipo['nome']."</td>";

			$valor_total+=$mostra['valor'];
			$valor_comissao+=$comissao;
			$total++;
		
		$html .= "</tr>";
		
	}
	
endforeach;


$html .= "<tr>";
	$html .= "<td>Valor Comissao R$</td>";
	$html .= "<td>". converteValor($valor_comissao)."</td>";
$html .= "</tr>";

$valorIss=$valor_comissao/1.05;
$valorIss=$valor_comissao-$valorIss;
$valor_comissao=$valor_comissao-$valorIss;

$html .= "<tr>";
	$html .= "<td>Desconto ISS R$ </td>";
	$html .= "<td>". converteValor($valorIss)."</td>";
$html .= "</tr>";

$html .= "<tr>";
	$html .= "<td>Total Comissao R$ </td>";
	$html .= "<td>". converteValor($valor_comissao)."</td>";
$html .= "</tr>";


echo $html;

?>