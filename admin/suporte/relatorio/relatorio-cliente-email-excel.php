<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$total=0;
$leitura = read('cliente',"WHERE id ORDER BY email ASC, email_financeiro ASC");

$nome_arquivo = "relatorio-cliente-sem-email";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");

//Nome / descrição ( tipo de coleta ) / Logradouro ( endereço ) / Número / Bairro / Localidade / Cep / //Sigla (estado) / Latitude / Longitude
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";

	$html .= "<td>Nome</td>";

	$html .= "<td>Endereco</td>";
	$html .= "<td>Numero</td>";
	$html .= "<td>Complemento</td>";

	$html .= "<td>Bairro</td>";
	$html .= "<td>Cidade</td>";
	$html .= "<td>Cep</td>";
	$html .= "<td>CNPJ/CPF</td>";

	$html .= "<td>Contrato</td>";

	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Tipo de Coleta</td>";
 
	
	$html .= "<td>Consultor</td>";
	$html .= "<td>Email</td>";

	$html .= "<td>Tipo</td>";

	$html .= "<td>Status</td>";

$html .= "</tr>";
foreach($leitura as $mostra):

	
		$total++;
		
		$html .= "<tr>";
			 
			$html .= "<td>".$mostra['nome']."</td>";

			$html .= "<td>".$mostra['endereco']."</td>";
			$html .= "<td>".$mostra['numero']."</td>";
			$html .= "<td>".$mostra['complemento']."</td>";

			$html .= "<td>".$mostra['bairro']."</td>";
			$html .= "<td>".$mostra['cidade']."</td>";
			$html .= "<td>". $mostra['cep']."</td>";
			$html .= "<td>". $mostra['cnpj'].' '. $mostra['cpf']."</td>";
		 	
			$html .= "<td>".substr($contrato['controle'],0,6)."</td>";

			$clienteId = $mostra['id'];
			$contrato = mostra('contrato',"WHERE id_cliente ='$clienteId'");
			$contratoId = $contrato['id'];
				
			$contratoTipoId = $contrato['contrato_tipo'];
			$monstraContratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
			$html .= "<td>".$monstraContratoTipo['nome']."</td>";

			$hoje= date( "Y/m/d");
			$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato='$contratoId' AND vencimento>'$hoje'");
			$tipoColetaId = $contratoColeta['tipo_coleta'];
            $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
			$html .= "<td>".$coleta['nome']."</td>";
		
			$consultorId=$contrato['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$html .= "<td>".$consultor['nome']."</td>";

			$html .= "<td>". $mostra['email']."</td>";

			$tipod=$mostra['tipo'];
			$tipo = mostra('cliente_tipo',"WHERE id ='$tipod'");
			$html .= "<td>".$tipo['nome']."</td>";

			$statusId=$contrato['status'];
			$status = mostra('contrato_status',"WHERE id ='$statusId'");
			$html .= "<td>".$status['nome']."</td>";

	
		$html .= "</tr>";

endforeach;


$html .= "<tr>";
	$html .= "<td>Total</td>";
	$html .= "<td>". $total."</td>";
$html .= "</tr>";


echo $html;

?>