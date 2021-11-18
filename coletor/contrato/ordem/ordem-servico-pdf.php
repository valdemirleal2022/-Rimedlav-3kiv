<?php

require_once("fpdf/fpdf.php");

define("FPDF_FONTPATH","font/");

$dataroteiro=$_SESSION['dataroteiro'];
$rotaId=$_SESSION['rotaColeta'];


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $data=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$data,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="ORDEM DE SERVIÇO";
      $this->Ln(8);
	  $this->Cell(0,5,$titulo,0,0,'C'); 
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$empresa = mostra('empresa',"WHERE id");

if(!empty($_GET['ordemImprimir'])){
	$ordemId = $_GET['ordemImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND id='$ordemId'");
}

//$pdf = new FPDF('P','mm','A4');
$pdf = new FPDF('P','mm',array(100,190));
$pdf->AddPage(); 
$pdf->SetMargins(10, 10, 5, 5);
$pdf->SetFont('Arial','B',20);

$i=1;



foreach($leitura as $mostra):
 
	$pdf->SetX(1);
	$pdf->Cell(5, 5, '======================');
	$pdf->ln();
    $pdf->ln();

	$pdf->SetFont('Arial','B',12);

	$pdf->SetX(60);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s'));
    $pdf->ln();
    $pdf->ln();

	$pdf->SetFont('Arial','B',20);

	$pdf->SetX(1);
	$pdf->Cell(10, 5, $empresa['nome']);
    $pdf->ln();
    $pdf->ln();

	$pdf->SetX(1);
	$pdf->Cell(10, 5, $empresa['telefone']);
	$pdf->ln();
    $pdf->ln();
	
	$pdf->SetX(1);
	$pdf->Cell(5, 5, '======================');
	$pdf->ln();
    $pdf->ln();

    $contratoId=$mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

 	$contratoTipo=$contrato['contrato_tipo'];
	$tipoResiduo = mostra('contrato_tipo',"WHERE id ='$contratoTipo'");

	$pdf->SetFont('Arial','B',15);
	$pdf->SetX(1);
	$pdf->Cell(5, 5, 'Resíduo : '. $tipoResiduo['nome']);
	$pdf->ln();
    $pdf->ln();

	$pdf->SetX(1);
	$pdf->Cell(10, 5, 'Número : '.$mostra['id']);
	$pdf->ln();
    $pdf->ln();

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(1);
	$pdf->Cell(10, 5, $cliente['nome']);
 	$pdf->ln();
    $pdf->ln();

	$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

	$pdf->SetX(1);
	$pdf->Cell(10, 5,  $endereco);
    $pdf->ln();
    $pdf->ln();

    $tipoColeta=$mostra['tipo_coleta1'];
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta'");

	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

	$pdf->SetX(1);
	$pdf->Cell(5, 5, 'Coleta : '. $tipoColeta['nome']);
	$pdf->ln();
	$pdf->ln();

	$pdf->SetX(1);
	$pdf->Cell(5, 5, 'Quantidade Coletado : '.$mostra['quantidade1']);
	$pdf->ln();
    $pdf->ln();
	$pdf->ln();
    $pdf->ln();
	$pdf->ln();
    $pdf->SetX(1);
	$pdf->Cell(5, 5, '===========================');
	$pdf->ln();
    $pdf->ln();

    break;

endforeach;

ob_clean();  
$pdf->Output('ordem-contrato.pdf', 'I');
 
?>