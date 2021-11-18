<?php


if(function_exists(ProtUser)){
	if(!ProtUser($_SESSION['autUser']['id'])){
		header('Location: painel.php?execute=suporte/403');	
	}	
}
		
if(!empty($_GET['fabricacaoId'])){
	$fabricacaoId= $_GET['fabricacaoId'];
}


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){

    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','B',9);
      $this->Cell(0,10,'Pсgina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$i=0;

$equipamentoFabricacao = mostra('estoque_equipamento_fabricacao',"WHERE id='$fabricacaoId'");
 
$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);


$empresa = mostra('empresa',"WHERE id");
 

$pdf->SetX(5);
	$pdf->Cell(5, 5, '===============================================================================================');
	$pdf->ln();
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(165);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s'));
    $pdf->ln();
 	$pdf->SetFont('Arial','B',10);

	$pdf->Image("ico/header-logo.png", 06, 18, 30, 15,'PNG');
	 

	$pdf->SetX(40);
	$pdf->Cell(10, 5, $empresa['nome']);
    $pdf->ln();

	$pdf->SetX(40);
	$pdf->Cell(10, 5, $empresa['endereco']);
	$pdf->SetX(105);
	$pdf->Cell(10, 5, $empresa['bairro']);
	$pdf->SetX(135);
	$pdf->Cell(10, 5, $empresa['cidade']);
    $pdf->ln();

	$pdf->SetX(40);
	$pdf->Cell(10, 5, $empresa['cnpj']);

	
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '==============================================================================================');
	$pdf->ln();

	$pdf->ln(5);
	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Id :');
	$pdf->SetX(22);
	$pdf->Cell(22, 1, $equipamentoFabricacao['id']);

	$equipamentoId=$equipamentoFabricacao['id_equipamento'];
	$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
	$pdf->SetX(45);
	$pdf->Cell(22, 1, 'Equipamento :');
	$pdf->SetX(75);
	$pdf->Cell(22, 1, $equipamento['nome']);

	$pdf->SetX(120);
	$pdf->Cell(22, 1, 'Quantidade :');
	$pdf->SetX(150);
	$pdf->Cell(22, 1, $equipamentoFabricacao['quantidade']);

	$pdf->ln(10);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Data da Solicitaчуo :');
	$pdf->SetX(48);
	$pdf->Cell(22, 1, converteData($equipamentoFabricacao['data_solicitacao']));

	$pdf->ln(9);
	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Fabricaчуo');
	
	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Inicio :');
	$pdf->SetX(28);
	$pdf->Cell(22, 1, converteData($equipamentoFabricacao['fabricacao_inicio']));

	$pdf->SetX(80);
	$pdf->Cell(22, 1, 'Tщrmino :');
	$pdf->SetX(100);
	$pdf->Cell(22, 1, converteData($equipamentoFabricacao['fabricacao_termino']));

	$pdf->SetX(150);
	$pdf->Cell(22, 1, 'Status :');
	$pdf->SetX(130);
	if($equipamentoRetirada['fabricacao_status'] == '1'){
		$pdf->Cell(22, 1, 'Em Manutenчуo');
	}
	if($equipamentoRetirada['fabricacao_status'] == '2'){
		$pdf->Cell(22, 1, 'Concluida');
	}

	$pdf->ln(9);
	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Pintura');
	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Inicio :');
	$pdf->SetX(28);
	$pdf->Cell(22, 1, converteData($equipamentoFabricacao['pintura_inicio']));

	$pdf->SetX(80);
	$pdf->Cell(22, 1, 'Tщrmino :');
	$pdf->SetX(110);
	$pdf->Cell(22, 1, converteData($equipamentoFabricacao['pintura_termino']));

	$pdf->SetX(150);
	$pdf->Cell(22, 1, 'Status :');
	$pdf->SetX(130);
	if($equipamentoRetirada['pintura_status'] == '1'){
		$pdf->Cell(22, 1, 'Em Pintura');
	}
	if($equipamentoRetirada['pintura_status'] == '2'){
		$pdf->Cell(22, 1, 'Concluida');
	}

	$pdf->ln(10);
	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Observaчуo');

	$pdf->ln(5);
	$pdf->SetX(10);
	$pdf->Cell(5, 5, '________________________________________________________________________________________________');
	$pdf->ln(10);

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');



 
?>