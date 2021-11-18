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
	  $titulo="Relatorio de Faturados";
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
$contratoTipo=$_SESSION['contratoTipo'];
$enviar_boleto_correio=$_SESSION['enviar_boleto_correio'];


$valor_total = soma('receber',"WHERE emissao>='$data1' AND emissao<='$data2'",'valor');
$total = conta('receber',"WHERE emissao>='$data1' AND emissao<='$data2'");
$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' ORDER BY emissao ASC");

if ($enviar_boleto_correio=='1') {
	$valor_total = soma('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1'",'valor');
	$total = conta('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1'");
	$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1' ORDER BY emissao ASC");
}

if (!empty($contratoTipo)) {
	$valor_total = soma('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo' ",'valor');
	$total = conta('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo' ");
	$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo'  ORDER BY emissao ASC");
}

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));

$pdf->Cell(22, 1, $enviar_boleto_correio);

if ($enviar_boleto_correio=='1') {
	$pdf->Cell(22, 1, 'Boleto Enviado por Correio');
}

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id/Controle');

$pdf->SetX(32);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(72);
$pdf->Cell(20, 1, 'Tipo de Contrato');

$pdf->SetX(109);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(126);
$pdf->Cell(20, 1, 'Taxa');

$pdf->SetX(140);
$pdf->Cell(20, 1, 'Emissão');

$pdf->SetX(158);
$pdf->Cell(20, 1, 'Vencimento');

$pdf->SetX(180);
$pdf->Cell(20, 1, 'Faturamento');

$pdf->SetX(204);
$pdf->Cell(20, 1, 'N. Nota');

$pdf->SetX(220);
$pdf->Cell(10, 1, 'Remessa');

$pdf->SetX(240);
$pdf->Cell(10, 1, 'Retorno');

$pdf->SetX(261);
$pdf->Cell(10, 1, 'Boleto Impresso');

$pdf->Line(10, 34, 290, 34); // insere linha divisória
$pdf->Ln(3);
$i=0;


foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	$contradoId=$mostra['id'].'|'.substr($contrato['controle'],0,6);

	$pdf->SetX(20);	
	$pdf->Cell(10, 5, $contradoId,0,0,'R');

	$pdf->SetX(32);
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));
	
	$tipoId = $contrato['contrato_tipo'];
	$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
	$pdf->SetX(72);
	$pdf->Cell(10, 5, substr($tipo['nome'],0,15) );

	$pdf->SetX(110);
	$pdf->Cell(10, 5, converteValor($mostra['valor']),0,0,'R');

	$pdf->SetX(126);
	$pdf->Cell(10, 5, converteValor($mostra['juros']),0,0,'R');

	$pdf->SetX(140);
	$pdf->Cell(10, 5, converteData($mostra['emissao']));

	$pdf->SetX(160);
	$pdf->Cell(10, 5, converteData($mostra['vencimento']));

	$pdf->SetX(180);
	$pdf->Cell(10, 5, date('d/m/Y H:i',strtotime($mostra['data_faturamento']) ) );

	$pdf->SetX(208);
	$pdf->Cell(10, 5, $mostra['nota'],0,0,'R');

	$pdf->SetX(220);
	$pdf->Cell(10, 5, $mostra['remessa']);

	$pdf->SetX(240);
	$pdf->Cell(10, 5, $mostra['retorno']);

	if(empty($mostra['imprimir'])){
		$pdf->SetX(262);
		$pdf->Cell(10, 5, '-');
	}else{
		$pdf->SetX(262);
		$pdf->Cell(10, 5, date('d/m/Y H:i:s',strtotime($mostra['imprimir']) ) );
	}


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

	$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id/Controle');

$pdf->SetX(32);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(72);
$pdf->Cell(20, 1, 'Tipo de Contrato');

$pdf->SetX(109);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(126);
$pdf->Cell(20, 1, 'Taxa');

$pdf->SetX(140);
$pdf->Cell(20, 1, 'Emissão');

$pdf->SetX(158);
$pdf->Cell(20, 1, 'Vencimento');

$pdf->SetX(180);
$pdf->Cell(20, 1, 'Faturamento');

$pdf->SetX(204);
$pdf->Cell(20, 1, 'N. Nota');

$pdf->SetX(220);
$pdf->Cell(10, 1, 'Remessa');

$pdf->SetX(240);
$pdf->Cell(10, 1, 'Retorno');

$pdf->SetX(261);
$pdf->Cell(10, 1, 'Boleto Impresso');

		$pdf->Line(10, 34, 290, 34); // insere linha divisória
		$pdf->Ln(3);
		$i=0;
	}
endforeach;

$pdf->ln(10);
$pdf->SetX(30);
$pdf->Cell(10, 5, 'Faturados ');

$pdf->SetFont('Arial','B',8);
$pdf->ln(5);
$pdf->SetX(30);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valor_total) );
$pdf->SetX(180);
$pdf->Cell(10, 5, 'Total de registros : '. $total);


$pdf->ln(10);
$pdf->SetX(30);
$pdf->Cell(10, 5, 'Baixado ');

$leituraBanco = read('banco',"WHERE id ORDER BY id ASC");
foreach($leituraBanco as $mostraBanco):

	$bancoId = $mostraBanco['id'];
	$valorTotal = soma('receber',"WHERE emissao>='$data' AND emissao<='$data'AND banco='$bancoId' AND status='Baixado'",'valor');
	
	if($valorTotal>0){
		$pdf->SetFont('Arial','B',8);
		$pdf->ln(5);
		$pdf->SetX(30);
		$pdf->Cell(10, 5, 'Banco - '. $mostraBanco['nome']);
		$pdf->SetX(112);
		$pdf->Cell(10, 5, converteValor($valorTotal),0,0,'R');
	}

endforeach;

$pdf->ln(10);
$pdf->SetX(30);
$pdf->Cell(10, 5, 'Em Aberto ');

$leituraBanco = read('banco',"WHERE id ORDER BY id ASC");
foreach($leituraBanco as $mostraBanco):

	$bancoId = $mostraBanco['id'];
	$valorTotal = soma('receber',"WHERE emissao>='$data' AND emissao<='$data' AND banco='$bancoId' AND status<>'Baixado'",'valor');
	
	if($valorTotal>0){
		$pdf->SetFont('Arial','B',8);
		$pdf->ln(5);
		$pdf->SetX(30);
		$pdf->Cell(10, 5, 'Banco - '. $mostraBanco['nome']);
		$pdf->SetX(112);
		$pdf->Cell(10, 5, converteValor($valorTotal),0,0,'R');
	}

endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>