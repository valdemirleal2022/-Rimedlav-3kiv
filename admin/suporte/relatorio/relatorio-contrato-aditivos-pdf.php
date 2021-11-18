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
	  $titulo="Contratos Aditivos";
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

$leitura = read('contrato_aditivo',"WHERE id AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' ORDER BY aprovacao ASC");
$total = conta('contrato_aditivo',"WHERE id AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' ORDER BY aprovacao ASC");


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

$pdf->SetX(12);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(55);
$pdf->Cell(20, 1, 'Aprovação');

$pdf->SetX(75);
$pdf->Cell(20, 1, 'Início');

$pdf->SetX(94);
$pdf->Cell(20, 1, 'Motivo');

$pdf->SetX(150);
$pdf->Cell(20, 1, 'Frequencia');

$pdf->SetX(185);
$pdf->Cell(20, 1, 'Tipo Coleta');

$pdf->SetX(215);
$pdf->Cell(20, 1, 'Quant');

$pdf->SetX(232);
$pdf->Cell(10, 1, 'Vl Unitário');
$pdf->SetX(253);
$pdf->Cell(10, 1, 'Vl Extra');
$pdf->SetX(270);
$pdf->Cell(10, 1, 'Vl Mensal');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id']);

	$contratoId = $mostra['id'];
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->SetX(12);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));

	$pdf->SetX(55);
	$pdf->Cell(10, 5,converteData($mostra['aprovacao']));

	$pdf->SetX(75);
	$pdf->Cell(10, 5,converteData($mostra['inicio']));

	$motivoId=$mostra['motivo'];		
	$motivo= mostra('contrato_aditivo_motivo',"WHERE id ='$motivoId'");
					
	$pdf->SetX(94);
	$pdf->Cell(10, 5, substr($motivo['nome'],0,25));

	$pdf->SetX(150);
	$pdf->Cell(10, 5, substr($mostra['frequencia_aditivo'],0,10));

	$tipoColetaId=$mostra['tipo_coleta_aditivo'];		
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
			
	$pdf->SetX(185);
	$pdf->Cell(10, 5,$tipoColeta['nome']);

	$pdf->SetX(225);
	$pdf->Cell(10, 5,$mostra['quantidade_aditivo']);

	$pdf->SetX(240);
	$pdf->Cell(10, 5, converteValor($mostra['valor_unitario_aditivo']),0,0,'R');
		 
	$pdf->SetX(260);
	$pdf->Cell(10, 5, converteValor($mostra['valor_extra_aditivo']),0,0,'R');

	$pdf->SetX(280);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal_aditivo']),0,0,'R');
	
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

		$pdf->SetX(12);
		$pdf->Cell(20, 1, 'Nome');

		$pdf->SetX(55);
		$pdf->Cell(20, 1, 'Aprovação');

		$pdf->SetX(80);
		$pdf->Cell(20, 1, 'Endereço');

		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Frequencia');

		$pdf->SetX(185);
		$pdf->Cell(20, 1, 'Tipo Coleta');

		$pdf->SetX(210);
		$pdf->Cell(20, 1, 'Quantidade');

		$pdf->SetX(232);
		$pdf->Cell(10, 1, 'Vl Unitário');
		$pdf->SetX(253);
		$pdf->Cell(10, 1, 'Vl Extra');
		$pdf->SetX(270);
		$pdf->Cell(10, 1, 'Vl Mensal');

		$pdf->Line(5, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	 
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>