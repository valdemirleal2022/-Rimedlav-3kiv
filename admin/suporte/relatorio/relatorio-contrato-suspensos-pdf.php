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
	  $titulo="Contratos Suspensos";
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

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='6' AND data_suspensao>='$data1' 
											  AND data_suspensao<='$data2' AND status='6'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
												AND data_suspensao<='$data2' AND status='6'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
											  AND data_suspensao<='$data2' AND status='6' 
											  ORDER BY data_suspensao ASC");

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
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(15);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Valor Mensal');
$pdf->SetX(135);
$pdf->Cell(20, 1, 'A partir');
$pdf->SetX(155);
$pdf->Cell(10, 1, 'Motivo');
$pdf->SetX(250);
$pdf->Cell(10, 1, 'Rota');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Tipo de Contrato');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id']);

	$contratoId = $mostra['id'];

	$contrato = mostra('contrato',"WHERE id = '$contratoId'");

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->SetX(15);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(65);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$pdf->SetX(90);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(118);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal']));
	
	$pdf->SetX(135);
	$pdf->Cell(10, 5, converteData($mostra['data_suspensao']));

	$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='2' 
					ORDER BY interacao ASC");

	$pdf->SetX(155);
	$pdf->Cell(10, 5,substr($contratoBaixa['motivo'],0,45) );

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

	$pdf->SetX(250);
	$pdf->Cell(10, 5,$rota );

	$tipoContratoId=$mostra['contrato_tipo'];
	$tipoContrato= mostra('contrato_tipo',"WHERE id ='$tipoContratoId'");
	$pdf->SetX(260);
	$pdf->Cell(10, 5, $tipoContrato['nome']);


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->Cell(22, 1, converteData($data1));
		$pdf->Cell(22, 1, 'Data Fim :');
		$pdf->Cell(22, 1, converteData($data2));

		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Ln(5);

		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(90);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Valor Mensal');
		$pdf->SetX(135);
		$pdf->Cell(20, 1, 'A partir');
		$pdf->SetX(155);
		$pdf->Cell(10, 1, 'Motivo');
		$pdf->SetX(260);
		$pdf->Cell(10, 1, 'Interação');

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