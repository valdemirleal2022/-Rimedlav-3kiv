<?php


$rotaId =$_SESSION[ 'rotaColeta' ];

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
	  $titulo="Rota Semanal";
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

$leitura =read('contrato',"WHERE id AND status='5' AND (
	domingo_rota1 = '$rotaId' AND domingo_rota1 !='0' OR 
	segunda_rota1 = '$rotaId' AND segunda_rota1 !='0' OR 
	terca_rota1 = '$rotaId' AND terca_rota1 !='0' OR 
	quarta_rota1 = '$rotaId' AND quarta_rota1 !='0' OR 
	quinta_rota1 = '$rotaId' AND quinta_rota1 !='0' OR 
	quinta_rota1 = '$rotaId' AND quinta_rota1 !='0' OR 
	sabado_rota1 = '$rotaId' AND sabado_rota1 !='0') ORDER BY segunda_hora1 ASC "); 

$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Rota :');
$pdf->Cell(22, 1, $rotaColeta['nome']);

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(5);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(60);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(150);
$pdf->Cell(20, 1, 'Coleta');
$pdf->SetX(200);
$pdf->Cell(10, 1, 'Seg');
$pdf->SetX(210);
$pdf->Cell(10, 1, 'Ter');
$pdf->SetX(220);
$pdf->Cell(10, 1, 'Qua');
$pdf->SetX(230);
$pdf->Cell(10, 1, 'Qui');
$pdf->SetX(240);
$pdf->Cell(10, 1, 'Sex');
$pdf->SetX(250);
$pdf->Cell(10, 1, 'Sab');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Dom');
$pdf->SetX(270);
$pdf->Cell(10, 1, 'Controle');
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId'");

	$pdf->SetX(5);
	$pdf->Cell(10, 5,$mostra['id']);

	$pdf->SetX(15);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));

	$pdf->SetX(60);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));
	
	$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];

	$pdf->SetX(90);
	$pdf->Cell(10, 5, substr($endereco,0,35));

	$contratoId = $mostra['id'];
	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
	$tipoColetaId = $contratoColeta['tipo_coleta'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(150);
	$pdf->Cell(10, 5,$coleta['nome']);

	$contratoId = $mostra['id_contrato'];
    $contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

	if($mostra['segunda_rota1']==$rotaId){
		$pdf->SetX(200);
		$pdf->Cell(10, 5, $mostra['segunda_hora1']);
	}else{
		$pdf->SetX(200);
		$pdf->Cell(10, 5, '---');
	}

	if($mostra['terca_rota1']==$rotaId){
		$pdf->SetX(210);
		$pdf->Cell(10, 5, $mostra['terca_hora1']);
	}else{
		$pdf->SetX(210);
		$pdf->Cell(10, 5, '---');
	}

	if($mostra['quarta_rota1']==$rotaId){
		$pdf->SetX(220);
		$pdf->Cell(10, 5, $mostra['quarta_hora1']);
	}else{
		$pdf->SetX(220);
		$pdf->Cell(10, 5, '---');
	}

	if($mostra['quinta_rota1']==$rotaId){
		$pdf->SetX(230);
		$pdf->Cell(10, 5, $mostra['quinta_hora1']);
	}else{
		$pdf->SetX(230);
		$pdf->Cell(10, 5, '---');
	}
	
	if($mostra['sexta_rota1']==$rotaId){
		$pdf->SetX(240);
		$pdf->Cell(10, 5, $mostra['sexta_hora1']);
	}else{
		$pdf->SetX(240);
		$pdf->Cell(10, 5, '---');
	}

	if($mostra['sabado_rota1']==$rotaId){
		$pdf->SetX(250);
		$pdf->Cell(10, 5, $mostra['sabado_hora1']);
	}else{
		$pdf->SetX(250);
		$pdf->Cell(10, 5, '---');
	}

	if($mostra['domingo_rota1']==$rotaId){
		$pdf->SetX(260);
		$pdf->Cell(10, 5, $mostra['domingo_hora1']);
	}else{
		$pdf->SetX(260);
		$pdf->Cell(10, 5, '---');
	}
	
	$pdf->SetX(272);
	$pdf->Cell(10, 5, substr($mostra['controle'],0,6));
			

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(60);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(90);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Coleta');
		$pdf->SetX(200);
		$pdf->Cell(10, 1, 'Seg');
		$pdf->SetX(210);
		$pdf->Cell(10, 1, 'Ter');
		$pdf->SetX(220);
		$pdf->Cell(10, 1, 'Qua');
		$pdf->SetX(230);
		$pdf->Cell(10, 1, 'Qui');
		$pdf->SetX(240);
		$pdf->Cell(10, 1, 'Sex');
		$pdf->SetX(250);
		$pdf->Cell(10, 1, 'Sab');
		$pdf->SetX(260);
		$pdf->Cell(10, 1, 'Sab');
		$pdf->SetX(270);
		$pdf->Cell(10, 1, 'Controle');
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