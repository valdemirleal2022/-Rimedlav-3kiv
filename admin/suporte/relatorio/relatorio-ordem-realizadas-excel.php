<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

if(isset($_SESSION[ 'rotaColeta' ])){
	$rotaId =$_SESSION[ 'rotaColeta' ];
}


$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
														AND status='13'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
													AND status='13' ORDER BY data ASC, rota ASC, hora ASC");

if(!empty($rotaId)){
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");
	$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' 
														AND status='13'");
	$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' 
														AND status='13'  ORDER BY data ASC, rota ASC, hora ASC");
}

$nome_arquivo = "relatorio-ordem-realizadas-excel";
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
	$html .= "<td>Endereço</td>";

	$html .= "<td>Bairro</td>";
	$html .= "<td>Numero</td>";
	$html .= "<td>Coleta</td>";
	$html .= "<td>Previsto</td>";
	$html .= "<td>Coletado</td>";
	$html .= "<td>Vl Unit</td>";
	$html .= "<td>Faturado</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Rota</td>";

$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";
		
		
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		$controle = substr($contrato['controle'],0,6);

		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>".$controle."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";	
		$html .= "<td>".$cliente['bairro']."</td>";

		$html .= "<td>".$mostra['id']."</td>";

		$tipoColetaId = $mostra['tipo_coleta1'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";

		$contratoId = $mostra['id_contrato'];
		$dataroteiro=$mostra['data'];
		$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'  AND tipo_coleta='$tipoColetaId'" );

		$html .= "<td>".$contratoColeta['quantidade']."</td>";

		$html .= "<td>".$mostra['quantidade1']."</td>";

		$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";

		$valor=$contratoId = $mostra['quantidade1']*$contratoColeta['valor_unitario'];
		if($mostra['quantidade1']==0){
			$valor = $contratoColeta['quantidade'] * $contratoColeta['valor_unitario'];
		}
 
		$html .= "<td>". converteValor($valor)."</td>";
 		$html .= "<td>".converteData($mostra['data'])."</td>";

		$rotaId =$mostra[ 'rota' ];
		$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");

		
		$html .= "<td>".$rotaColeta['nome']."</td>";

		$valorTotal=$valorTotal+$valor;

	$html .= "</tr>";
endforeach;


$valorTotal=0;
$pesagemPrevista=0;

$leituraTipoColeta= read('contrato_tipo_coleta',"WHERE id ORDER BY id ASC");
if($leituraTipoColeta){
	foreach($leituraTipoColeta as $tipoColeta):
	
		$nome= $tipoColeta['nome'];
		$tipoColetaId = $tipoColeta['id'];
		$pesoMedio = $tipoColeta['peso_medio'];
		$previsto=0;
		$coletado=0;
		$valorColeta=0;
		 
		foreach($leitura as $mostra):
	
			if($mostra['tipo_coleta1']==$tipoColetaId ){
				
				$contratoId = $mostra['id_contrato'];
				
				$dataroteiro=$mostra['data'];
				$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId' 
										   AND tipo_coleta='$tipoColetaId'" );
				
				$previsto=$previsto+$contratoColeta['quantidade'];

				if($contratoColeta){
					$coletado=$coletado+$mostra['quantidade1'];
					$valor=$mostra['quantidade1']*$contratoColeta['valor_unitario'];
					if($mostra['quantidade1']==0){
						$valor=$contratoColeta['quantidade']*$contratoColeta['valor_unitario'];
					}
					$valorColeta=$valorColeta+$valor;
				}
			}

		endforeach;
	
		if($valorColeta<>'0'){
			
			$html .= "<tr>";
				$html .= "<td>".$nome."</td>";
				$html .= "<td>".$previsto."</td>";
				$html .= "<td>".$coletado."</td>";
				$html .= "<td>".converteValor($valorColeta)."</td>";
			$html .= "</tr>";	
			$valorTotal=$valorTotal+$valorColeta;
			$pesagemPrevista=$pesagemPrevista+($pesoMedio*$previsto);
		}
	
	endforeach;
	
	$html .= "<tr>";	
		$html .= "<td>".converteValor($valorTotal)."</td>";
	$html .= "</tr>";	 
 }


$html .= "<tr>";	
	$html .= "<td>'Veiculo/Aterro'</td>";
$html .= "</tr>";	 

$kmTotal=0;
$pesagemTotal=0;

$leitura= read('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' AND rota ='$rotaId'");
if($leitura){
	foreach($leitura as $veiculoLiberacao):
	
		$km=$veiculoLiberacao['km_chegada']-$veiculoLiberacao['km_saida'];
		$kmTotal=$kmTotal+$km;
	
		$pesagem = $veiculoLiberacao['pesagem'] ; 
		$pesagemTotal=$pesagemTotal+$pesagem;
	
	endforeach;
}
	
	$veiculoId=$veiculoLiberacao['id_veiculo'];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	
	$html .= "<tr>";
		$html .= "<td>".'Modelo : '.$veiculo['modelo']. ' Placa : '.$veiculo['placa']."</td>";
	$html .= "</tr>"; 
	
	$aterroId=$veiculoLiberacao['aterro'];
	$aterro = mostra('aterro',"WHERE id ='$aterroId'");
	
	$html .= "<tr>";
		$html .= "<td>".'Aterro : '. $aterro['nome']."</td>";
	$html .= "</tr>";
	
	$html .= "<tr>";
		$html .= "<td>".'Km : '. $kmTotal ."</td>";
	$html .= "</tr>"; 
	
	$html .= "<tr>";
		$html .= "<td>".'Pesagem : ' .$pesagemTotal . ' - Previsto : '. $pesagemPrevista . ' = '.($pesagemTotal - $pesagemPrevista)."</td>";
	$html .= "</tr>"; 
 

echo $html;

?>