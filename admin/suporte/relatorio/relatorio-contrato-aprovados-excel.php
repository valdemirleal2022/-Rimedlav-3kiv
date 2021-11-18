<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

if(isset($_SESSION['inicio'])){
	$data1 = $_SESSION['inicio'];
}else{
	$data1 = date("Y-m-d");
}
if(isset($_SESSION['fim'])){
	$data2 = $_SESSION['fim'];
}else{
	$data2 = date("Y-m-d");
}

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1' 
											AND aprovacao<='$data2'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1' AND aprovacao<='$data2'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1'  
											AND aprovacao<='$data2' ORDER BY aprovacao ASC");

$nome_arquivo = "contrato-aprovados";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";

	$html .= "<td>Aprovacao</td>";
	$html .= "<td>Inicio</td>";

	$html .= "<td>Tipo Contrato</td>";

	$html .= "<td>Nome</td>";
	$html .= "<td>Email</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Consultor</td>";

	$html .= "<td>Tipo de Coleta</td>";
	$html .= "<td>Quantidade</td>";
	$html .= "<td>Frequencia</td>";
	
	$html .= "<td>Valor Unitario</td>";
	$html .= "<td>Valor Mensal</td>";

	$html .= "<td>Contrato</td>";

$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".converteData($mostra['aprovacao'])."</td>";
		$html .= "<td>".converteData($mostra['inicio'])."</td>";

		$contratoTipoId = $mostra['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
		$html .= "<td>".$contratoTipo['nome']."</td>";
		
		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";
		$html .= "<td>".$cliente['email']."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		

		$contratoId = $mostra['id'];

		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";
		$html .= "<td>".$contratoColeta['quantidade']."</td>";

		// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
		$frequenciaId = $mostra[ 'frequencia' ];
		$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
		$frequencia = $frequencia[ 'nome' ];

		$html .= "<td>".$frequencia."</td>";

		$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";

		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";

		$diaSemana='';
		$rota='';
		$hora='';
		if($mostra['domingo']==1){
			$diaSemana = ' Dom';
			$hora=$mostra['domingo_hora1'];
			$rotaId = $mostra[ 'domingo_rota1' ];
			$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rotaMostra[ 'nome' ];
		}
		if($mostra['segunda']==1){
			$diaSemana = $diaSemana . ' Seg';
			$hora=$mostra['segunda_hora1'];
			$rotaId = $mostra[ 'segunda_rota1' ];
			$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rotaMostra[ 'nome' ];
		}
		if($mostra['terca']==1){
			$diaSemana = $diaSemana . ' Ter';
			$hora=$mostra['terca_hora1'];
			$rotaId = $mostra[ 'terca_rota1' ];
			$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rotaMostra[ 'nome' ];
		}
		if($mostra['quarta']==1){
			$diaSemana = $diaSemana . ' Qua';
			$hora=$mostra['quarta_hora1'];
			$rotaId = $mostra[ 'quarta_rota1' ];
			$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rotaMostra[ 'nome' ];
		}
		if($mostra['quinta']==1){
			$diaSemana = $diaSemana . ' Qui';
			$hora=$mostra['quinta_hora1'];
			$rotaId = $mostra[ 'quinta_rota1' ];
			$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rotaMostra[ 'nome' ];
		}
		if($mostra['sexta']==1){
			$diaSemana = $diaSemana . ' Sex';
			$hora=$mostra['sexta_hora1'];
			$rotaId = $mostra[ 'sexta_rota1' ];
			$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rotaMostra[ 'nome' ];
		}
		if($mostra['sabado']==1){
			$diaSemana = $diaSemana . ' Sab';
			$hora=$mostra['sabado_hora1'];
			$rotaId = $mostra[ 'sabado_rota1' ];
			$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rotaMostra[ 'nome' ];
		}

		

		$html .= "<td>".$mostra['id'].'|'.substr($mostra['controle'],0,6)."</td>";
		

	$html .= "</tr>";
endforeach;

echo $html;

?>