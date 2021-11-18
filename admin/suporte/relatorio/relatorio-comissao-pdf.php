<?php


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$consultorId = $_SESSION['consultor'];

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Comissão de Consultor";
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

$leitura = read('receber',"WHERE id AND pagamento>='$data1' AND pagamento<='$data2' AND status<>'Em Aberto'	ORDER BY pagamento ASC");

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
$pdf->Cell(20, 1, 'Consultor');

$pdf->SetX(128);
$pdf->Cell(20, 1, 'Pagamento');

$pdf->SetX(155);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(181);
$pdf->Cell(20, 1, '%');

$pdf->SetX(195);
$pdf->Cell(20, 1, 'Comissão');

$pdf->SetX(230);
$pdf->Cell(10, 1, 'Tipo');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

$totalValor=0;
$totalDesconto=0;
$totalJuros=0;

$total=0;

foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
	$receberId = $mostra['id'];
				
	$totalPagamento = conta('receber',"WHERE id_contrato ='$contratoId'");
		
	$contador = 1;
	$primeiraParcela = 0;
	$leituraReceber = read('receber',"WHERE id_contrato ='$contratoId' ORDER BY emissao ASC");
	foreach($leituraReceber as $receber):
		if($contador==1 and $receberId==$receber['id']){
			break;
		}
		$contador = $contador + 1 ; 	
		$primeiraParcela = $primeiraParcela +1;
	endforeach;	
		
	if($contrato['consultor']==$consultorId AND $primeiraParcela=='0' ){

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();

		$pdf->SetX(7);
		$pdf->Cell(10, 5, $mostra['id'],0,0,R);

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");

		$pdf->SetX(20);
		$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$pdf->SetX(38);
		$pdf->Cell(10, 5, substr($cliente['nome'],0,32));

		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");

		$pdf->SetX(100);
		$pdf->Cell(10, 5, $consultor['nome']);

		$pdf->SetX(130);
		$pdf->Cell(10, 5, converteData($mostra['pagamento']));

		$percentual=30.00;

		$comissao=($mostra['valor']*$percentual)/100;

		$pdf->SetX(158);
		$pdf->Cell(10, 5, converteValor($mostra['valor']),0,0,'R');

	 	$pdf->SetX(178);
	 	$pdf->Cell(10, 5, $percentual,0,0,'R');

		$valor_total+=$mostra['valor'];
		$valor_comissao+=$comissao;
		$total++;
		
		$pdf->SetX(202);
		$pdf->Cell(10, 5, converteValor($comissao),0,0,'R');

		$tipoId = $mostra['contrato_tipo'];
		$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
		$pdf->SetX(230);
		$pdf->Cell(10, 5, $tipo['nome']);

		$i=$i+1;
	}

	if ($i>29){
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
		$pdf->Cell(20, 1, 'Consultor');

		$pdf->SetX(128);
		$pdf->Cell(20, 1, 'Pagamento');

		$pdf->SetX(155);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(181);
		$pdf->Cell(20, 1, '%');

		$pdf->SetX(195);
		$pdf->Cell(20, 1, 'Comissão');

		$pdf->SetX(230);
		$pdf->Cell(10, 1, 'Tipo');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->ln(6);
$pdf->SetX(158);
$pdf->Cell(10, 5, converteValor($valor_total),0,0,'R');
$pdf->SetX(202);
$pdf->Cell(10, 5, converteValor($valor_comissao),0,0,'R');

$pdf->SetFont('Arial','B',8);

$pdf->ln(5);
$pdf->Cell(10, 5, 'Valor Comissão R$ ');
$pdf->SetX(50);
$pdf->Cell(10, 5, converteValor($valor_comissao),0,0,'R');

$valorIss=$valor_comissao/1.05;
$valorIss=$valor_comissao-$valorIss;
$valor_comissao=$valor_comissao-$valorIss;

$pdf->ln(5);
$pdf->Cell(10, 5, 'Desconto ISS R$ ');
$pdf->SetX(50);
$pdf->Cell(10, 5, converteValor($valorIss),0,0,'R');
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total Comissão R$');
$pdf->SetX(50);
$pdf->Cell(10, 5, converteValor($valor_comissao),0,0,'R');

$pdf->ln(7);
$pdf->Cell(10, 5, 'Total de registros : '. $total);


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>