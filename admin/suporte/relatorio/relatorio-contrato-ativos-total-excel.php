<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$leitura = read('cliente',"WHERE id ORDER BY nome ASC");

$nome_arquivo = "relatorio-contrato-ativoS-total";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");

echo '<head><meta charset="iso-8859-1"></head>';

 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";

	$html .= "<td>Controle</td>";
	$html .= "<td>RAZAO SOCIAL</td>";
	$html .= "<td>CNPJ/CPF</td>";

	$html .= "<td>NOME FANTASIA</td>";
	$html .= "<td>ENDERECO</td>";
	$html .= "<td>No./COMPLEMENTO</td>";
	$html .= "<td>BAIRRO</td>";
	$html .= "<td>TELEFONE</td>";
	$html .= "<td>EMAIL</td>";
	$html .= "<td>EXTRAORDINARIO</td>";
	$html .= "<td>BIOLOGICO</td>";
	$html .= "<td>INICIO</td>";
	$html .= "<td>VENCIMENTO</td>";
	$html .= "<td>MENSAL</td>";
	$html .= "<td>QUINZENAL</td>";
	$html .= "<td>SEMANAL</td>";

	$html .= "<td>2a</td>";
	$html .= "<td>3a</td>";
	$html .= "<td>4a</td>";
	$html .= "<td>5a</td>";
	$html .= "<td>6a</td>";
	$html .= "<td>SAB</td>";
	$html .= "<td>DOM</td>";

	$html .= "<td>Semana</td>";
	$html .= "<td>VOLUME</td>";
	$html .= "<td>ACONDICIONAMENTO</td>";
	$html .= "<td>Quantidade</td>";
	$html .= "<td>VALOR UNITARIO</td>";

	$html .= "<td>VALOR MENSAL</td>";

	$html .= "<td>ULTIMO FATURAMENTO</td>";

	$html .= "<td>DATA CANCELAMENTO</td>";

	$html .= "<td>CONSULTOR</td>";

	$html .= "<td>2a</td>";
	$html .= "<td>3a</td>";
	$html .= "<td>4a</td>";
	$html .= "<td>5a</td>";
	$html .= "<td>6a</td>";
	$html .= "<td>SAB</td>";
	$html .= "<td>DOM</td>";

	$html .= "<td>Email</td>";
	$html .= "<td>Restrição</td>";

$html .= "</tr>";
foreach($leitura as $cliente):

		$clienteId = $cliente['id'];
		$contrato = mostra('contrato',"WHERE id AND  id_cliente='$clienteId'");
	
		if($contrato['status']=='9'){
			$dataCancelamento = date("Y-m-d", strtotime("-30 day"));
			if($contrato['data_cancelamento']<$dataCancelamento){
				$contrato='';	
			}
			
		}

		if($contrato){
			
			$html .= "<tr>";
			
				$html .= "<td>".$contrato['controle']."</td>";
				$html .= "<td>".$cliente['nome']."</td>";
				$html .= "<td>".$cliente['cnpj'].$cliente['cpf']."</td>";

				$html .= "<td>".$cliente['nome_fantasia']."</td>";
				$html .= "<td>".$cliente['endereco']."</td>";
				$html .= "<td>".$cliente['numero'].' '.$cliente['complemento']."</td>";
				$html .= "<td>".$cliente['bairro']."</td>";
				$html .= "<td>".$cliente['telefone'].' | '.$cliente['celular']."</td>";
				$html .= "<td>".$cliente['email']."</td>";
			
				$contratoId = $contrato['id'];
			
			    $hoje= date( "Y/m/d");
			
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato='$contratoId' AND vencimento>'$hoje'");
			
				$tipoColetaId=$contratoColeta['tipo_coleta'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
			
				if($tipoColeta['residuo']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($tipoColeta['residuo']<>1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}

				$html .= "<td>".converteData($contratoColeta['inicio'])."</td>";
				$html .= "<td>".converteData($contratoColeta['vencimento'])."</td>";
			
				// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
				$frequencia = $contrato[ 'frequencia' ];
				if($frequencia==3){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($frequencia==2){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($frequencia==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				
				if($contrato['segunda']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($contrato['terca']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($contrato['quarta']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($contrato['quinta']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($contrato['sexta']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($contrato['sabado']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
				if($contrato['domingo']==1){
					$html .= "<td>X</td>";
				}else{
					$html .= "<td></td>";
				}
			
				$diaSemana='';
				if($contrato['domingo']==1){
					$diaSemana = ' Dom';
				}
				if($contrato['segunda']==1){
					$diaSemana = $diaSemana . ' Seg';
				}
				if($contrato['terca']==1){
					$diaSemana = $diaSemana . ' Ter';
				}
				if($contrato['quarta']==1){
					$diaSemana = $diaSemana . ' Qua';
				}
				if($contrato['quinta']==1){
					$diaSemana = $diaSemana . ' Qui';
				}
				if($contrato['sexta']==1){
					$diaSemana = $diaSemana . ' Sex';
				}
				if($contrato['sabado']==1){
					$diaSemana = $diaSemana . ' Sab';
				}
			
				$html .= "<td>".$diaSemana."</td>";
			
				$html .= "<td>".$tipoColeta['volume_litros']."</td>";
				$html .= "<td>".$tipoColeta['nome']."</td>";
				
				$html .= "<td>".$contratoColeta['quantidade']."</td>";
				$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";
				$html .= "<td>".converteValor($contratoColeta['valor_mensal'])."</td>";
			
			
				$hoje= date( "Y/m/d");
			
				$receber = mostra('receber',"WHERE id_contrato='$contratoId' ORDER BY vencimento ASC");
			
				$html .= "<td>".converteValor($receber['valor'])."</td>";
			
				$html .= "<td>".converteData($contrato['data_cancelamento'])."</td>";
			
				$consultorId=$contrato['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				if($consultor){
					$html .= "<td>".$consultor['nome']."</td>";
				}else{
					$html .= "<td>".$consultorId."</td>";
				}
			
				
				if($contrato['segunda']=='1'){
					$rotaId = $contrato[ 'segunda_rota1' ];
					$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
					$html .= "<td>".$rota[ 'nome']."</td>";
					$rota= $rota[ 'nome' ];
				}else{
					$html .= "<td>-</td>";
				}
				if($contrato['terca']=='1'){
					$rotaId = $contrato[ 'terca_rota1' ];
					$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
					$html .= "<td>".$rota[ 'nome']."</td>";
					$rota= $rota[ 'nome' ];
				}else{
					$html .= "<td>-</td>";
				}
				if($contrato['quarta']=='1'){
					$rotaId = $contrato[ 'quarta_rota1' ];
					$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
					$html .= "<td>".$rota[ 'nome']."</td>";
					$rota= $rota[ 'nome' ];
				}else{
					$html .= "<td>-</td>";
				}
				if($contrato['quinta']=='1'){
					$rotaId = $contrato[ 'quinta_rota1' ];
					$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
					$html .= "<td>".$rota[ 'nome']."</td>";
					$rota= $rota[ 'nome' ];
				}else{
					$html .= "<td>-</td>";
				}
				if($contrato['sexta']=='1'){
					$rotaId = $contrato[ 'sexta_rota1' ];
					$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
					$html .= "<td>".$rota[ 'nome']."</td>";
					$rota= $rota[ 'nome' ];
				}else{
					$html .= "<td>-</td>";
				}
				if($contrato['sabado']=='1'){
					$rotaId = $contrato[ 'sabado_rota1' ];
					$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
					$html .= "<td>".$rota[ 'nome']."</td>";
					$rota= $rota[ 'nome' ];
				}else{
					$html .= "<td>-</td>";
				}
				if($contrato['domingo']=='1'){
					$rotaId = $contrato[ 'domingo_rota1' ];
					$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
					$html .= "<td>".$rota[ 'nome']."</td>";
					$rota = $rota[ 'nome' ];
				}else{
					$html .= "<td>-</td>";
				}
			
			$html .= "<td>".$cliente['email']."</td>";

			if($cliente['nao_enviar_email']=='1'){
				$html .= "<td>Nao Enviar Email</td>";
			}elseif($cliente['nao_enviar_email']=='0'){
				$html .= "<td>Sem Restricaoo</td>";
			}else{
				$html .= "<td>-</td>";
			}

			  
			$html .= "</tr>";

		}

	
endforeach;

echo $html;

?>