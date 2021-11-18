<?php

if(isset($_SESSION['inicio'])){
	$data1 = $_SESSION['inicio'];
}
if(isset($_SESSION['fim'])){
	$data2 = $_SESSION['fim'];
}

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
	  $titulo="Contratos Suspenso (Falta Pagamento)";
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

$total = conta('contrato_baixa',"WHERE id AND falta_pagamento='1' 
								AND data>='$data1' AND data<='$data2'");
$leitura = read('contrato_baixa',"WHERE id AND falta_pagamento='1' 
								AND data>='$data1'AND data<='$data2' ORDER BY data ASC");
$pdf=new RELATORIO();
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
$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)

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
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(155);
$pdf->Cell(10, 1, 'Motivo');
$pdf->SetX(190);
$pdf->Cell(10, 1, 'Status');

$pdf->Line(0, 33, 220, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

$valor_total=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id']);

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->SetX(15);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(65);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id = '$contratoId'");

	$pdf->SetX(90);
	$consultorId=$contrato['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND id_contrato='$contratoId' ORDER BY vencimento DESC" );
				if($tipoColeta){
						$pdf->SetX(118);
						$pdf->Cell(10, 5, converteValor($tipoColeta['valor_mensal']),0,0,'R');
						$valor_total=$valor_total+$contrato['valor_mensal'];
				}else{
						$pdf->SetX(118);
						$pdf->Cell(10, 5, 'ERROR');
				}

	
	$pdf->SetX(135);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$pdf->SetX(155);
	$pdf->Cell(10, 5,substr($mostra['motivo'],0,20) );

	if($contrato['status']==5){
				$pdf->SetX(190);
				$pdf->Cell(10, 5, 'Ativo' );
		}elseif($contrato['status']==6){
				$pdf->SetX(190);
				$pdf->Cell(10, 5, 'Suspenso' );
		}elseif($contrato['status']==7){
				$pdf->SetX(190);
				$pdf->Cell(10, 5, 'Protestado' );
		}elseif($contrato['status']==8){
				$pdf->SetX(190);
				$pdf->Cell(10, 5, 'Serasa' );
	}


	$i=$i+1;
	if ($i>45){
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
		$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)

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
		$pdf->Cell(20, 1, 'Data');
		$pdf->SetX(155);
		$pdf->Cell(10, 1, 'Motivo');
		$pdf->SetX(190);
		$pdf->Cell(10, 1, 'Status');

		$pdf->Line(0, 33, 220, 33); // insere linha divisória
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