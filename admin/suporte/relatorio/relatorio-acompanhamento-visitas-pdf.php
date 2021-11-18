<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$consultorId = $_SESSION['consultor'];

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
	  $titulo="Acompanhamento de Visitas";
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

$total = conta('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2'");
$leitura = read('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");

$visitas = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' 
AND data<='$data2'");
$orcamento = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' 
AND data<='$data2'");
$propostas = conta('cadastro_visita',"WHERE id AND status='3' AND data>='$data1' 
AND data<='$data2'");
$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND data>='$data1' 
AND data<='$data2'");
$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' 
AND data<='$data2'");
		
if(!empty($consultorId)){
	
	$total = conta('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
	$leitura = read('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2' AND
	consultor='$consultorId' ORDER BY data ASC");
	
	$visitas = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
	$orcamentos = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
	$propostas = conta('cadastro_visita',"WHERE id AND status='3' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
	$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
	$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
	
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
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(140);
$pdf->Cell(20, 1, 'Telefone');
$pdf->SetX(160);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(178);
$pdf->Cell(20, 1, 'Orçamento');
$pdf->SetX(200);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(221);
$pdf->Cell(20, 1, 'Indicação');
$pdf->SetX(255);
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
	$pdf->Cell(10, 5, substr($mostra['nome'],0,25));

	$endereco=$mostra['endereco'].', '.$mostra['numero'].' '.$mostra['complemento'];

	$pdf->SetX(65);
	$pdf->Cell(10, 5, substr($endereco,0,25));

	$pdf->SetX(110);
	$pdf->Cell(10, 5, substr($mostra['bairro'],0,20));

	$pdf->SetX(140);
	$pdf->Cell(10, 5, substr($mostra['telefone'],0,10));

	$pdf->SetX(160);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$pdf->SetX(180);
	$pdf->Cell(10, 5, converteData($mostra['orc_data']));

	$pdf->SetX(200);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(221);
	$indicacaoId=$mostra['indicacao'];
	$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
	$pdf->Cell(10, 5, $indicacao['nome']);
	
	$pdf->SetX(255);
	$statusId=$mostra['status'];
	if($statusId=='0'){
		$pdf->Cell(10, 5,'Visita');
	}else{
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$pdf->Cell(10, 5, $status['nome']);
	}

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
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(140);
		$pdf->Cell(20, 1, 'Telefone');
		$pdf->SetX(160);
		$pdf->Cell(20, 1, 'Data');
		$pdf->SetX(178);
		$pdf->Cell(20, 1, 'Orçamento');
		$pdf->SetX(200);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(221);
		$pdf->Cell(20, 1, 'Indicação');
		$pdf->SetX(255);
		$pdf->Cell(20, 1, 'Status');
		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de Visitas : '. $visitas);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de Orçamentos : '. $orcamentos);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de Propostas : '. $propostas);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de Aprovados : '. $aprovados);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de Cancelados : '. $cancelados);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>