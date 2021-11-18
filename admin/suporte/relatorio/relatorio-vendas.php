<?php

require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$consultorId = $_GET['consultorId'];
$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
$nomeconsultor=$consultor['nome'];

$data1 = $_SESSION['datavenda1'];
$data2 = $_SESSION['datavenda2'];

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Relatório por Consultor";
      $this->Ln(8);
	  $this->Cell(0,5,$titulo,0,0,'C'); 
      $this->Ln(9);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$valor_total = soma('contrato',"WHERE tipo<>'1' AND tipo<>'3' AND situacao<>'4' AND contrato>='$data1' AND contrato<='$data2' AND consultor='$consultorId'",'valor');
$total = conta('contrato',"WHERE tipo<>'1' AND tipo<>'3' AND situacao<>'4' AND contrato>='$data1' AND contrato<='$data2' AND consultor='$consultorId'");
$leitura = read('contrato',"WHERE tipo<>'1' AND tipo<>'3' AND situacao<>'4' AND contrato>='$data1' AND contrato<='$data2' AND consultor='$consultorId' ORDER BY contrato ASC, tipo ASC");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Ln(1);
$pdf->Line(0, 25, 220, 25); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(25);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Valor');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Tipo');
$pdf->SetX(140);
$pdf->Cell(20, 1, 'Status');
$pdf->SetX(180);
$pdf->Cell(20, 1, 'Recebimento');
$pdf->Line(0, 33, 220, 33); // insere linha divisória
$pdf->Ln(4);~
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();
	$pdf->Cell(10, 5, $mostra['id']);
	$pdf->SetX(25);
	$contratoId = $mostra['id'];
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));
	$pdf->SetX(70);
	$pdf->Cell(10, 5, converteData($mostra['contrato']));
	$pdf->SetX(90);
	$pdf->Cell(10, 5, converteValor($mostra['valor']));
	$pdf->SetX(110);
	if($mostra['tipo']==2){
		$pdf->Cell(10, 5, 'Serviço');
	}
	if($mostra['tipo']==3){
		$pdf->Cell(10, 5, 'Atendimento');
	}
	if($mostra['tipo']==5){
		$pdf->Cell(10, 5, 'Manutenção');
	}
	
	$statusId = $mostra['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId '");
	$pdf->SetX(130);
	$pdf->Cell(10, 5, $status['nome']);

	$consultorId = $mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
	$pdf->SetX(180);
	$pdf->Cell(10, 5, $consultor['nome']);

	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(1);
		$pdf->Line(0, 25, 220, 25); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(25);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(80);
		$pdf->Cell(20, 1, 'contrato');
		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'valor');
		$pdf->SetX(130);
		$pdf->Cell(20, 1, 'status');
		$pdf->SetX(160);
		$pdf->Cell(20, 1, 'Banco');
		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Forma Pag');
		$pdf->Line(0, 33, 220, 33); // insere linha divisória
		$pdf->Ln(4);~
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros :  '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ :  ' . number_format($valor_total,2,',','.'));
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>