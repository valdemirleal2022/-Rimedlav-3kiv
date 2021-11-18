<?php
require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");
 
$leitura = read('cliente',"WHERE id ORDER BY nome");
$pdf = new FPDF();
  
	$pdf->AliasNbPages('{np}'); // Escopo com valor default: AliasNbPages($alias='{nb}')
	$pdf->SetFont('Arial','B',10);// SetFont($family, $style='', $size=0)
	$pdf->SetMargins(10,10,10);// SetMargins($left, $top, $right=null)
	$pdf->AddPage();   //  AddPage($orientation='', $size='')
	$titulo="Relatуrio de Cliente";
	// Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	$pdf->Image("../site/images/header-logo.png"); //Image($arquivo);
	$pdf->Ln(2);
	$pdf->Cell(0,5,$titulo,0,0,'L'); 
	$pdf->Cell(0,5,'http://www.wpcsistema.com.br',0,1,'R'); 
	$pdf->Cell(0,0,'',1,1,'L'); 
	$pdf->Ln(3);

$pdf->Cell(10, 1, 'Id');
$pdf->SetX(25);
$pdf->Cell(10, 1, 'Nome');
$pdf->SetX(100);
$pdf->Cell(10, 1, 'Endereco');
$pdf->SetX(140);
$pdf->Cell(10, 1, 'bairro');
$pdf->Ln(2);~

$i=0;
foreach($leitura as $mostra):
	$pdf->ln();
	$pdf->Cell(10, 5, $mostra['id']);
	$pdf->SetX(25);
	$pdf->Cell(10, 5, substr($mostra['nome'],0,30));
	$pdf->SetX(100);
	$pdf->Cell(10, 5, $mostra['bairro']);
	$pdf->SetX(140);
	$pdf->Cell(10, 5, $mostra['bairro']);
	$i=$i+1;
	if ($i>30){
		$pdf->AddPage();
		$titulo="Relatуrio de Cliente";
		$pdf->Image("../site/images/header-logo.png"); //Image($arquivo);
		$pdf->Ln(2);
		$pdf->Cell(0,5,$titulo,0,0,'L'); 
		$pdf->Cell(0,5,'http://www.wpcsistema.com.br',0,1,'R'); 
		$pdf->Cell(0,0,'',1,1,'L'); 
		$pdf->Ln(3);
		$pdf->Cell(10, 1, 'Id');
		$pdf->SetX(25);
		$pdf->Cell(10, 1, 'Nome');
		$pdf->SetX(100);
		$pdf->Cell(10, 1, 'Endereco');
		$pdf->SetX(140);
		$pdf->Cell(10, 1, 'bairro');
		$pdf->Ln(2);~
		$i=0;
	}
endforeach;

// Gerando um rodapй simples
$pdf->Line(25, 270, 185, 270); // insere linha divisуria
$pdf->SetXY(25,270); //posiзгo para o texto
$data=date("d/m/Y H:i:s"); //pegando data e hora da criaзгo do PDF
$conteudo=$data."       Pбg. ".$pdf->PageNo(); //pegando o nъmero da pбgina
$texto="www.wpcsistema.com.br";
$pdf->Cell(80,5,$texto,0,0,"L"); //Insere cйlula de texto alinhado а esquerda
$pdf->Cell(80,5,$conteudo,0,0,"R"); //Insere cйlula de texto alinhado а direita
ob_clean();  
$pdf->Output('relaorio.pdf', 'I');
 
?>