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
	  $titulo="Refaturados";
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

$leitura = read('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' ORDER BY refaturamento_data ASC");
$total = conta('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' ORDER BY refaturamento_data ASC");
$valor_total = soma('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2'",'refaturamento_valor');

if(!empty($contratoTipo )){
	$leitura = read('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' AND contrato_tipo='$contratoTipo' ORDER BY refaturamento_data ASC");
	$total = conta('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2'AND contrato_tipo='$contratoTipo'");
	$valor_total = soma('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='1' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' AND contrato_tipo='$contratoTipo'",'refaturamento_valor');
}

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(25, 1, converteData($data1));


$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(20);
$pdf->Cell(20, 1, 'Controle');

$pdf->SetX(41);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Tipo de Contrato');

$pdf->SetX(135);
$pdf->Cell(20, 1, 'Vl Atual');

$pdf->SetX(160);
$pdf->Cell(20, 1, 'Vl Refaturar');

$pdf->SetX(190);
$pdf->Cell(20, 1, 'Faturamento');

$pdf->SetX(220);
$pdf->Cell(20, 1, 'Motivo');

$pdf->SetX(265);
$pdf->Cell(10, 1, 'Autorização');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();

		$pdf->SetX(10);
		$pdf->Cell(10, 5, $mostra['id'],0,0,R);

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");

		$pdf->SetX(22);
		$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$pdf->SetX(41);
		$pdf->Cell(10, 5, substr($cliente['nome'],0,30));

		$contratoTipoId = $mostra['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
		$pdf->SetX(100);
		$pdf->Cell(10, 5, $contratoTipo['nome']);

		$pdf->SetX(140);
		$pdf->Cell(10, 5, converteValor($mostra['valor_anterior']),0,0,'R');

		$pdf->SetX(165);
		$pdf->Cell(10, 5, converteValor($mostra['refaturamento_valor']),0,0,'R');

		$pdf->SetX(190);
		$pdf->Cell(10, 5, converteData($mostra['refaturamento_data']));

		$motivoId=$mostra['refaturamento_motivo'];
		$motivo = mostra('motivo_refaturamento',"WHERE id ='$motivoId'");
			
		$pdf->SetX(220);
		$pdf->Cell(10, 5, $motivo['nome']);


		if($mostra['refaturamento_autorizacao']=='1'){
				$autorizacao='Autorizado';
			}elseif($mostra['refaturamento_autorizacao']=='2'){
				$autorizacao='Não Autorizado';
			}elseif($mostra['refaturamento_autorizacao']=='0'){
				$autorizacao='guardando</td>';
		}
		
 		$pdf->SetX(265);
		$pdf->Cell(10, 5, $autorizacao);

		$i=$i+1;
		
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->Cell(25, 1, converteData($data1));
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Controle');

		$pdf->SetX(41);
		$pdf->Cell(20, 1, 'Nome');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Emissão');

		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Vencimento');

		$pdf->SetX(147);
		$pdf->Cell(20, 1, 'Data Recuperação');

		$pdf->SetX(185);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(200);
		$pdf->Cell(20, 1, 'Banco/Pag');

		$pdf->SetX(222);
		$pdf->Cell(10, 1, 'Nota');

		$pdf->SetX(240);
		$pdf->Cell(10, 1, 'Retorno');

		$pdf->SetX(261);
		$pdf->Cell(10, 1, 'Boleto Impresso');


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