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
	  $titulo="Renovação de Contrato";
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


$valor_total = soma('contrato_coleta',"WHERE id AND vencimento>='$data1' AND
																vencimento<='$data2'",'valor_mensal');
$total = conta('contrato_coleta',"WHERE id AND vencimento>='$data1' 
																AND vencimento<='$data2'");
$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1'
																AND vencimento<='$data2' ORDER BY vencimento ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(5);
$pdf->Cell(20, 1, 'Id|Controle');
$pdf->SetX(25);
$pdf->Cell(25, 1, 'Nome');
$pdf->SetX(100);
$pdf->Cell(20, 1, 'Coleta');

$pdf->SetX(136);
$pdf->Cell(20, 1, 'Unitario');
$pdf->SetX(155);
$pdf->Cell(20, 1, 'Mensal');
$pdf->SetX(171);
$pdf->Cell(10, 1, 'Vencimento');

$pdf->SetX(195);
$pdf->Cell(10, 1, 'Reajuste');

$pdf->SetX(212);
$pdf->Cell(20, 1, 'Unitario');
$pdf->SetX(230);
$pdf->Cell(20, 1, 'Mensal');
$pdf->SetX(245);
$pdf->Cell(10, 1, 'Renovação');

$pdf->SetX(265);
$pdf->Cell(10, 1, 'Status');
$pdf->Line(5, 33, 290, 33); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$contratoId=$mostra['id_contrato'];

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
	$clienteId = $mostra['id_cliente'];

	$pdf->SetX(12);
	$pdf->Cell(10, 5, $contrato['id'].'|'.substr($contrato['controle'],0,6), 0, 0, 'R');
	
	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->SetX(25);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,30));

	$tipoColetaId = $mostra['tipo_coleta'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(100);
	$pdf->Cell(10, 5, $coleta['nome']);

	$pdf->SetX(140);
	$pdf->Cell(10, 5, converteValor($mostra['valor_unitario']),0,0,'R');
	
	$pdf->SetX(157);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal']),0,0,'R');

	$pdf->SetX(176);
	$pdf->Cell(10, 5, converteData($mostra['vencimento']));

	$vencimento=$mostra['vencimento'];
	$coletaRenovacao = mostra('contrato_coleta',"WHERE id AND id_contrato='$contratoId'												AND vencimento>'$vencimento'");
	if($coletaRenovacao){
		$pdf->SetX(198);
		$pdf->Cell(10, 5, converteValor($coletaRenovacao['percentual']),0,0,'R');
		$pdf->SetX(215);
		$pdf->Cell(10, 5, converteValor($coletaRenovacao['valor_unitario']),0,0,'R');
		$pdf->SetX(235);
		$pdf->Cell(10, 5, converteValor($coletaRenovacao['valor_mensal']),0,0,'R');
		$pdf->SetX(254);
		$pdf->Cell(10, 5, converteData($coletaRenovacao['vencimento']),0,0,'R');
	}else{
		$pdf->SetX(195);
		$pdf->Cell(10, 5, '-');
		$pdf->SetX(217);
		$pdf->Cell(10, 5, '-');
		$pdf->SetX(235);
		$pdf->Cell(10, 5, '-');
		$pdf->SetX(255);
		$pdf->Cell(10, 5, '-');
	}
	
	$statusId=$contrato['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId'");
	$pdf->SetX(265);
	$pdf->Cell(10, 5, $status['nome']);


	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(1);
		$pdf->Line(0, 25, 220, 25); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(25);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(70);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(130);
		$pdf->Cell(20, 1, 'Valor Mensal');
		$pdf->SetX(160);
		$pdf->Cell(20, 1, 'Aprovação');
		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Inicio');
		$pdf->SetX(200);
		$pdf->Cell(10, 1, 'Status');
		$pdf->Line(0, 33, 220, 33); // insere linha divisória
		$pdf->Ln(4);~
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . number_format($valor_total,2,',','.'));
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>