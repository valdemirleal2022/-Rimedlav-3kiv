<?php


$aprovacao_comercial = $_SESSION['aprovacao_comercial'];

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
	  $titulo="Relatorio de Autorizacoes";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Pбgina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_comercial='2' OR aprovacao_comercial='3'",'orc_valor');
$total = conta('cadastro_visita',"WHERE id AND aprovacao_comercial='2' OR aprovacao_comercial='3'");
$leitura = read('cadastro_visita',"WHERE id AND aprovacao_comercial='2' OR aprovacao_comercial='3' ORDER BY orc_data DESC");

if(!empty($aprovacao_comercial)){
	$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial'",'orc_valor');
 	$total = conta('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial'");
	$leitura = read('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial' ORDER BY orc_data DESC");
}
		
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisуria (Col, Lin, Col, Lin)
$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(102);
$pdf->Cell(20, 1, 'Valor');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Solicitaзгo');
$pdf->SetX(145);
$pdf->Cell(20, 1, 'Tipo de Resнduo');
$pdf->SetX(185);
$pdf->Cell(20, 1, 'Vendedor');
$pdf->SetX(210);
$pdf->Cell(20, 1, 'Comercial|Operacional');
$pdf->SetX(260);
$pdf->Cell(20, 1, 'Observaзгo');
$pdf->Line(10, 33, 290, 33); // insere linha divisуria
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($mostra['nome'],0,30));

	$pdf->SetX(75);
	$pdf->Cell(10, 5, substr($mostra['bairro'],0,30));

	$pdf->SetX(102);
	$pdf->Cell(10, 5, converteValor($mostra['valor']) );

	$pdf->SetX(120);
	$pdf->Cell(10, 5, converteData($mostra['orc_data']));

	$pdf->SetX(145);
	$pdf->Cell(10, 5, substr($mostra['orc_residuo'],0,15) );

	$pdf->SetX(185);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

				if($mostra['aprovacao_comercial']=='1'){
					$liberacaoComercial='Aprovado';
				}
				if($mostra['aprovacao_comercial']=='2'){
					$liberacaoComercial='Nгo Autorizado';
				}
				if($mostra['aprovacao_comercial']=='3'){
					$liberacaoComercial='Aguardando';
				}
				if($mostra['aprovacao_operacional']=='1'){
					$liberacaoOperacional='Aprovado';
				}
				if($mostra['aprovacao_operacional']=='2'){
					$liberacaoOperacional='Nгo Autorizado';
				}
				if($mostra['aprovacao_operacional']=='3'){
					$liberacaoOperacional='Aguardando';
				}

	$pdf->SetX(210);
	$pdf->Cell(10, 5,$liberacaoComercial.'|'.$liberacaoOperacional);

	$pdf->SetX(260);
	$pdf->Cell(10, 5,  substr($mostra['aprovacao_comercial_observacao'],0,35) );

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->SetX(10);
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisуria (Col, Lin, Col, Lin)
		$pdf->SetX(10);
		
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(75);
		$pdf->Cell(20, 1, 'Valor');
		$pdf->SetX(95);
		$pdf->Cell(20, 1, 'Orзamento');
		$pdf->SetX(145);
		$pdf->Cell(20, 1, 'Tipo de Resнduo');
		$pdf->SetX(170);
		$pdf->Cell(20, 1, 'Vendedor');
		$pdf->SetX(210);
		$pdf->Cell(20, 1, 'Comercial|Operacional');
		
		$pdf->Line(10, 33, 290, 33); // insere linha divisуria
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