<?php
require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Serasa";
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

$valor_total = soma('receber',"WHERE serasa_data>='$data1' AND serasa_data<='$data2' AND serasa='1'",'valor');
$total = conta('receber',"WHERE serasa_data>='$data1' AND serasa_data<='$data2' AND serasa='1'");
$leitura = read('receber',"WHERE serasa_data>='$data1' AND serasa_data<='$data2' AND serasa='1' ORDER BY serasa_data ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(25, 1, converteData($data1));

if(!empty($statusId )){
	$pdf->Cell(22, 1, 'Status :');
	$pdf->Cell(25, 1, $status['nome']);
}

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
$pdf->Cell(20, 1, 'Data serasa');

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

foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();

		$pdf->SetX(10);
		$pdf->Cell(10, 5, $mostra['id'],0,0,R);

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");

		$pdf->SetX(24);
		$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$pdf->SetX(41);
		$pdf->Cell(10, 5, substr($cliente['nome'],0,30));

		$pdf->SetX(100);
		$pdf->Cell(10, 5, converteData($mostra['emissao']));

		$pdf->SetX(122);
		$pdf->Cell(10, 5, converteData($mostra['vencimento']));

		$pdf->SetX(153);
		$pdf->Cell(10, 5, converteData($mostra['serasa_data']));
		
		$pdf->SetX(183);
		$pdf->Cell(10, 5, converteValor($mostra['valor']),0,0,'R');

		$bancoId=$mostra['banco'];
		$banco = mostra('banco',"WHERE id ='$bancoId'");
		$formapagId=$mostra['formpag'];
		$formapag = mostra('formpag',"WHERE id ='$formapagId'");
		$pdf->SetX(200);
		$pdf->Cell(10, 5, $banco['nome']. "|".$formapag['nome']);

		$pdf->SetX(222);
		$pdf->Cell(10, 5, $mostra['nota']);


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