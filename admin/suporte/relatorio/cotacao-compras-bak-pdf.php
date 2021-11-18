<?php

$cotacaoId = $_SESSION['cotacaoId'];

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
	  $titulo="Cotacao de Compras";
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
 
$compras = mostra('estoque_compras',"WHERE id='$cotacaoId'");

$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId' ORDER BY id ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();


if($leitura ){
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);
	
	$pdf->Ln(5);
	$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

	$pdf->SetX(05);
	$pdf->Cell(20, 5, 'Id : ' .$compras['id']);

	$pdf->SetX(45);
	$pdf->Cell(20, 5, 'Status  : '. $compras['status']);

	$materialId=$compras['id_material'];
	$estoqueMaterial = mostra('estoque_material_tipo',"WHERE id='$materialId'");

	$pdf->SetX(110);
	$pdf->Cell(20, 5, 'Tipo  : '. $estoqueMaterial['nome']);

	$pdf->SetX(190);
	$pdf->Cell(20, 5, 'Data de Cotação  : '. converteData($compras['data']) );

	$pdf->Ln(8);

	$forncedorId=$compras['fornecedor1'];
	$fornecedor = mostra('estoque_fornecedor',"WHERE id='$forncedorId'");

	$pdf->SetX(05);
	$pdf->Cell(20, 5, 'Fornecedor 1 : ' .substr($fornecedor['nome'],0,30));

	$pdf->SetX(110);
	$pdf->Cell(20, 5, 'Desconto (%) : '. $compras['fornecedor1_desconto']);

	$pdf->SetX(155);
	$pdf->Cell(20, 5, 'Cond Pagamento  : '. $compras['fornecedor1_pag']);

	$pdf->SetX(220);
	$pdf->Cell(20, 5, 'Prazo de Entrega  : '. $compras['fornecedor1_prazo']);

	$pdf->Ln(10);
	$pdf->Line(5, 68, 290, 68); // insere linha divisória (Col, Lin, Col, Lin)
	$pdf->Cell(20, 1, 'Id');
	$pdf->SetX(15);
	$pdf->Cell(20, 1, 'Material');
	$pdf->SetX(65);
	$pdf->Cell(20, 1, 'Quantidade');
	$pdf->SetX(100);
	$pdf->Cell(20, 1, 'Unidade');
	$pdf->SetX(120);
	$pdf->Cell(20, 1, 'Preço');
	$pdf->SetX(140);
	$pdf->Cell(20, 1, 'Total');
	$pdf->Line(5, 73, 290, 73); // insere linha divisória
	$pdf->Ln(4);
	$i=0;

	$total=0;

	foreach($leitura as $mostra):

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->Cell(10, 5, $mostra['id']);

		$pdf->SetX(15);
		$materialId = $mostra['id_material'];
		$material = mostra('estoque_material',"WHERE id ='$materialId'");
		$pdf->Cell(10, 5, substr($material['nome'],0,30));

		$pdf->SetX(75);
		$pdf->Cell(10, 5, $mostra['quantidade']);

		$pdf->SetX(100);
		$pdf->Cell(10, 5, $mostra['unidade']);

		$pdf->SetX(120);
		$pdf->Cell(10, 5, converteValor($mostra['valor1']));

		$total1=$mostra['quantidade']*$mostra['valor1'];

		$totalGeral1+=$total1;

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($total1),0,0,'R');

		$total++;

	endforeach;

		$pdf->ln(5);

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->Cell(10, 5, '');

		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'SubTotal : ');

		$pdf->SetX(75);
		$pdf->Cell(10, 5,  $total);

		$pdf->SetX(100);
		$pdf->Cell(10, 5, '');

		$pdf->SetX(120);
		$pdf->Cell(10, 5, '');

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($totalGeral1),0,0,'R');

		$desconto1=($totalGeral1*$compras['fornecedor1_desconto'])/100;

		$pdf->ln(5);

		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'Desconto : ');

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($desconto1),0,0,'R');

		$totalGeral1=$totalGeral1-$desconto1;

		$pdf->SetFont('Arial','B',10);
		$pdf->ln(5);
		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'Total Líquido : ');
		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($totalGeral1),0,0,'R');
}

$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId' ORDER BY id ASC");

if($leitura ){
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);

	$pdf->Ln(5);
	$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

	$pdf->SetX(05);
	$pdf->Cell(20, 5, 'Id : ' .$compras['id']);

	$pdf->SetX(45);
	$pdf->Cell(20, 5, 'Status  : '. $compras['status']);

	$materialId=$compras['id_material'];
	$estoqueMaterial = mostra('estoque_material_tipo',"WHERE id='$materialId'");

	$pdf->SetX(110);
	$pdf->Cell(20, 5, 'Tipo  : '. $estoqueMaterial['nome']);

	$pdf->SetX(190);
	$pdf->Cell(20, 5, 'Data de Cotação  : '. converteData($compras['data']) );

	$pdf->Ln(8);

	$forncedorId=$compras['fornecedor2'];
	$fornecedor = mostra('estoque_fornecedor',"WHERE id='$forncedorId'");

	$pdf->SetX(05);
	$pdf->Cell(20, 5, 'Fornecedor 2 : ' .substr($fornecedor['nome'],0,30));

	$pdf->SetX(110);
	$pdf->Cell(20, 5, 'Desconto (%) : '. $compras['fornecedor1_desconto']);

	$pdf->SetX(155);
	$pdf->Cell(20, 5, 'Cond Pagamento  : '. $compras['fornecedor1_pag']);

	$pdf->SetX(220);
	$pdf->Cell(20, 5, 'Prazo de Entrega  : '. $compras['fornecedor1_prazo']);

	$pdf->Ln(10);
	$pdf->Line(5, 68, 290, 68); // insere linha divisória (Col, Lin, Col, Lin)
	$pdf->Cell(20, 1, 'Id');
	$pdf->SetX(15);
	$pdf->Cell(20, 1, 'Material');
	$pdf->SetX(65);
	$pdf->Cell(20, 1, 'Quantidade');
	$pdf->SetX(100);
	$pdf->Cell(20, 1, 'Unidade');
	$pdf->SetX(120);
	$pdf->Cell(20, 1, 'Preço');
	$pdf->SetX(140);
	$pdf->Cell(20, 1, 'Total');
	$pdf->Line(5, 73, 290, 73); // insere linha divisória
	$pdf->Ln(4);
	$i=0;

	$total=0;

	foreach($leitura as $mostra):

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->Cell(10, 5, $mostra['id']);

		$pdf->SetX(15);
		$materialId = $mostra['id_material'];
		$material = mostra('estoque_material',"WHERE id ='$materialId'");
		$pdf->Cell(10, 5, substr($material['nome'],0,30));

		$pdf->SetX(75);
		$pdf->Cell(10, 5, $mostra['quantidade']);

		$pdf->SetX(100);
		$pdf->Cell(10, 5, $mostra['unidade']);

		$pdf->SetX(120);
		$pdf->Cell(10, 5, converteValor($mostra['valor2']));

		$total2=$mostra['quantidade']*$mostra['valor2'];

		$totalGeral2+=$total2;

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($total2),0,0,'R');

		$total++;

	endforeach;

		$pdf->ln(5);

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->Cell(10, 5, '');

		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'SubTotal : ');

		$pdf->SetX(75);
		$pdf->Cell(10, 5,  $total);

		$pdf->SetX(100);
		$pdf->Cell(10, 5, '');

		$pdf->SetX(120);
		$pdf->Cell(10, 5, '');

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($totalGeral2),0,0,'R');

		$desconto2=($totalGeral2*$compras['fornecedor2_desconto'])/100;

		$pdf->ln(5);

		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'Desconto : ');

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($desconto2),0,0,'R');

		$totalGeral2=$totalGeral2-$desconto2;

		$pdf->SetFont('Arial','B',10);
		$pdf->ln(5);
		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'Total Líquido : ');
		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($totalGeral2),0,0,'R');
}

$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId' ORDER BY id ASC");

if($leitura ){
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);

	$pdf->Ln(5);
	$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

	$pdf->SetX(05);
	$pdf->Cell(20, 5, 'Id : ' .$compras['id']);

	$pdf->SetX(45);
	$pdf->Cell(20, 5, 'Status  : '. $compras['status']);

	$materialId=$compras['id_material'];
	$estoqueMaterial = mostra('estoque_material_tipo',"WHERE id='$materialId'");

	$pdf->SetX(110);
	$pdf->Cell(20, 5, 'Tipo  : '. $estoqueMaterial['nome']);

	$pdf->SetX(190);
	$pdf->Cell(20, 5, 'Data de Cotação  : '. converteData($compras['data']) );

	$pdf->Ln(8);

	$forncedorId=$compras['fornecedor3'];
	$fornecedor = mostra('estoque_fornecedor',"WHERE id='$forncedorId'");

	$pdf->SetX(05);
	$pdf->Cell(20, 5, 'Fornecedor 1 : ' .substr($fornecedor['nome'],0,30));

	$pdf->SetX(110);
	$pdf->Cell(20, 5, 'Desconto (%) : '. $compras['fornecedor1_desconto']);

	$pdf->SetX(155);
	$pdf->Cell(20, 5, 'Cond Pagamento  : '. $compras['fornecedor1_pag']);

	$pdf->SetX(220);
	$pdf->Cell(20, 5, 'Prazo de Entrega  : '. $compras['fornecedor1_prazo']);

	$pdf->Ln(10);
	$pdf->Line(5, 68, 290, 68); // insere linha divisória (Col, Lin, Col, Lin)
	$pdf->Cell(20, 1, 'Id');
	$pdf->SetX(15);
	$pdf->Cell(20, 1, 'Material');
	$pdf->SetX(65);
	$pdf->Cell(20, 1, 'Quantidade');
	$pdf->SetX(100);
	$pdf->Cell(20, 1, 'Unidade');
	$pdf->SetX(120);
	$pdf->Cell(20, 1, 'Preço');
	$pdf->SetX(140);
	$pdf->Cell(20, 1, 'Total');
	$pdf->Line(5, 73, 290, 73); // insere linha divisória
	$pdf->Ln(4);
	$i=0;

	$total=0;

	foreach($leitura as $mostra):

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->Cell(10, 5, $mostra['id']);

		$pdf->SetX(15);
		$materialId = $mostra['id_material'];
		$material = mostra('estoque_material',"WHERE id ='$materialId'");
		$pdf->Cell(10, 5, substr($material['nome'],0,30));

		$pdf->SetX(75);
		$pdf->Cell(10, 5, $mostra['quantidade']);

		$pdf->SetX(100);
		$pdf->Cell(10, 5, $mostra['unidade']);

		$pdf->SetX(120);
		$pdf->Cell(10, 5, converteValor($mostra['valor3']));

		$total3=$mostra['quantidade']*$mostra['valor3'];

		$totalGeral3+=$total3;

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($total3),0,0,'R');

		$total++;

	endforeach;

		$pdf->ln(5);
		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->Cell(10, 5, '');

		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'SubTotal : ');

		$pdf->SetX(75);
		$pdf->Cell(10, 5,  $total);

		$pdf->SetX(100);
		$pdf->Cell(10, 5, '');

		$pdf->SetX(120);
		$pdf->Cell(10, 5, '');

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($totalGeral3),0,0,'R');

		$desconto3=($totalGeral3*$compras['fornecedor3_desconto'])/100;

		$pdf->ln(5);

		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'Desconto : ');

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($desconto3),0,0,'R');

		$totalGeral3=$totalGeral3-$desconto3;

		$pdf->SetFont('Arial','B',10);
		$pdf->ln(5);
		$pdf->SetX(15);
		$pdf->Cell(10, 5, 'Total Líquido : ');
		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($totalGeral3),0,0,'R');
}

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>