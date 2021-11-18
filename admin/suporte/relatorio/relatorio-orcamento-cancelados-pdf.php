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
	  $titulo="Relatorio de Orçamento Cancelados";
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

$leitura = read( 'cadastro_visita', "WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2' ORDER BY orc_solicitacao ASC" );

$total = conta( 'cadastro_visita', "WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2'" );
$valor_total = soma('cadastro_visita',"WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2'",'orc_valor');

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->SetX(32);
$pdf->Cell(22, 1, converteData($data1));
$pdf->SetX(70);
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->SetX(88);
$pdf->Cell(22, 1, converteData($data2));
$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Valor');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Solicitação');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Orçamento/Hora');
$pdf->SetX(140);
$pdf->Cell(20, 1, 'Indicacao');
$pdf->SetX(170);
$pdf->Cell(20, 1, 'Tipo de Resíduo');
$pdf->SetX(210);
$pdf->Cell(20, 1, 'Vendedor');
$pdf->SetX(260);
$pdf->Cell(20, 1, 'Status');
$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($mostra['nome'],0,30));

	$pdf->SetX(75);
	$pdf->Cell(10, 5, converteValor($mostra['orc_valor']) );

	$pdf->SetX(90);
	$pdf->Cell(10, 5, converteData($mostra['orc_solicitacao']) );

	$pdf->SetX(110);
	$pdf->Cell(10, 5, converteData($mostra['orc_data']).'/'.$mostra['orc_hora'] );

	$indicacaoId = $mostra['indicacao'];
	$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
	$pdf->SetX(140);
	$pdf->Cell(10, 5, substr($indicacao['nome'],0,10) );

	$pdf->SetX(170);
	$pdf->Cell(10, 5, substr($mostra['orc_residuo'],0,15) );

	$pdf->SetX(210);
	$consultorId = $mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(260);
	$statusId = $mostra['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId'");
	$pdf->Cell(10, 5, $status['nome']);

	

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->SetX(32);
		$pdf->Cell(22, 1, converteData($data1));
		$pdf->SetX(70);
		$pdf->Cell(22, 1, 'Data Fim :');
		$pdf->SetX(88);
		$pdf->Cell(22, 1, converteData($data2));
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Valor');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Solicitação');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Orçamento/Hora');
$pdf->SetX(140);
$pdf->Cell(20, 1, 'Indicacao');
$pdf->SetX(170);
$pdf->Cell(20, 1, 'Tipo de Resíduo');
$pdf->SetX(210);
$pdf->Cell(20, 1, 'Vendedor');
$pdf->SetX(260);
$pdf->Cell(20, 1, 'Status');
		$pdf->Line(10, 33, 290, 33); // insere linha divisória
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