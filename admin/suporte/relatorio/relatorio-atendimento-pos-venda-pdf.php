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
	  $titulo="Relatório de Atendimento";
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
$suporte = $_SESSION['suporte'];
$status = $_SESSION['status'];

 

		$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
		$leitura = read('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
	 

	if(!empty($status)){
		$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status'");
		$leitura = read('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status' ORDER BY data_solicitacao ASC");
	}
		

	if(!empty($suporte)){
		$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte'");
		$leitura = read('pedido',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
	}
		
	if(!empty($status) && !empty($suporte ) ){
		$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' 
			AND status='$status' AND id_suporte='$suporte'");
		$leitura = read('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2'
			AND status='$status' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
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
$pdf->SetX(55);
$pdf->Cell(20, 1, 'Tipo');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Pos-Venda');
$pdf->SetX(130);
$pdf->Cell(20, 1, 'Solicitação');
$pdf->SetX(220);
$pdf->Cell(20, 1, 'Motivo');
$pdf->SetX(275);
$pdf->Cell(10, 1, 'Status');
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();
	$pdf->SetX(5);
	$pdf->Cell(10, 5, $mostra['id']);

	$clienteId = $mostra['id_cliente'];
	$contratoId = $mostra['id_contrato'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(17);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,16));

	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
	$contratoTipoId = $contrato['contrato_tipo'];
	$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
		 
	$pdf->SetX(55);
	$pdf->Cell(10, 5, $contratoTipo['nome']);

	$pdf->SetX(90);
	$pdf->Cell(10, 5, converteData($mostra['data_solicitacao']));

	$pos_vendaId = $mostra['pos_venda'];
	$posVenda = mostra('contrato_pos_venda',"WHERE id ='$pos_vendaId'");

	$pdf->SetX(110);
	$pdf->Cell(10, 5, $posVenda['nome']);

	$pdf->SetX(130);
	$pdf->Cell(10, 5, substr($mostra['solicitacao'],0,55));

	$pdf->SetX(220);
	$motivoId = $mostra['motivo'];
	$suporteMotivo = mostra('contrato_atendimento_pos_venda_motivo',"WHERE id ='$motivoId'");
	$pdf->Cell(10, 5, substr($suporteMotivo['nome'],0,25));

	$pdf->SetX(272);
	$pdf->Cell(10, 5, $mostra['status']);


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
		$pdf->Cell(20, 1, 'Solicitação');
		$pdf->SetX(78);
		$pdf->Cell(20, 1, 'Atendente');
		$pdf->SetX(98);
		$pdf->Cell(20, 1, 'Motivo');
		$pdf->SetX(138);
		$pdf->Cell(20, 1, 'Solicitação');
		$pdf->SetX(191);
		$pdf->Cell(20, 1, 'Data Solução');
		$pdf->SetX(218);
		$pdf->Cell(10, 1, 'Solução');
		$pdf->SetX(256);
		$pdf->Cell(20, 1, 'Origem');
		$pdf->SetX(275);
		$pdf->Cell(10, 1, 'Status');
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