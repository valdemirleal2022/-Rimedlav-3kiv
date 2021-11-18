<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$dataEmissao1 = $_SESSION['dataEmissao1'];
$dataEmissao2 = $_SESSION['dataEmissao2'];


$leituraRota = read('contrato_rota',"WHERE id ORDER BY nome ASC");

$nome_arquivo = "relatorio-veiculo-pesagem";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";

$html .= "<tr>";
	$html .= "<td>Saida</td>";
	foreach($leituraRota as $mostraRota):
		$html .= "<td>"."'".$mostraRota['nome']."</td>";
	endforeach;
$html .= "</tr>";

$data=$dataEmissao1;

while ($data <= $dataEmissao2) :

	$html .= "<tr>";
	$html .= "<td>".$data."</td>";

	foreach($leituraRota as $mostraRota):
	 
		$rotaId = $mostraRota['id'];
		$veiculoLiberacao = mostra('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaId'");
		
		$pesagemTotal = $veiculoLiberacao['pesagem'] ; 

		$pesagemColetada=0;

		$leitura = read('contrato_ordem',"WHERE data>='$data' AND data<='$data' AND rota='$rotaId' 
														AND status='13'");

		$leituraTipoColeta= read('contrato_tipo_coleta',"WHERE id ORDER BY id ASC");
		if($leituraTipoColeta){
			foreach($leituraTipoColeta as $tipoColeta):

				$tipoColetaId = $tipoColeta['id'];
				$pesoMedio = $tipoColeta['peso_medio'];
				$coletado=0;

				foreach($leitura as $mostra):
			
					if($mostra['tipo_coleta1']==$tipoColetaId ){
						$coletado=$coletado+$mostra['quantidade1'];
					}

				endforeach;
  
					$pesagemColetada=$pesagemColetada+($pesoMedio*$coletado);
			 

			endforeach;
		}

		$pesagem = $pesagemTotal - $pesagemColetada;
		$html .= "<td>".$pesagem."</td>";
	
	 
	endforeach;
	 
	$html .= "</tr>";

    $data =  somarDia($data,1);

endwhile;

echo $html;

?>