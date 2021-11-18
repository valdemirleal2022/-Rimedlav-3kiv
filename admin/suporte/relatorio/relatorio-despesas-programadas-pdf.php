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
	  $titulo="Despesas";
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

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$conta = $_SESSION['conta'];

$total = conta('pagar',"WHERE id AND programacao>='$data1' AND programacao<='$data2' AND status<>'Baixado'");
$valor_total = soma('pagar',"WHERE id AND programacao>='$data1' AND programacao<='$data2' AND status<>'Baixado'",'valor');

$leitura = read('pagar',"WHERE id AND programacao>='$data1' AND programacao<='$data2' AND status<>'Baixado' ORDER BY programacao ASC");

if( !empty($conta) ){
	$total = conta('pagar',"WHERE  id_conta='$conta' AND status='Em Aberto' AND programacao>='$data1' AND programacao<='$data2'");
	$valor_total = soma('pagar',"WHERE id_conta='$conta' AND status='Em Aberto' AND programacao>='$data1' AND programacao<='$data2'",'valor');
	$leitura = read('pagar',"WHERE  id_conta='$conta' AND status='Em Aberto' AND programacao>='$data1' AND programacao<='$data2' ORDER BY programacao ASC");
}

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(25, 1, converteData($data1));
$pdf->Cell(20, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(25);
$pdf->Cell(20, 1, 'Descrição');

$pdf->SetX(88);
$pdf->Cell(20, 1, 'Emissão');

$pdf->SetX(106);
$pdf->Cell(20, 1, 'Vencimento');

$pdf->SetX(130);
$pdf->Cell(20, 1, 'programacao');

$pdf->SetX(155);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(175);
$pdf->Cell(20, 1, 'Parcela');

$pdf->SetX(190);
$pdf->Cell(20, 1, 'Banco/Form Pag');

$pdf->SetX(230);
$pdf->Cell(20, 1, 'Conta');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

$totalValor=0;
$totalDesconto=0;
$totalJuros=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id'],0,0,R);

	$pdf->SetX(25);
	$pdf->Cell(10, 5, substr($mostra['descricao'],0,45) );

	$pdf->SetX(88);
	$pdf->Cell(10, 5, converteData($mostra['emissao']));

	$pdf->SetX(108);
	$pdf->Cell(10, 5, converteData($mostra['vencimento']));

	$pdf->SetX(130);
	$pdf->Cell(10, 5, converteData($mostra['programacao']));

	$pdf->SetX(155);
	$pdf->Cell(10, 5, converteValor($mostra['valor']),0,0,'R');

	$pdf->SetX(178);
	$pdf->Cell(10, 5, $mostra['parcela'] );

	$bancoId=$mostra['banco'];
	$formapagId=$mostra['formpag'];
	$banco = mostra('banco',"WHERE id ='$bancoId'");
	$formapag = mostra('formpag',"WHERE id ='$formapagId'");

	$pdf->SetX(190);
	$pdf->Cell(10, 5, $banco['nome']. "|".$formapag['nome']);

	$contaId = $mostra['id_conta'];
	$contaMostra = mostra('pagar_conta',"WHERE id ='$contaId '");

	$pdf->SetX(230);
	$pdf->Cell(10, 5, substr($contaMostra['nome'],0,30) );


	$i=$i+1;

	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->Cell(25, 1, converteData($data1));
		$pdf->Cell(20, 1, 'Data Fim :');
		$pdf->Cell(22, 1, converteData($data2));

		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(25);
		$pdf->Cell(20, 1, 'Descrição');

		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(130);
		$pdf->Cell(20, 1, 'Emissão');

		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'programacao');

		$pdf->SetX(175);
		$pdf->Cell(20, 1, 'Parcela');

		$pdf->SetX(190);
		$pdf->Cell(20, 1, 'Banco/Form Pag');

		$pdf->SetX(230);
		$pdf->Cell(20, 1, 'Conta');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);

$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total : '. converteValor($valor_total) );


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>