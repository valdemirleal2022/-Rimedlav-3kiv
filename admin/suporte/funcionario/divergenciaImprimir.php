<?php

require_once("js/fpdf/fpdf.php");

define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $data=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$data,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="ORDEM DE SERVIЧO";
      $this->Ln(8);
	  $this->Cell(0,5,$titulo,0,0,'C'); 
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Pсgina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

if(!empty($_GET['divergencia'])){
	$divergenciaId = $_GET['divergencia'];
	$divergencia=mostra('funcionario_divergencia',"WHERE id AND id='$divergenciaId'");
}

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage(); 
$pdf->SetMargins(10, 10, 5, 5);
$pdf->SetFont('Arial','B',9);

$empresa = mostra('empresa',"WHERE id");

	
	
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();

	$pdf->SetX(172);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s'));
    $pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $empresa['nome'] );
    $pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $empresa['endereco']);
	$pdf->SetX(85);
	$pdf->Cell(10, 5, $empresa['bairro']);
	$pdf->SetX(105);
	$pdf->Cell(10, 5, $empresa['cidade']);
    $pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $empresa['telefone']);
	
	$pdf->ln();
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	
	$pdf->SetFont('Arial','B',12);
	$pdf->ln();
	$pdf->ln();

	$funcionarioId = $divergencia['id_funcionario'];
	$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId '");
	
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Nome :');
	$pdf->SetX(22);
	$pdf->Cell(10, 5, $funcionario['nome']);
		
	$pdf->ln();


	$pdf->SetFont('Arial','B',9);
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Id :');
	$pdf->SetX(12);
	$pdf->Cell(10, 5, $divergencia['id']);

	$pdf->SetX(55);
	$pdf->Cell(10, 5, 'Status :');
	$pdf->SetX(70);
	$pdf->Cell(10, 5, $divergencia['status']);


	$pdf->SetX(150);
	$pdf->Cell(10, 5, 'Data da Solicitaчуo :');
	$pdf->SetX(182);
	$pdf->Cell(10, 5, converteData($divergencia['data_solicitacao']));

	$divergenciaId = $divergencia['id_divergencia'];
	$motivo_divergencia = mostra('funcionario_divergencia_motivo',"WHERE id ='$divergenciaId '");

    $pdf->ln();
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Divergencia :');
	$pdf->SetX(30);
	$pdf->Cell(10, 5, $motivo_divergencia['nome']);

	$pdf->ln();
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Solicitaчуo :');
	$pdf->SetX(25);

	$pdf->MultiCell(0,5,$divergencia['solicitacao'],0,1);



 	$pdf->ln();
	$pdf->ln();
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Procedente/Improcedente :');
	$pdf->SetX(50);
	if($divergencia['procedente'] == '1'){
		$pdf->Cell(10, 5, 'Procedente');
	}
	if($divergencia['procedente'] == '0'){
		$pdf->Cell(10, 5, 'Improcedente');
	}
 
	$pdf->SetX(150);
	$pdf->Cell(10, 5, 'Data Soluчуo : ' .converteData($divergencia['data_solucao']));
		
	$pdf->ln();
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Soluчуo :');
	$pdf->SetX(25);
	$pdf->MultiCell(0,5,$divergencia['solucao'],0,1);

 	$pdf->ln();

	
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();

ob_clean();  
$pdf->Output('ordem-contrato.pdf', 'I');
 
?>