<?php


ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$contratoTipo = $_SESSION['contratoTipo'];

$total = conta('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' AND status<>'Dispensa' ORDER BY vencimento ASC");
$valor_total = soma('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' AND status<>'Dispensa' ORDER BY vencimento ASC",'valor');
$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' AND status<>'Dispensa' ORDER BY vencimento ASC");
		
if(!empty($contratoTipo)){
	$total = conta('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' AND status<>'Dispensa' AND contrato_tipo='$contratoTipo'");
	$valor_total = soma('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' AND status<>'Dispensa' AND contrato_tipo='$contratoTipo'",'valor');
	$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND contrato_tipo='$contratoTipo' AND status<>'Baixado' AND status<>'Dispensa' ORDER BY vencimento ASC");
}

$nome_arquivo = "relatorio-receita";
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
	$html .= "<td>Endereco</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>CEP</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Contato</td>";
	$html .= "<td>Email</td>";

	$html .= "<td>CNPJ/CPF</td>"; 
	$html .= "<td>Emissao</td>";
	$html .= "<td>Vencimento</td>";
	 
	$html .= "<td>Prog Pag</td>";

	$html .= "<td>Valor Bruto</td>";
	$html .= "<td>Juros</td>";
	$html .= "<td>Desconto</td>";
	$html .= "<td>Valor Liquido</td>";

	$html .= "<td>Banco/Forma Pag</td>";
	$html .= "<td>Nota</td>";
	$html .= "<td>status</td>";
	$html .= "<td>SJP</td>";
	$html .= "<td>Status Contrato</td>";
	$html .= "<td>Tipo </td>";

	$html .= "<td>Consultor </td>";
	
	$html .= "<td>Rota</td>";
	
	$html .= "<td>Status do Cliente </td>";

	$html .= "<td>Data </td>";
	$html .= "<td>Atendente </td>";
	$html .= "<td>Solucao </td>";

	$html .= "<td>Data </td>";
	$html .= "<td>Atendente </td>";
	$html .= "<td>Solucao </td>";
	$html .= "<td>Previsão Pagamento </td>";

	$html .= "<td>Refaturamento </td>";
 		
		
$html .= "</tr>";
foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");

		$html .= "<td>".$contrato['controle']."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['cep']."</td>";
		$html .= "<td>".$cliente['telefone']."</td>";
		$html .= "<td>".$cliente['contato']."</td>";
		$html .= "<td>".$cliente['email']."</td>";
			
		$html .= "<td>".$cliente['cnpj'].$cliente['cpf']."</td>";

		$html .= "<td>".converteData($mostra['emissao'])."</td>";
		$html .= "<td>".converteData($mostra['vencimento'])."</td>";

		$html .= "<td>".converteData($mostra['refaturamento_vencimento'])."</td>";

		$html .= "<td>".converteValor($mostra['valor'])."</td>";
		$html .= "<td>".converteValor($mostra['juros'])."</td>";
		$html .= "<td>".converteValor($mostra['desconto'])."</td>";

		$valorLiquido = $mostra['valor']+$mostra['juros']-$mostra['desconto'];
		$html .= "<td>".converteValor($valorLiquido)."</td>";

		$bancoId=$mostra['banco'];
		$banco = mostra('banco',"WHERE id ='$bancoId'");
		$formpagId=$mostra['formpag'];
		$formapag = mostra('formpag',"WHERE id ='$formpagId'");

		$html .= "<td>".$banco['nome']. "|".$formapag['nome']."</td>";
		$html .= "<td>".$mostra['nota']."</td>";
		$html .= "<td>".$mostra['status']."</td>";

		$cobranca='';
		if($mostra['serasa']=='1'){
			$cobranca='S';
		}
		if($mostra['juridico']=='1'){
			$cobranca=$cobranca.'J';
		}
		if($mostra['protesto']=='1'){
			$cobranca=$cobranca.'P';
		}
				
		$html .= "<td>".$cobranca."</td>";

		$statusId=$contrato['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";

		$contratoTipoId = $mostra['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
		$html .= "<td>".$contratoTipo['nome']."</td>";

		$consultorId = $mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		if(!empty($contrato[ 'domingo_rota1' ])){
			$rotaId = $contrato[ 'domingo_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($contrato[ 'segunda_rota1' ])){
			$rotaId = $contrato[ 'segunda_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($contrato[ 'terca_rota1' ])){
			$rotaId = $contrato[ 'terca_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($contrato[ 'quarta_rota1' ])){
			$rotaId = $contrato[ 'quarta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($contrato[ 'quinta_rota1' ])){
			$rotaId = $contrato[ 'quinta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($contrato[ 'sexta_rota1' ])){
			$rotaId = $contrato[ 'sexta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($contrato[ 'sabado_rota1' ])){
			$rotaId = $contrato[ 'sabado_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}

		$html .= "<td>".$rota."</td>"; 

		if($cliente['tipo']==4){
				$html .= "<td>Ouro</td>";
			}elseif($cliente['tipo']==5){
				$html .= "<td>Premium</td>";
			}elseif($cliente['tipo']==6){
				$html .= "<td>Prata</td>";
			}else{
				$html .= "<td>-</td>";
		}
		
		$pedido = mostra('pedido',"WHERE id_contrato ='$contratoId' ORDER BY id ASC");
		if($pedido){
			$html .= "<td>" . converteData($pedido['data_solucao']) ."</td>";
			$html .= "<td>".$pedido['atendente_fechamento']."</td>";
			$html .= "<td>".$pedido['solucao']."</td>";
		}else{
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
		}
		
		$receberId = $mostra['id'];
		$negociacao = mostra('receber_negociacao',"WHERE id_receber ='$receberId' ORDER BY peso ASC, data ASC");
		if($negociacao){
			$html .= "<td>" . converteData($negociacao['data']) ."</td>";
			$usuarioId = $negociacao['id_usuario'];
			$usuario = mostra('usuarios',"WHERE id ='$usuarioId '");
			$html .= "<td>".$usuario['nome']."</td>";
			
			$solucaoId = $negociacao['id_solucao'];
			$solucao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId'");
			$html .= "<td>".$solucao['nome']."</td>";
		 
			$html .= "<td>".$negociacao['previsao_pagamento']."</td>";
			
		}else{
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
		}

		

		if($mostra['refaturar']=='1'){
			$html .= "<td>SIM</td>";
		}

	$html .= "</tr>";

endforeach;

echo $html;

?>