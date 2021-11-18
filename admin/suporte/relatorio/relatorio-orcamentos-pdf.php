<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$consultorId = $_SESSION['consultor'];
$indicacaoId = $_SESSION['indicacao'];

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
	  $titulo="Relatorio de Orçamentos";
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

$leitura = read( 'cadastro_visita', "WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY orc_data DESC" );
$total = conta( 'cadastro_visita', "WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2'" );

		if(!empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status<>'0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status<>'0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId' ORDER BY interacao DESC");
		}
		
		if(!empty($indicacaoId)){
			$total = conta('cadastro_visita',"WHERE id AND status<>'0' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId'");
			$leitura = read('cadastro_visita',"WHERE id AND status<>'0' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId' ORDER BY interacao DESC");
		}
		
		if(!empty($indicacaoId) AND !empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status<>'0' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status<>'0' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId' AND consultor='$consultorId' ORDER BY interacao DESC");
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
$pdf->Cell(22, 1, converteData($data2));

if(!empty($consultorId)){
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->SetX(130);
	$pdf->Cell(22, 1, 'Consultor :');
	$pdf->SetX(150);
	$pdf->Cell(22, 1, $consultor['nome']);
}

if(!empty($indicacaoId)){
	$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
	$pdf->SetX(180);
	$pdf->Cell(22, 1, 'Consultor :');
	$pdf->SetX(200);
	$pdf->Cell(22, 1, $indicacao['nome']);
}


$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(60);
$pdf->Cell(20, 1, 'Telefone');

$pdf->SetX(85);
$pdf->Cell(20, 1, 'Celular');

$pdf->SetX(140);
$pdf->Cell(20, 1, 'Valor');
 
$pdf->SetX(155);
$pdf->Cell(20, 1, 'Orçamento/Hora');
 
$pdf->SetX(185);
$pdf->Cell(20, 1, 'Tipo de Resíduo');
$pdf->SetX(225);
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
	$pdf->Cell(10, 5, substr($mostra['nome'],0,20));

	$pdf->SetX(60);
	$pdf->Cell(10, 5, substr($mostra['telefone'],0,20));
	
	$pdf->SetX(85);
	$pdf->Cell(10, 5, substr($mostra['celular'],0,20));

	$pdf->SetX(140);
	$pdf->Cell(10, 5, converteValor($mostra['orc_valor']) );

//	$pdf->SetX(90);
//	$pdf->Cell(10, 5, converteData($mostra['data']) );

	$pdf->SetX(160);
	$pdf->Cell(10, 5, converteData($mostra['orc_data']).'/'.$mostra['orc_hora'] );

	//$indicacaoId = $mostra['indicacao'];
//	$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
//	$pdf->SetX(140);
//	$pdf->Cell(10, 5, substr($indicacao['nome'],0,10) );

	$pdf->SetX(185);
	$pdf->Cell(10, 5, substr($mostra['orc_residuo'],0,15) );

	$pdf->SetX(225);
	$consultorId = $mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, substr($consultor['nome'],0,15) );

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

		$pdf->SetX(60);
		$pdf->Cell(20, 1, 'Telefone');

		$pdf->SetX(85);
		$pdf->Cell(20, 1, 'Celular');

		$pdf->SetX(140);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(155);
		$pdf->Cell(20, 1, 'Orçamento/Hora');

		$pdf->SetX(185);
		$pdf->Cell(20, 1, 'Tipo de Resíduo');
		$pdf->SetX(225);
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