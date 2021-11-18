<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$rota='';

$dataEmissao1 = $_SESSION['dataEmissao1'];
$dataEmissao2 = $_SESSION['dataEmissao2'];

if(isset($_SESSION[ 'rotaId' ])){
	$rotaId =$_SESSION[ 'rotaId' ];
	$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
	$rotaNome = $rota['nome'];
}
if(isset($_SESSION[ 'veiculoId' ])){
	$veiculoId =$_SESSION[ 'veiculoId' ];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$veiculoNome = $veiculo['modelo'].' | '.$veiculo['placa'];
}

if(isset($_SESSION[ 'aterroId' ])){
	$aterroId =$_SESSION[ 'aterroId' ];
	$aterro = mostra('aterro',"WHERE id ='$aterroId'");
	$aterroNome = $aterro['nome'];
}


$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' ORDER BY saida ASC");

	if(!empty($rotaId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' ORDER BY saida ASC");
	}
	if(!empty($rotaId) && !empty($veiculoId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' ORDER BY saida ASC");
	}
	if(!empty($rotaId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND aterro='$aterroId' ORDER BY saida ASC");
	}
	if(!empty($rotaId) && !empty($veiculoId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida ASC");
	}
	if(!empty($veiculoId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida ASC");
	}
	if(!empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND aterro='$aterroId' ORDER BY saida ASC");
	}


$nome_arquivo = "relatorio-veiculo-liberacoes";
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
	$html .= "<td>Veículo</td>";
	$html .= "<td>Placa</td>";
	$html .= "<td>Saida</td>";
	$html .= "<td>Hora</td>";
	$html .= "<td>Chegada</td>";
	$html .= "<td>Hora</td>";
	$html .= "<td>Pesagem</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Km Saida</td>";
	$html .= "<td>Km Chegada</td>";
	$html .= "<td>Aterro</td>";
	$html .= "<td>Prev</td>";
	$html .= "<td>Real.</td>";

	$html .= "<td>Motorista</td>";
	$html .= "<td>Ajudante 1</td>";
	$html .= "<td>Ajudante 2</td>";
 	
	$html .= "<td>Status</td>";
 	$html .= "<td>H Trab</td>";
	
$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$veiculoId = $mostra['id_veiculo'];
		$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");

		$html .= "<td>".$veiculo['modelo']."</td>";
		$html .= "<td>".$veiculo['placa']."</td>";
	
		$html .= "<td>".converteData($mostra['saida'])."</td>";
		$html .= "<td>".$mostra['saida_hora']."</td>";

		$html .= "<td>".converteData($mostra['chegada'])."</td>";
		$html .= "<td>".$mostra['chegada_hora']."</td>";
		
		$html .= "<td>".$mostra['pesagem']."</td>";

		$rotaId = $mostra['rota'];
		$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
		
		$html .= "<td>". $rota['nome']."</td>";

		$html .= "<td>".$mostra['km_saida']."</td>";
		$html .= "<td>".$mostra['km_chegada']."</td>";	
		

		$dataroteiro=$mostra['saida'];

		$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND rota='$rotaId'" );
		$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND rota='$rotaId' AND status='13'" );

		$aterroId = $mostra['aterro'];
		$aterro = mostra('aterro',"WHERE id ='$aterroId'");
	
		$html .= "<td>".substr($aterro['nome'],0,15)."</td>";

		$html .= "<td>". $ordemEmaberto ."</td>";
		$html .= "<td>". $ordemRealizada ."</td>";

		$motoristaId = $mostra['motorista'];
		$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$html .= "<td>". $motorista['nome'] ."</td>";
 	
		$motoristaId = $mostra['ajudante1'];
		$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$html .= "<td>". $motorista['nome'] ."</td>";
		
		$motoristaId = $mostra['ajudante2'];
		$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$html .= "<td>". $motorista['nome'] ."</td>";

		$html .= "<td>". $mostra['status'] ."</td>";

		if(!empty($mostra['chegada_hora'])){
			$horas = (int) ($mostra['horas_trabalhadas']/60);
			$minutos = $mostra['horas_trabalhadas']- ($horas*60) ;
			$totalHoras = $horas.'h ' . $minutos.'m';
			$html .= "<td>". $totalHoras ."</td>";
			 
		}
		
	$html .= "</tr>";
endforeach;

echo $html;

?>