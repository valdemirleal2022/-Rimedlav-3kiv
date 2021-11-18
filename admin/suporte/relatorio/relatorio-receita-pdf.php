<?php
require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$contratoTipo = $_SESSION['contratoTipo'];

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Relatorio de Receita";
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

$total = conta('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' ORDER BY vencimento ASC");
$valor_total = soma('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' ORDER BY vencimento ASC",'valor');
$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' ORDER BY vencimento ASC");
		
if(!empty($contratoTipo)){
	$total = conta('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado'  AND contrato_tipo='$contratoTipo'");
	$valor_total = soma('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' AND contrato_tipo='$contratoTipo'",'valor');
	$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND contrato_tipo='$contratoTipo' AND status<>'Baixado' ORDER BY vencimento ASC");
}


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(25, 1, converteData($data1));
$pdf->Cell(20, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));
if(!empty($contratoTipo)){
	$contrato_tipo = mostra('contrato_tipo',"WHERE id ='$contratoTipo '");
	$pdf->Cell(20, 1, 'Tipo :');
	$pdf->Cell(22, 1, $contrato_tipo['nome']);
}

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(20);
$pdf->Cell(20, 1, 'Controle');

$pdf->SetX(38);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(95);
$pdf->Cell(20, 1, 'Emissão');

$pdf->SetX(111);
$pdf->Cell(20, 1, 'Vencimento');

$pdf->SetX(134);
$pdf->Cell(20, 1, 'Prog Pag');


$pdf->SetX(152);
$pdf->Cell(20, 1, 'Taxa');

$pdf->SetX(165);
$pdf->Cell(20, 1, 'Desc');

$pdf->SetX(178);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(190);
$pdf->Cell(20, 1, 'Banco/Pag');

$pdf->SetX(213);
$pdf->Cell(20, 1, 'Nota');

$pdf->SetX(226);
$pdf->Cell(10, 1, 'Remessa');

$pdf->SetX(246);
$pdf->Cell(10, 1, 'Retorno');

$pdf->SetX(266);
$pdf->Cell(10, 1, 'Observação');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(8);
	$pdf->Cell(10, 5, $mostra['id'],0,0,R);

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId '");

	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(38);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(95);
	$pdf->Cell(10, 5, converteData($mostra['emissao']));

	$pdf->SetX(115);
	$pdf->Cell(10, 5, converteData($mostra['vencimento']));

	
	if(!empty($mostra['refaturamento_vencimento']) ){
			 
			$pdf->SetX(135);
			$pdf->Cell(10, 5, converteData($mostra['refaturamento_vencimento']));

		}else{
			$pdf->SetX(141);
			$pdf->Cell(10, 5, "-");

	}
		

	$pdf->SetX(154);
	$pdf->Cell(10, 5, converteValor($mostra['juros']),0,0,'R');

	$pdf->SetX(165);
	$pdf->Cell(10, 5, converteValor($mostra['desconto']),0,0,'R');
	$valorLiquido = $mostra['valor']+$mostra['juros']-$mostra['desconto'];
	$pdf->SetX(179);
	$pdf->Cell(10, 5, converteValor($valorLiquido),0,0,'R');

	$bancoId=$mostra['banco'];
	$banco = mostra('banco',"WHERE id ='$bancoId'");
	$formapagId=$mostra['formpag'];
	$formapag = mostra('formpag',"WHERE id ='$formapagId'");
	$pdf->SetX(190);
	$pdf->Cell(10, 5, $banco['nome']. "|".$formapag['nome']);

	$pdf->SetX(212);
	$pdf->Cell(10, 5, $mostra['nota'],0,0,'R');

	$pdf->SetX(226);
	$pdf->Cell(10, 5, $mostra['remessa']);

	$pdf->SetX(246);
	$pdf->Cell(10, 5, $mostra['retorno']);

	$pdf->SetX(266);
	$pdf->Cell(10, 5, $mostra['observacao']);

	//if(empty($mostra['imprimir'])){
//		$pdf->SetX(266);
//		$pdf->Cell(10, 5, '-');
//	}else{
//		$pdf->SetX(266);
//		$pdf->Cell(10, 5, date('d/m/Y H:i:s',strtotime($mostra['imprimir']) ) );
//	}

	$i=$i+1;
	if ($i>29){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->Cell(25, 1, converteData($data1));
		$pdf->Cell(20, 1, 'Data Fim :');
		$pdf->Cell(22, 1, converteData($data2));
		if(isset($contratoTipo)){
			$contrato_tipo = mostra('contrato_tipo',"WHERE id ='$contratoTipo '");
			$pdf->Cell(20, 1, 'Tipo :');
			$pdf->Cell(22, 1, $contrato_tipo['nome']);
		}
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(20);
$pdf->Cell(20, 1, 'Controle');

$pdf->SetX(38);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(95);
$pdf->Cell(20, 1, 'Emissão');

$pdf->SetX(111);
$pdf->Cell(20, 1, 'Vencimento');

$pdf->SetX(134);
$pdf->Cell(20, 1, 'Prog Pag');


$pdf->SetX(152);
$pdf->Cell(20, 1, 'Taxa');

$pdf->SetX(165);
$pdf->Cell(20, 1, 'Desc');

$pdf->SetX(178);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(190);
$pdf->Cell(20, 1, 'Banco/Pag');

$pdf->SetX(213);
$pdf->Cell(20, 1, 'Nota');

$pdf->SetX(226);
$pdf->Cell(10, 1, 'Remessa');

$pdf->SetX(246);
$pdf->Cell(10, 1, 'Retorno');

$pdf->SetX(266);
$pdf->Cell(10, 1, 'B Impresso');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . number_format($valor_total,2,',','.'));
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>