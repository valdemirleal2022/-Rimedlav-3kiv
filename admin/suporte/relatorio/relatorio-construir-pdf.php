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
	  $titulo="Relatorio de Faturamento";
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

$dia = $_SESSION[ 'dia' ];
$banco = $_SESSION[ 'banco' ];
$dataExtrato = $_SESSION[ 'dataExtrato' ];
$contratoTipo=$_SESSION['contratoTipo'];

$valor_total = soma('contrato',"WHERE id AND dia_fechamento='$dia' AND inicio<'$dataExtrato' 
						AND	status<>'9'",'valor_mensal');
$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND inicio<'$dataExtrato' 
									AND status<>'9'");
$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND inicio<'$dataExtrato'
								AND	status<>'9'	ORDER BY controle ASC");

if(!empty($contratoTipo)){
		$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND contrato_tipo='$contratoTipo' 							AND inicio<'$dataExtrato' AND status<>'9'");
	
		$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status<>'9' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'
					ORDER BY controle ASC");
}
$dataAnterior=diminuirMes($dataExtrato,1);
	
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data do Faturamento :');
$pdf->SetX(50);
$pdf->Cell(22, 1, converteData($dataExtrato));
$pdf->Ln(6);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->Cell(20, 1, 'Id|Controle');
$pdf->SetX(32);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Tipo de Contrato');
$pdf->SetX(122);
$pdf->Cell(20, 1, 'Valor Mensal');
$pdf->SetX(148);
$pdf->Cell(20, 1, 'Inicio');
$pdf->SetX(165);
$pdf->Cell(20, 1, 'Fech');
$pdf->SetX(178);
$pdf->Cell(10, 1, 'Venc');
$pdf->SetX(190);
$pdf->Cell(10, 1, 'Emissao');
$pdf->SetX(207);
$pdf->Cell(10, 1, 'Vencimento');
$pdf->SetX(233);
$pdf->Cell(10, 1, 'Faturado');
$pdf->SetX(254);
$pdf->Cell(10, 1, 'Anterior');
$pdf->SetX(271);
$pdf->Cell(10, 1, 'Diferença');
$pdf->Line(10, 35, 290, 35); // insere linha divisória
$pdf->Ln(3);
$i=0;

$totalFaturar=0;
$totalFaturado=0;
$totalFaturadoAnterior=0;
$totalDiferenca=0;

foreach($leitura as $contrato):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();
	$pdf->SetX(20);
	$pdf->Cell(10, 5, $contrato['id'].'|'.substr($contrato['controle'],0,6), 0, 0, 'R');

	$pdf->SetX(32);
	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,30));

	$tipoId = $contrato['contrato_tipo'];
	$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");

	$pdf->SetX(90);
	$pdf->Cell(10, 5, $tipo['nome']);

	$fechamento = $contrato['dia_fechamento'];
	$vencimento = $contrato['dia_vencimento'];

	$mes = date( 'm', strtotime( $dataExtrato ) );
	$ano = date( 'Y', strtotime( $dataExtrato ) );

	$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
	$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));

	$data1 = date("Y-m-d", strtotime("$faturamento -1 month"));
	$data2 = date("Y-m-d", strtotime("$faturamento -1 days"));
	
	$contratoId = $contrato['id'];
	$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$data1' 
				AND vencimento>='$data2' AND id_contrato='$contratoId'" );

	$pdf->SetX(133);
	$pdf->Cell(10, 5, converteValor($tipoColeta['valor_mensal']),0,0,'R');

	$totalFaturar=$totalFaturar+$tipoColeta['valor_mensal'];

	$pdf->SetX(146);
	$pdf->Cell(10, 5, converteData($contrato['inicio']));
	
	$pdf->SetX(168);
	$pdf->Cell(10, 5, $contrato['dia_fechamento']);
	
	$pdf->SetX(179);
	$pdf->Cell(10, 5, $contrato['dia_vencimento']);

	$receber = mostra('receber',"WHERE id_cliente='$clienteId' AND emissao='$dataExtrato'");
	$valorFaturado=$receber['valor']+$receber['juros']-$receber['desconto'];

	$pdf->SetX(190);
	$pdf->Cell(10, 5, converteData($receber['emissao']));
	$pdf->SetX(210);
	$pdf->Cell(10, 5, converteData($receber['vencimento']));
	$pdf->SetX(240);
    $pdf->Cell(10, 5,converteValor($valorFaturado),0,0,'R');

	$totalFaturado=$totalFaturado+$valorFaturado;
	
	$valorAnteiro=0;
	$receber = mostra('receber',"WHERE id_cliente='$clienteId' AND emissao='$dataAnterior'");
	if($receber){
		$valorAnteiro=$receber['valor']+$receber['juros']-$receber['desconto'];
	}
	
	$pdf->SetX(260);
    $pdf->Cell(10, 5,converteValor($valorAnteiro),0,0,'R');
	
	$totalFaturadoAnterior=$totalFaturadoAnterior+$valorAnteiro;

	$ValorDiferenca=$valorFaturado-$valorAnteiro;

	$pdf->SetX(279);
    $pdf->Cell(10, 5,converteValor($ValorDiferenca),0,0,'R');

	$totalDiferenca=$totalDiferenca+$ValorDiferenca;
	

	$i=$i+1;

	if ($i>28){
		
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->SetFont('Arial','B',10);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, 'Data do Faturamento :');
		$pdf->SetX(50);
		$pdf->Cell(22, 1, converteData($dataExtrato));
		$pdf->Ln(6);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Cell(20, 1, 'Id|Controle');
		$pdf->SetX(32);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(90);
		$pdf->Cell(20, 1, 'Tipo de Contrato');
		$pdf->SetX(122);
		$pdf->Cell(20, 1, 'Valor Mensal');
		$pdf->SetX(148);
		$pdf->Cell(20, 1, 'Inicio');
		$pdf->SetX(165);
		$pdf->Cell(20, 1, 'Fech');
		$pdf->SetX(178);
		$pdf->Cell(10, 1, 'Venc');
		$pdf->SetX(190);
		$pdf->Cell(10, 1, 'Emissao');
		$pdf->SetX(207);
		$pdf->Cell(10, 1, 'Vencimento');
		$pdf->SetX(233);
		$pdf->Cell(10, 1, 'Faturado');
		$pdf->SetX(254);
		$pdf->Cell(10, 1, 'Anterior');
		$pdf->SetX(271);
		$pdf->Cell(10, 1, 'Diferença');
		$pdf->Line(10, 35, 290, 35); // insere linha divisória
		$pdf->Ln(3);
		$i=0;
	}

endforeach;

$pdf->ln();

$pdf->SetX(120);
$pdf->Cell(10, 5, 'Total');
$pdf->SetX(133);
$pdf->Cell(10, 5, converteValor($totalFaturar),0,0,'R');

$pdf->SetX(240);
$pdf->Cell(10, 5, converteValor($totalFaturado),0,0,'R');

$pdf->SetX(260);
$pdf->Cell(10, 5, converteValor($totalFaturadoAnterior),0,0,'R');

$pdf->SetX(279);
$pdf->Cell(10, 5, converteValor($totalDiferenca),0,0,'R');

$pdf->ln(5); 

$pdf->SetFont('Arial','B',8);
$pdf->ln();

$pdf->Cell(10, 5, 'Total de registros : '. $total);


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>