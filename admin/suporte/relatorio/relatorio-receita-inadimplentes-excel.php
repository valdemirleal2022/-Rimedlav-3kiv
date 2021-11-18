<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$statusId = $_SESSION['status'];
$contratoTipoId = $_SESSION['contratoTipo'];


$dataHoje = date("Y-m-d", strtotime("-3 day"));
$leitura = read('receber',"WHERE vencimento<='$dataHoje' AND status<>'Baixado' ORDER BY id_contrato ASC, vencimento ASC");

if(!empty($statusId )){
	$status = mostra('contrato_status',"WHERE id='$statusId'");
}

if(!empty($contratoTipoId )){
	$Tipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
}

$totalJuridido = 0;
$totalProtesto = 0;	
$totalSerasa = 0;
$totalEmAberto = 0;
			
$valorJuridido = 0;
$valorProtesto = 0;	
$valorSerasa = 0;
$valorEmAberto = 0;

$nome_arquivo = "relatorio-receita-inadimplentes";
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
	$html .= "<td>CEP</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Contato</td>";

	$html .= "<td>CNPJ/CPF</td>"; 

	$html .= "<td>Status</td>";
	$html .= "<td>Emissao</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Situacao</td>";
	$html .= "<td>Banco/Forma Pag</td>";
	$html .= "<td>Nota</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Rota</td>";

$html .= "</tr>";

foreach($leitura as $mostra):

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		$listar='NAO';
 		
 		 if($mostra['status']=='Em Aberto'){
 			$listar='SIM';
 		}
		if($mostra['status']=='Juridico'){
 			$listar='SIM';
 		}

		
		if($mostra['serasa']=='1'){
		  $listar='SIM';
		}
		if($mostra['juridico']=='1'){
		  $listar='SIM';
		}
		if($mostra['protesto']=='1'){
		  $listar='SIM';
		}
		

		if(!empty($statusId) AND $listar=='SIM'){
			$listar='NAO';
			if($contrato['status']==$statusId){
				$listar='SIM';
			}
		}
		
		if(!empty($contratoTipoId) AND $listar=='SIM'){
			$listar='NAO';
			if($contrato['tipo']==$contratoTipoId){
				$listar='SIM';
			}
		}
		
		if($listar=='SIM'){
			

			$html .= "<tr>";
				$html .= "<td>".$mostra['id']."</td>";

				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId '");

				$html .= "<td>".substr($contrato['controle'],0,6)."</td>";

				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
				$html .= "<td>".$cliente['nome']."</td>";
			
				$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

				$html .= "<td>".$endereco."</td>";

				$html .= "<td>".$cliente['bairro']."</td>";
				$html .= "<td>".$cliente['cep']."</td>";
				$html .= "<td>".$cliente['telefone']."</td>";
				$html .= "<td>".$cliente['contato 9']."</td>";
			
				$html .= "<td>".$cliente['cnpj'].$cliente['cpf']."</td>";


				if($contrato['status']==5){
					$html .= "<td>Ativo</td>";
				}elseif($contrato['status']==6){
					$html .= "<td>Suspenso</td>";
				}elseif($contrato['status']==9){
					$html .= "<td>Cancelado</td>";
				}elseif($contrato['status']==10){
					$html .= "<td>Juridico</td>";
				}else{
					$html .= "<td>!</td>";
				}

				$html .= "<td>".converteData($mostra['emissao'])."</td>";
				$html .= "<td>".converteData($mostra['vencimento'])."</td>";
				$html .= "<td>".converteValor($mostra['valor'])."</td>";
			
				$html .= "<td>".$mostra['status']."</td>";
		
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				$html .= "<td>".$banco['nome']. "|".$formapag['nome']."</td>";
			
				$html .= "<td>".$mostra['nota']."</td>";

				$consultorId = $contrato['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				$html .= "<td>".$consultor['nome']."</td>";

				$tipoId = $contrato['contrato_tipo'];
				$tipoContrato = mostra('contrato_tipo',"WHERE id ='$tipoId'");
				$html .= "<td>". $tipoContrato['nome']."</td>";
			
				if($mostra['status']=='Em Aberto' AND $mostra['vencimento']<=$dataHoje){
					$totalEmAberto = $totalEmAberto+1;
					$valorEmAberto = $valorEmAberto+$mostra['valor'];
				}
		
				if($mostra['serasa']=='1'){
				  $totalSerasa = $totalSerasa+1;
				  $valorSerasa = $valorSerasa+$mostra['valor'];
				}
				if($mostra['juridico']=='1'){
				  $totalJuridido = $totalJuridido+1;
				  $valorJuridido = $valorJuridido+$mostra['valor'];
				}
				if($mostra['protesto']=='1'){
				  $totalProtesto = $totalProtesto+1;
				  $valorProtesto = $valorProtesto+$mostra['valor'];
				}
			
				$diaSemana='';
				$rota='';
				if($contrato['domingo']==1){
					$diaSemana = ' Dom';
					$rota=$contrato['domingo_rota1'];
				}
				if($contrato['segunda']==1){
					$diaSemana = $diaSemana . ' Seg';
					$rota=$contrato[ 'segunda_rota1' ];
				}
				if($contrato['terca']==1){
					$diaSemana = $diaSemana . ' Ter';
					$rota=$contrato[ 'terca_rota1' ];
				}
				if($contrato['quarta']==1){
					$diaSemana = $diaSemana . ' Qua';
					$rota=$contrato[ 'quarta_rota1' ];
				}
				if($contrato['quinta']==1){
					$diaSemana = $diaSemana . ' Qui';
					$rota=$contrato[ 'quinta_rota1' ];
				}
				if($contrato['sexta']==1){
					$diaSemana = $diaSemana . ' Sex';
					$rota=$contrato[ 'sexta_rota1' ];
				}
				if($contrato['sabado']==1){
					$diaSemana = $diaSemana . ' Sab';
					$rota=$contrato[ 'sabado_rota1' ];
				}

				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rota'" );
				$rota = $rota[ 'nome' ];
			
				$html .= "<td>". $rota ."</td>";
			
			$html .= "</tr>";
			
	}

endforeach;

echo $html;

?>