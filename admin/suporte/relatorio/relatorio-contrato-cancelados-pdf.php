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
	  $titulo="Contratos Cancelados";
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

$valor_total = soma('contrato',"WHERE id AND status='9' AND data_cancelamento>='$data1' 
											  AND data_cancelamento<='$data2'",'valor_mensal');
$total = conta('contrato',"WHERE id AND data_cancelamento>='$data1' 
												AND data_cancelamento<='$data2' AND status='9'");
$leitura = read('contrato',"WHERE id AND data_cancelamento>='$data1' 
											  AND data_cancelamento<='$data2' AND status='9' 
											  ORDER BY data_cancelamento ASC");

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
$pdf->Cell(25, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(100);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(128);
$pdf->Cell(20, 1, 'Inicio');
$pdf->SetX(142);
$pdf->Cell(20, 1, 'Vl Mensal');
$pdf->SetX(162);
$pdf->Cell(20, 1, 'A partir');
$pdf->SetX(180);
$pdf->Cell(10, 1, 'Rota');
$pdf->SetX(190);
$pdf->Cell(10, 1, 'Motivo');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Tipo de Contrato');


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
	$pdf->Cell(10, 5, substr($cliente['nome'],0,23));

	$pdf->SetX(75);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$pdf->SetX(100);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(126);
	$pdf->Cell(10, 5, converteData($mostra['inicio']));

	$pdf->SetX(148);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal']));
	
	$pdf->SetX(162);
	$pdf->Cell(10, 5, converteData($mostra['data_cancelamento']));

	$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='5' 
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

	$pdf->SetX(180);
	$pdf->Cell(10, 5,$rota );

	$pdf->SetX(190);
	$pdf->Cell(10, 5,substr($contratoBaixa['motivo'],0,60) );

	$contratoTipoId = $mostra['contrato_tipo'];
				$monstraContratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");

	$pdf->SetX(260);
	$pdf->Cell(10, 5,$monstraContratoTipo['nome'] );
			

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(1);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Ln(5);

		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id | Controle');
		$pdf->SetX(30);
		$pdf->Cell(25, 1, 'Nome');
		$pdf->SetX(75);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(128);
		$pdf->Cell(20, 1, 'Inicio');
		$pdf->SetX(142);
		$pdf->Cell(20, 1, 'Vl Mensal');
		$pdf->SetX(162);
		$pdf->Cell(20, 1, 'A partir');
		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Rota');
		$pdf->SetX(190);
		$pdf->Cell(10, 1, 'Motivo');
		$pdf->SetX(260);
		$pdf->Cell(10, 1, 'Tipo de Contrato');

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