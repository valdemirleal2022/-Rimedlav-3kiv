<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$dataRota=$_SESSION[ 'dataRota' ];
$rotaId = $_SESSION[ 'rotaRoteiro' ];

	$dia_semana = diaSemana($dataRota);
	$numero_semana = numeroSemana($dataRota);
	
	//DOMINGO

	if ( $numero_semana == 0 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId' OR domingo_rota2='$rotaId' 
										ORDER BY domingo_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId' OR domingo_rota2='$rotaId' ");
	}

	if ( $numero_semana == 1 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId' OR segunda_rota2='$rotaId' 
										ORDER BY segunda_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId' OR segunda_rota2='$rotaId'");
	}

	// TERCA
	if ( $numero_semana == 2 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND terca='1' 
										AND terca_rota1='$rotaId' OR terca_rota2='$rotaId' 
											ORDER BY terca_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND terca='1' 
											AND terca_rota1='$rotaId' OR terca_rota2='$rotaId'");
	}

	//QUARTA
	if ( $numero_semana == 3 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND quarta='1' 
										AND quarta_rota1='$rotaId' OR quarta_rota2='$rotaId' 
										ORDER BY quarta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quarta='1' 
										AND  quarta_rota1='$rotaId' OR quarta_rota2='$rotaId'");
	}
	
	//QUINTA
	if ( $numero_semana == 4 ) {

		$leitura = read( 'contrato', "WHERE id AND status='5' AND quinta='1' 
										AND quinta_rota1='$rotaId' OR quinta_rota2='$rotaId' 
										ORDER BY quinta_hora2, quinta_hora1 ASC");
		
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quinta='1' 
										AND  quinta_rota1='$rotaId' OR quinta_rota2='$rotaId'");
	}
	
	//SEXTA
	if ( $numero_semana == 5 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sexta='1' 
										AND sexta_rota1='$rotaId' OR sexta_rota2='$rotaId'
										ORDER BY sexta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sexta='1' 
										AND  sexta_rota1='$rotaId' OR sexta_rota2='$rotaId'");
	}
	
	//SABADO
	if ( $numero_semana == 6 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sabado='1' 
										AND sabado_rota1='$rotaId' OR sabado_rota2='$rotaId' 
										ORDER BY sabado_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sabado='1' 
										AND  sabado_rota1='$rotaId' OR sabado_rota2='$rotaId' ");
	}

$rotaMostra = mostra('contrato_rota',"WHERE id ='$rotaId'");

$nome_arquivo = "contrato-rota";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Hora</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Destinario</td>";
	
	$html .= "<td>Endereco</td>";
	$html .= "<td>Cep</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Cidade</td>";

	$html .= "<td>Coleta</td>";
	$html .= "<td>Frequencia</td>";
	$html .= "<td>Contrato</td>";
	$html .= "<td>Email</td>";
	$html .= "<td>Email Financeiro</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		if ( $numero_semana == 0 ) {
					$hora = $mostra['domingo_hora1'];
					$rota = $mostra['domingo_rota1'];
					if($mostra['domingo_rota2']==$rotaId){
						$hora = $mostra['domingo_hora2'];
						$rota = $mostra['domingo_rota2'];
					}
				}
				if ( $numero_semana == 1 ) {
					$hora = $mostra['segunda_hora1'];
					$rota = $mostra['segunda_rota1'];
					if($mostra['segunda_rota2']==$rotaId){
						$hora = $mostra['segunda_hora2'];
						$rota = $mostra['segunda_rota2'];
					}
				}
		
				if ( $numero_semana == 2 ) {
					$hora = $mostra['terca_hora1'];
					$rota = $mostra['terca_rota1'];
					if($mostra['terca_rota2']==$rotaId){
						$hora = $mostra['terca_hora2'];
						$rota = $mostra['terca_rota2'];
					}
				}
		
				if ( $numero_semana == 3 ) {
					$hora = $mostra['quarta_hora1'];
					$rota = $mostra['quarta_rota1'];
					if($mostra['quarta_rota2']==$rotaId){
						$hora = $mostra['quarta_hora2'];
						$rota = $mostra['quarta_rota2'];
					}
				}
		
				if ( $numero_semana == 4 ) { //QUINTA
					$hora = $mostra['quinta_hora1'];
					$rota = $mostra['quinta_rota1'];
					
					if($mostra['quinta_rota2']==$rotaId){
						$hora = $mostra['quinta_hora2'];
						$rota = $mostra['quinta_rota2'];
					}
				}
				if ($numero_semana == 5 ) {
					$hora = $mostra['sexta_hora1'];
					$rota = $mostra['sexta_rota1'];
					
					if($mostra['sexta_rota2']==$rotaId){
						$hora = $mostra['sexta_hora2'];
						$rota = $mostra['sexta_rota2'];
					}

				}
				if ( $numero_semana == 6 ) {
					$hora = $mostra['sabado_hora1'];
					$rota = $mostra['sabado_rota1'];
					if($mostra['sabado_rota2']==$rotaId){
						$hora = $mostra['sabado_hora2'];
						$rota = $mostra['sabado_rota2'];
					}
				}

		$html .= "<td>".$hora."</td>";
		$html .= "<td>". $rotaMostra['nome']."</td>";

		$contratoId = $mostra['id'];
		$clienteId = $mostra['id_cliente'];

		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['cep']."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['cidade']."</td>";

		

		$contratoId = $mostra['id'];
		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato  ='$contratoId'");

		$tipoColetaId = $contratoColeta['tipo_coleta'];
		$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$tipoColeta['nome']."</td>";

		$frequenciaId = $mostra['frequencia'];
		$frequencia= mostra('contrato_frequencia',"WHERE id ='$frequenciaId'");
		$html .= "<td>".$frequencia['nome']."</td>";

		$html .= "<td>".$mostra['id'].'|'.substr($mostra['controle'],0,6)."</td>";	

		$html .= "<td>".$cliente['email']."</td>";

		$html .= "<td>".$cliente['email_financeiro']."</td>";
	 
	$html .= "</tr>";
endforeach;

echo $html;

?>