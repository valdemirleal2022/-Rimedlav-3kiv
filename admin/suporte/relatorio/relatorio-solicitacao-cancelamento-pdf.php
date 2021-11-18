<?php


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Solicitação de Cancelamento";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$total = conta('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
												AND data_solicitacao<='$data2'");
$leitura = read('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");

if($_SESSION['ordem']=='2') {
	$leitura = read('contrato_cancelamento',"WHERE id AND data_encerramento>='$data1' 
					 AND data_encerramento<='$data2' ORDER BY data_encerramento ASC");
	$total = conta('contrato_cancelamento',"WHERE id AND data_encerramento>='$data1' 
												AND data_encerramento<='$data2'");
}

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->Ln(5);

$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id | Controle');
$pdf->SetX(28);
$pdf->Cell(30, 1, 'Nome');
$pdf->SetX(77);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(97);
$pdf->Cell(20, 1, 'Coleta');
$pdf->SetX(130);
$pdf->Cell(20, 1, 'Vl Mensal');
$pdf->SetX(149);
$pdf->Cell(20, 1, 'Solicitacao');
$pdf->SetX(170);
$pdf->Cell(20, 1, 'Encerramento');
$pdf->SetX(196);
$pdf->Cell(10, 1, 'Rota');
$pdf->SetX(208);
$pdf->Cell(10, 1, 'Status');
$pdf->SetX(223);
$pdf->Cell(10, 1, 'Recuperada');
$pdf->SetX(245);
$pdf->Cell(10, 1, 'Motivo');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

$valor_total=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId '");

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id'].'|'.substr($contrato['controle'],0,6));

	$pdf->SetX(28);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$consultorId=$contrato['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->SetX(77);
	$pdf->Cell(10, 5, $consultor['nome']);

	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
	$tipoColetaId = $contratoColeta['tipo_coleta'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(97);
	$pdf->Cell(10, 5, substr($coleta['nome'],0,25));

	$pdf->SetX(137);
	$pdf->Cell(10, 5, converteValor($contrato['valor_mensal']));

	$valor_total=$valor_total+$contrato['valor_mensal'];
	
	$pdf->SetX(149);
	$pdf->Cell(10, 5, converteData($mostra['data_solicitacao']));

	$pdf->SetX(174);
	$pdf->Cell(10, 5, converteData($mostra['data_encerramento']));


	
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

	$pdf->SetX(196);
	$pdf->Cell(10, 5,$rota );

	$pdf->SetX(211);
	$pdf->Cell(10, 5, $mostra['status']);

	if($mostra['recuperada']=='1'){
	 	$pdf->SetX(228);
		$pdf->Cell(10, 5,'SIM');
	}else{
		$pdf->SetX(228);
		$pdf->Cell(10, 5,'NAO');
	}

	$motivoId=$mostra['motivo'];
	$motivo = mostra('contrato_cancelamento_motivo',"WHERE id ='$motivoId'");
	$pdf->SetX(245);
	$pdf->Cell(10, 5, substr($motivo['nome'],0,25));
	
	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(1);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Ln(5);

		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id | Controle');
		$pdf->SetX(28);
		$pdf->Cell(30, 1, 'Nome');
		$pdf->SetX(77);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(97);
		$pdf->Cell(20, 1, 'Coleta');
		$pdf->SetX(130);
		$pdf->Cell(20, 1, 'Vl Mensal');
		$pdf->SetX(149);
		$pdf->Cell(20, 1, 'Solicitacao');
		$pdf->SetX(170);
		$pdf->Cell(20, 1, 'Encerramento');
		$pdf->SetX(196);
		$pdf->Cell(10, 1, 'Rota');
		$pdf->SetX(208);
		$pdf->Cell(10, 1, 'Status');
		$pdf->SetX(223);
		$pdf->Cell(10, 1, 'Recuperada');
		$pdf->SetX(245);
		$pdf->Cell(10, 1, 'Motivo');

		$pdf->Line(5, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
		
	}

endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valor_total));
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>