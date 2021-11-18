<?php


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

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
	  $titulo="Contratos Judicial";
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

 
$total = conta('contrato',"WHERE id AND data_judicial>='$data1' 
												AND data_judicial<='$data2' AND status='10'");
$leitura = read('contrato',"WHERE id AND data_judicial>='$data1' 
											  AND data_judicial<='$data2' AND status='10' 
											  ORDER BY data_judicial ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->Ln(5);

$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id | Controle');
$pdf->SetX(30);
$pdf->Cell(30, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(100);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Total Debito');
$pdf->SetX(150);
$pdf->Cell(20, 1, 'A partir');
$pdf->SetX(170);
$pdf->Cell(10, 1, 'Rota');
$pdf->SetX(190);
$pdf->Cell(10, 1, 'Motivo');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id'].'|'.substr($mostra['controle'],0,6));

	$contratoId = $mostra['id'];
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->SetX(30);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,22));

	$pdf->SetX(75);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$pdf->SetX(100);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	
	$totalDebito = soma('receber',"WHERE id_cliente ='$clienteId' AND status<>'Baixado'",'valor');
	$valor_total = $valor_total +$totalDebito;

	$pdf->SetX(128);
	$pdf->Cell(10, 5, converteValor($totalDebito));
	
	$pdf->SetX(150);
	$pdf->Cell(10, 5, converteData($mostra['data_judicial']));

	$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='2' 
					ORDER BY interacao ASC");
	
	if(!empty($mostra[ 'domingo_rota1' ])){
		$rotaId = $mostra[ 'domingo_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($mostra[ 'segunda_rota1' ])){
		$rotaId = $mostra[ 'segunda_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($mostra[ 'terca_rota1' ])){
		$rotaId = $mostra[ 'terca_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($mostra[ 'quarta_rota1' ])){
		$rotaId = $mostra[ 'quarta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($mostra[ 'quinta_rota1' ])){
		$rotaId = $mostra[ 'quinta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($mostra[ 'sexta_rota1' ])){
		$rotaId = $mostra[ 'sexta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($mostra[ 'sabado_rota1' ])){
		$rotaId = $mostra[ 'sabado_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}

	$pdf->SetX(170);
	$pdf->Cell(10, 5,$rota );

	$pdf->SetX(190);
	$pdf->Cell(10, 5,substr($contratoBaixa['motivo'],0,60) );


	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(1);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Ln(5);

		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id | Controle');
		$pdf->SetX(30);
		$pdf->Cell(30, 1, 'Nome');
		$pdf->SetX(75);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Total Debito');
		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'A partir');
		$pdf->SetX(170);
		$pdf->Cell(10, 1, 'Motivo');

		$pdf->Line(5, 33, 290, 33); // insere linha divisória
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valor_total));
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>