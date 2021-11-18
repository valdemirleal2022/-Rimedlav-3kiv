<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$nomeUsuario = $_SESSION['nomeUsuario'];

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
	  $titulo="Relatório de Interações";
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

$total = conta('interacao',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2'");
$leitura = read('interacao',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2' ORDER BY data DESC");
		
if (!empty($nomeUsuario) ) {
	$total = conta('interacao',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2' AND usuario='$nomeUsuario'");
	$leitura = read('interacao',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2' 
			AND usuario='$nomeUsuario' ORDER BY data DESC");
}

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
$pdf->Cell(22, 1, converteData($data2) );

if(!empty($nomeUsuario)){
	$pdf->SetX(130);
	$pdf->Cell(22, 1, 'Usuario :');
	$pdf->SetX(150);
	$pdf->Cell(22, 1, $nomeUsuario);
}

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(100);
$pdf->Cell(20, 1, 'Tipo de Interação');
$pdf->SetX(180);
$pdf->Cell(20, 1, 'Usuário');
$pdf->SetX(220);
$pdf->Cell(20, 1, 'Data/Horário');
$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$servicoId = $mostra['id_servico'];
	$servico = mostra('servico',"WHERE id ='$servicoId'");

	if($servico){
		$clienteId = $servico['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		$pdf->SetX(20);
		$pdf->Cell(10, 5, substr($cliente['nome'],0,25));
	}else{
	    $pdf->SetX(20);
		$pdf->Cell(10, 5, 'Financeiro' );
	}

	$pdf->SetX(100);
	$pdf->Cell(10, 5, $mostra['interacao'] );

	$pdf->SetX(180);
	$pdf->Cell(10, 5, $mostra['usuario'] );

	$pdf->SetX(220);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s',strtotime($mostra['data'])) );

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
		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Tipo de Interação');
		$pdf->SetX(180);
		$pdf->Cell(20, 1, 'Usuário');
		$pdf->SetX(220);
		$pdf->Cell(20, 1, 'Data/Horário');
		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>