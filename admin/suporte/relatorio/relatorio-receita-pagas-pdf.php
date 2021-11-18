<?php
require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$banco = $_SESSION['banco'];
$formpag = $_SESSION['formpag'];

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Receita Quitadas";
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

$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' ORDER BY pagamento ASC");

$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'",'valor');
$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'");

	if(!empty($banco)){
		
		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND banco='$banco' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'AND banco='$banco'",'valor');
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco'");
	}

	if(!empty($formpag)){
		
		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND formpag='$formpag' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'AND formpag='$formpag'",'valor');
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND formpag='$formpag'");
	}
 

	if(!empty($banco) AND !empty($formpag) ){

		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND banco='$banco' AND formpag='$formpag' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco' AND formpag='$formpag'",'valor');
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco' AND formpag='$formpag'");
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

$pdf->SetX(20);
$pdf->Cell(20, 1, 'Controle');

$pdf->SetX(38);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Emissão');

$pdf->SetX(118);
$pdf->Cell(20, 1, 'Vencimento');

$pdf->SetX(141);
$pdf->Cell(20, 1, 'Pagamento');

$pdf->SetX(168);
$pdf->Cell(20, 1, 'Juros');

$pdf->SetX(182);
$pdf->Cell(20, 1, 'Desconto');

$pdf->SetX(205);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(218);
$pdf->Cell(20, 1, 'Banco/Pag');

$pdf->SetX(243);
$pdf->Cell(10, 1, 'Nota');

$pdf->SetX(256);
$pdf->Cell(20, 1, 'S|J|P');

$pdf->SetX(264);
$pdf->Cell(20, 1, 'Status');



$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

$totalValor=0;
$totalDesconto=0;
$totalJuros=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $mostra['id'],0,0,R);

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId '");

	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(38);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,32));

	$pdf->SetX(100);
	$pdf->Cell(10, 5, converteData($mostra['emissao']));

	$pdf->SetX(120);
	$pdf->Cell(10, 5, converteData($mostra['vencimento']));

	$pdf->SetX(142);
	$pdf->Cell(10, 5, converteData($mostra['pagamento']));

	$pdf->SetX(168);
	$pdf->Cell(10, 5, converteValor($mostra['juros']),0,0,'R');
	
	$totalJuros=$totalJuros+$mostra['juros'];

	$pdf->SetX(186);
	$pdf->Cell(10, 5, converteValor($mostra['desconto']),0,0,'R');
	
	$totalDesconto=$totalDesconto+$mostra['desconto'];

	$pdf->SetX(205);
	$pdf->Cell(10, 5, converteValor($mostra['valor']),0,0,'R');

	$totalValor=$totalValor+$mostra['valor'];

	$bancoId=$mostra['banco'];
	$banco = mostra('banco',"WHERE id ='$bancoId'");
	$formapagId=$mostra['formpag'];
	$formapag = mostra('formpag',"WHERE id ='$formapagId'");
	$pdf->SetX(220);
	$pdf->Cell(10, 5, $banco['nome']. "|".substr($formapag['nome'],0,6) ) ;

	$pdf->SetX(242);
	$pdf->Cell(10, 5, $mostra['nota']);

	if($mostra['serasa']=='1'){
		$pdf->SetX(257);
		$pdf->Cell(10, 5, 'S');
	}
	if($mostra['juridico']=='1'){
		$pdf->SetX(259);
		$pdf->Cell(10, 5, 'J');
	}
	if($mostra['protestado']=='1'){
		$pdf->SetX(259);
		$pdf->Cell(10, 5, 'P');
	}

	$statusId=$contrato['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId'");
	$pdf->SetX(264);
	$pdf->Cell(10, 5, $status['nome']);


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

		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Controle');

		$pdf->SetX(38);
		$pdf->Cell(20, 1, 'Nome');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Emissão');

		$pdf->SetX(118);
		$pdf->Cell(20, 1, 'Vencimento');

		$pdf->SetX(141);
		$pdf->Cell(20, 1, 'Pagamento');

		$pdf->SetX(168);
		$pdf->Cell(20, 1, 'Juros');

		$pdf->SetX(182);
		$pdf->Cell(20, 1, 'Desconto');

		$pdf->SetX(205);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(205);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(220);
		$pdf->Cell(20, 1, 'Banco/Pag');

		$pdf->SetX(243);
		$pdf->Cell(10, 1, 'Nota');

		$pdf->SetX(256);
		$pdf->Cell(20, 1, 'S|J');

		$pdf->SetX(264);
		$pdf->Cell(20, 1, 'Status');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;
$pdf->ln(6);
$pdf->SetX(168);
$pdf->Cell(10, 5, converteValor($totalJuros),0,0,'R');
$pdf->SetX(186);
$pdf->Cell(10, 5, converteValor($totalDesconto),0,0,'R');
$pdf->SetX(205);
$pdf->Cell(10, 5, converteValor($totalValor),0,0,'R');

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>