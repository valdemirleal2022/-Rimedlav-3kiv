<?php


if(function_exists(ProtUser)){
	if(!ProtUser($_SESSION['autUser']['id'])){
		header('Location: painel.php?execute=suporte/403');	
	}	
}
		
if(!empty($_GET['manutencao'])){
	$manutencaoId = $_GET['manutencao'];
}


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','B',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','B',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Solicita��o de Manuten��o";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
	  $this->Line(10, 21, 200, 21); // insere linha divis�ria
      $this->Ln(11);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','B',9);
      $this->Cell(0,10,'P�gina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$i=0;

if(!empty($manutencaoId)){
	$readmanutencao= read('veiculo_manutencao',"WHERE id = '$manutencaoId'");
	if(!$readmanutencao){
		header('Location: painel.php?execute=suporte/error');	
	}
	foreach($readmanutencao as $manutencao);
}

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$veiculoId = $manutencao['id_veiculo'];
$veiculo = mostra('veiculo',"WHERE id ='$veiculoId '");

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Manuten��o Id :');
$pdf->SetX(45);
$pdf->Cell(22, 1, $manutencao['id']);

$pdf->SetX(60);
$pdf->Cell(22, 1, 'Veiculo :');
$pdf->SetX(78);
$pdf->Cell(22, 1, substr($veiculo['placa'],0,30));

$pdf->SetX(114);
$pdf->Cell(22, 1, 'Km :'); 
$pdf->SetX(124);
$pdf->Cell(22, 1, $manutencao['km']);

$pdf->SetX(153);
$pdf->Cell(22, 1, 'Manuten��o :');
if( $manutencao['manutencao']=='1' ){
	$pdf->SetX(178);
	$pdf->Cell(22, 1,'Preventiva');
}
if( $manutencao['manutencao']=='2' ){
	$pdf->SetX(178);
	$pdf->Cell(22, 1,'Corretiva' );
}
if( $manutencao['manutencao']=='3' ){
	$pdf->SetX(178);
	$pdf->Cell(22, 1,'Socorro' );
}

$pdf->ln(8);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data :');
$pdf->SetX(22);
$pdf->Cell(22, 1, converteData($manutencao['data_solicitacao']) );

$pdf->SetX(60);
$pdf->Cell(22, 1, 'Hora :');
$pdf->SetX(72);
$pdf->Cell(22, 1, $manutencao['hora_solicitacao']);

$motoristaId=$manutencao['id_motorista'];
$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
$pdf->SetX(95);
$pdf->Cell(22, 1, 'Motorista :');
$pdf->SetX(115);
$pdf->Cell(22, 1, $motorista['nome']);

$pdf->SetX(180);
$pdf->Cell(22, 1, 'Turno :');
$pdf->SetX(195);
$pdf->Cell(22, 1, $manutencao['turno']);

$pdf->ln(10);
$pdf->SetX(80);
$pdf->Cell(22, 1, 'Descri��o da Manuten��o');
$pdf->ln(8);

$pdf->SetX(10);
$pdf->Cell(22, 1, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Descri��o 1 :');
$pdf->SetX(35);
$pdf->MultiCell(0, 4,$manutencao['descricao1'], 0, "L");
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Reparo executado : _________________________________________________________________________');
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Respons�vel pela excecu��o : ___________________________');
$pdf->SetX(118);
$pdf->Cell(22, 1, 'Inicio:_____________ T�rmino : _______________');
$pdf->ln(7);

$pdf->SetX(10);
$pdf->Cell(22, 1, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Descri��o 2 :');
$pdf->SetX(35);
$pdf->MultiCell(0, 4,$manutencao['descricao2'], 0, "L");
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Reparo executado : _______________________________________________________________________________');
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Respons�vel pela excecu��o : ___________________________');
$pdf->SetX(118);
$pdf->Cell(22, 1, 'Inicio:_____________ T�rmino : _______________');
$pdf->ln(7);

$pdf->SetX(10);
$pdf->Cell(22, 1, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Descri��o 3 :');
$pdf->SetX(35);
$pdf->MultiCell(0, 4,$manutencao['descricao3'], 0, "L");
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Reparo executado : _______________________________________________________________________________');
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Respons�vel pela excecu��o : ___________________________');
$pdf->SetX(118);
$pdf->Cell(22, 1, 'Inicio:_____________ T�rmino : _______________');
$pdf->ln(7);

$pdf->SetX(10);
$pdf->Cell(22, 1, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Descri��o 4 :');
$pdf->SetX(35);
$pdf->MultiCell(0, 4,$manutencao['descricao4'], 0, "L");
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Reparo executado : _______________________________________________________________________________');
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Respons�vel pela excecu��o : ___________________________');
$pdf->SetX(118);
$pdf->Cell(22, 1, 'Inicio:_____________ T�rmino : _______________');
$pdf->ln(7);

$pdf->SetX(10);
$pdf->Cell(22, 1, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Descri��o 5 :');
$pdf->SetX(35);
$pdf->MultiCell(0, 4,$manutencao['descricao5'], 0, "L");
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Respons�vel pela excecu��o : ___________________________');
$pdf->SetX(118);
$pdf->Cell(22, 1, 'Inicio:_____________ T�rmino : _______________');
$pdf->ln(7);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Respons�vel pela excecu��o : _________________________________________________');
$pdf->ln(7);

$pdf->SetX(10);
$pdf->Cell(22, 1, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->ln(8);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Operacional _______________________');

$pdf->SetX(120);
$pdf->Cell(22, 1, 'Mecanico _________________________');

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');



?>