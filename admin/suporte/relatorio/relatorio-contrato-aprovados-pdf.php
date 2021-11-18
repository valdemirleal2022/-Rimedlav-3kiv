 
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
	  $titulo="Contratos Aprovados";
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

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1' 
											AND aprovacao<='$data2'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1' AND aprovacao<='$data2'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1'  
											AND aprovacao<='$data2' ORDER BY aprovacao ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(25, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(25, 1, converteData($data2));

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(25);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(95);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(116);
$pdf->Cell(20, 1, 'Valor Mensal');
$pdf->SetX(142);
$pdf->Cell(20, 1, 'Aprovação');
$pdf->SetX(165);
$pdf->Cell(10, 1, 'Inicio');
$pdf->SetX(185);
$pdf->Cell(10, 1, 'Frequencia');
$pdf->SetX(210);
$pdf->Cell(10, 1, 'Dia(s) da Semana');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Rota');
$pdf->SetX(270);
$pdf->Cell(10, 1, 'Hora');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id'].'|'.substr($mostra['controle'],0,6));

	$pdf->SetX(25);
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));

	$pdf->SetX(70);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,13));

	$pdf->SetX(95);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(128);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal']),0,0,'R');
	
	$pdf->SetX(145);
	$pdf->Cell(10, 5, converteData($mostra['aprovacao']));

	$pdf->SetX(164);
	$pdf->Cell(10, 5, converteData($mostra['inicio']));

	$diaSemana='';
	$rota='';
	$hora='';
	if($mostra['domingo']==1){
		$diaSemana = ' Dom';
		$hora=$mostra['domingo_hora1'];
		$rotaId = $mostra[ 'domingo_rota1' ];
		$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rotaMostra[ 'nome' ];
	}
	if($mostra['segunda']==1){
		$diaSemana = $diaSemana . ' Seg';
		$hora=$mostra['segunda_hora1'];
		$rotaId = $mostra[ 'segunda_rota1' ];
		$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rotaMostra[ 'nome' ];
	}
	if($mostra['terca']==1){
		$diaSemana = $diaSemana . ' Ter';
		$hora=$mostra['terca_hora1'];
		$rotaId = $mostra[ 'terca_rota1' ];
		$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rotaMostra[ 'nome' ];
	}
	if($mostra['quarta']==1){
		$diaSemana = $diaSemana . ' Qua';
		$hora=$mostra['quarta_hora1'];
		$rotaId = $mostra[ 'quarta_rota1' ];
		$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rotaMostra[ 'nome' ];
	}
	if($mostra['quinta']==1){
		$diaSemana = $diaSemana . ' Qui';
		$hora=$mostra['quinta_hora1'];
		$rotaId = $mostra[ 'quinta_rota1' ];
		$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rotaMostra[ 'nome' ];
	}
	if($mostra['sexta']==1){
		$diaSemana = $diaSemana . ' Sex';
		$hora=$mostra['sexta_hora1'];
		$rotaId = $mostra[ 'sexta_rota1' ];
		$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rotaMostra[ 'nome' ];
	}
	if($mostra['sabado']==1){
		$diaSemana = $diaSemana . ' Sab';
		$hora=$mostra['sabado_hora1'];
		$rotaId = $mostra[ 'sabado_rota1' ];
		$rotaMostra = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rotaMostra[ 'nome' ];
	}

	// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
	$frequenciaId = $mostra[ 'frequencia' ];
	$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
	$frequencia = $frequencia[ 'nome' ];

	$pdf->SetX(185);
	$pdf->Cell(10, 5, $frequencia);

	$pdf->SetX(210);
	$pdf->Cell(10, 5, $diaSemana);

	$pdf->SetX(260);
	$pdf->Cell(10, 5, $rota);

	$pdf->SetX(270);
	$pdf->Cell(10, 5,  $hora);


	$i=$i+1;
	if ($i>47){
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
		$pdf->Line(2, 28, 205, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(89);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(111);
		$pdf->Cell(20, 1, 'Valor Mensal');
		$pdf->SetX(137);
		$pdf->Cell(20, 1, 'Aprovação');
		$pdf->SetX(160);
		$pdf->Cell(10, 1, 'Inicio');
		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Status');
		$pdf->Line(2, 33, 205, 33); // insere linha divisória
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