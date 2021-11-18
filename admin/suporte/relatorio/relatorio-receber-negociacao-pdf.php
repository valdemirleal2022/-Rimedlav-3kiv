<?php

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
	  $titulo="Relatório de Receber Negociações";
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
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$motivo = $_SESSION['motivo'];
$usuarioPesquisa = $_SESSION['usuarioPesquisa'];

$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC, id_cliente ASC, id_usuario ASC,, peso DESC");
 
if(!empty($usuarioPesquisa)){
		$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_usuario='$usuarioPesquisa'");
		$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_usuario='$usuarioPesquisa' ORDER BY data ASC");
}
		

if(!empty($motivo)){
		$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motivo='$motivo'");
		$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motivo='$motivo' ORDER BY data ASC");
}

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
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(17);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(52);
$pdf->Cell(20, 1, 'Valor');
$pdf->SetX(78);
$pdf->Cell(20, 1, 'Vencimento');
$pdf->SetX(98);
$pdf->Cell(20, 1, 'Status');
$pdf->SetX(128);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(160);
$pdf->Cell(20, 1, 'Hora');
$pdf->SetX(180);
$pdf->Cell(10, 1, 'Previsao Pag');
$pdf->SetX(210);
$pdf->Cell(20, 1, 'Motivo');
$pdf->SetX(240);
$pdf->Cell(20, 1, 'Solução');
$pdf->SetX(270);
$pdf->Cell(10, 1, 'Usuario');
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();
	$pdf->SetX(5);
	$pdf->Cell(10, 5, $mostra['id']);

	$clienteId = $mostra['id_cliente'];
	$receberId = $mostra['id_receber'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(17);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,16));

	$receber = mostra('receber',"WHERE id ='$receberId'");

	$pdf->SetX(52);
	$pdf->Cell(10, 5, converteValor($receber['valor']));

	$pdf->SetX(78);
	$pdf->Cell(10, 5, converteData($receber['vencimento']));

	$pdf->SetX(98);
	$pdf->Cell(10, 5, $receber['status']);

	$pdf->SetX(128);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$pdf->SetX(160);
	$pdf->Cell(10, 5, $mostra['hora'] );

	$pdf->SetX(180);
	$pdf->Cell(10, 5, converteData($mostra['previsao_pagamento']));

	$motivoId = $mostra['id_motivo'];
	$motivo = mostra('recebe_negociacao_motivo',"WHERE id ='$motivoId'");
	$pdf->SetX(210);
	$pdf->Cell(10, 5,$motivo['nome']);

	$solucaoId = $mostra['id_solucao'];
	$solucao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId'");
	$pdf->SetX(240);
	$pdf->Cell(10, 5,$solucao['nome']);


	$usuarioId = $mostra['id_usuario'];
	$usuarioMostra = mostra('usuarios',"WHERE id ='$usuarioId '");

	$pdf->SetX(270);
	$pdf->Cell(10, 5, $usuarioMostra['nome']);


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(17);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(52);
		$pdf->Cell(20, 1, 'Valor');
		$pdf->SetX(78);
		$pdf->Cell(20, 1, 'Vencimento');
		$pdf->SetX(98);
		$pdf->Cell(20, 1, 'Status');
		$pdf->SetX(128);
		$pdf->Cell(20, 1, 'Data');
		$pdf->SetX(160);
		$pdf->Cell(20, 1, 'Hora');
		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Previsao Pag');
		$pdf->SetX(210);
		$pdf->Cell(20, 1, 'Motivo');
		$pdf->SetX(240);
		$pdf->Cell(20, 1, 'Solução');
		$pdf->SetX(270);
		$pdf->Cell(10, 1, 'Usuario');
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