<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$rotaId =$_SESSION[ 'rotaColeta' ];

$leitura =read('contrato',"WHERE id AND status='5' AND (
	domingo_rota1 = '$rotaId' AND domingo_rota1 !='0' OR 
	segunda_rota1 = '$rotaId' AND segunda_rota1 !='0' OR 
	terca_rota1 = '$rotaId' AND terca_rota1 !='0' OR 
	quarta_rota1 = '$rotaId' AND quarta_rota1 !='0' OR 
	quinta_rota1 = '$rotaId' AND quinta_rota1 !='0' OR 
	quinta_rota1 = '$rotaId' AND quinta_rota1 !='0' OR 
	sabado_rota1 = '$rotaId' AND sabado_rota1 !='0') ORDER BY segunda_hora1 ASC "); 

$nome_arquivo = "relatorio-rota-semanal";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";

//	$html .= "<td>Id</td>";

	//$html .= "<td>Controle</td>";

	$html .= "<td>Nome</td>";
	

	$html .= "<td>Endereco</td>";
	$html .= "<td>Numero</td>";

	$html .= "<td>Bairro</td>";
	$html .= "<td>Cidade</td>";

	$html .= "<td>Cep</td>";
	$html .= "<td>Estado</td>";

	$html .= "<td>Latitude</td>";
	$html .= "<td>Longitude</td>";

	$html .= "<td>Coleta</td>";
	$html .= "<td>Quantidade</td>";
	$html .= "<td>Seg</td>";
	$html .= "<td>Terc</td>";
	$html .= "<td>Qua</td>";
	$html .= "<td>Qui</td>";
	$html .= "<td>Sex</td>";
	$html .= "<td>Sab</td>";
	$html .= "<td>Dom</td>";
	$html .= "<td>Controle</td>";
	$html .= "<td>Referencia</td>";
	$html .= "<td>CNPJ/CPF</td>";
	$html .= "<td>Valor Mensal</td>";

	$html .= "<td>Tipo Contrato</td>";

	$html .= "<td>Email</td>";
	$html .= "<td>Email Financeiro</td>";

	$html .= "<td>Manifesto Login</td>";
	$html .= "<td>Manifesto CPF</td>";
	$html .= "<td>Manifesto Senha</td>";
	$html .= "<td>Manifesto Email</td>";



$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";
 
		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		//$html .= "<td>".$mostra['id']."</td>";
		//$html .= "<td>".$mostra['controle']."</td>";
		$html .= "<td>".$cliente['nome']."</td>";

	
 
		$html .= "<td>".$cliente['endereco']."</td>";
		$html .= "<td>".$cliente['numero']."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['cidade']."</td>";

		$html .= "<td>". $cliente['cep']."</td>";
		$html .= "<td>". $cliente['uf']."</td>";

		$html .= "<td>". $cliente['latitude']."</td>";
		$html .= "<td>". $cliente['longitude']."</td>";

		$contratoId = $mostra['id'];
		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
		$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		
		$html .= "<td>".$coleta['nome']."</td>";
		$html .= "<td>".$contratoColeta['quantidade']."</td>";
	
		if($mostra['segunda_rota1']==$rotaId){
			$html .= "<td>".$mostra['segunda_hora1']."</td>";
		}else{
			$html .= "<td>-</td>";
		}

		if($mostra['terca_rota1']==$rotaId){
			$html .= "<td>".$mostra['terca_hora1']."</td>";
		}else{
			$html .= "<td>-</td>";
		}
		
		if($mostra['quarta_rota1']==$rotaId){
			$html .= "<td>".$mostra['quarta_hora1']."</td>";
		}else{
			$html .= "<td>-</td>";
		}

		if($mostra['quinta_rota1']==$rotaId){
			$html .= "<td>".$mostra['quinta_hora1']."</td>";
		}else{
			$html .= "<td>-</td>";
		}

		if($mostra['sexta_rota1']==$rotaId){
			$html .= "<td>".$mostra['sexta_hora1']."</td>";
		}else{
			$html .= "<td>-</td>";
		}

		if($mostra['sabado_rota1']==$rotaId){
			$html .= "<td>".$mostra['sabado_hora1']."</td>";
		}else{
			$html .= "<td>-</td>";
		}

		if($mostra['domingo_rota1']==$rotaId){
			$html .= "<td>".$mostra['domingo_hora1']."</td>";
		}else{
			$html .= "<td>-</td>";
		}
		
		$html .= "<td>". substr($mostra['controle'],0,6)."</td>";

		$html .= "<td>".$cliente['referencia']."</td>";
		$html .= "<td>".$cliente['cnpj'].' '.$cliente['cpf']."</td>";

		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";

	    $contratoTipoId = $mostra['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
		$html .= "<td>".$contratoTipo['nome']."</td>";

		$html .= "<td>".$cliente['email']."</td>";
		$html .= "<td>".$cliente['email_financeiro']."</td>";

		$html .= "<td>".$cliente['manifesto_login']."</td>";
		$html .= "<td>".$cliente['manifesto_cpf']."</td>";
		$html .= "<td>".$cliente['manifesto_senha']."</td>";
		$html .= "<td>".$cliente['manifesto_email']."</td>";
		


	$html .= "</tr>";
endforeach;

echo $html;

?>