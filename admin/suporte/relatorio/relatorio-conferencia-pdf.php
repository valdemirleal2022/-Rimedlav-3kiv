<?php
 
$data1 = $_SESSION['dataInicio'];
$data2 = $_SESSION['dataFinal'];
$rota =$_SESSION[ 'rotaColeta' ];

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
	  $titulo="Conferencia de Rota";
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

$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' ORDER BY rota ASC, hora ASC");

if(!empty($rota)){
	$rota =$_SESSION[ 'rotaColeta' ];
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rota'");
	$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rota'");
	$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rota' ORDER BY rota ASC, hora ASC");
}

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();

$rotaPagina='';
$total=0;

foreach($leitura as $mostra):

	if($rotaPagina<>$mostra['rota']){
		
		$rotaId =$mostra['rota'];
		$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");
		
		if(!empty($rotaPagina)){
			$pdf->SetFont('Arial','B',8);
			$pdf->ln(10);
			$pdf->Cell(10, 5, 'Total de registros : '. $total);
			$pdf->SetX(130);
			$pdf->Cell(10, 5, 'Assistente : ______________________');
			$pdf->SetX(200);
			$pdf->Cell(10, 5, 'Coordenador : ______________________');
			$total=0;
		}

		$rotaPagina=$mostra['rota'];
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->Cell(22, 1, converteData($data1));
		$pdf->Cell(22, 1, 'Data Fim :');
		$pdf->Cell(22, 1, converteData($data2));
		$pdf->Cell(22, 1, 'Rota :');
		$pdf->Cell(22, 1, $rotaColeta['nome']);
		$pdf->Cell(22, 1, 'Equipe : _______________/ _________________');
		
		$pdf->Line(10, 29, 290, 29); // insere linha divisória (Col, Lin, Col, Lin)
		
		$pdf->Ln(6);
		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Hora');
		$pdf->SetX(22);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(70);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(125);
		$pdf->Cell(20, 1, 'Bairro');
		
		$pdf->SetX(148);
		$pdf->Cell(20, 1, 'Referencia');
		
		$pdf->SetX(225);
		$pdf->Cell(20, 1, 'Contrato');
		$pdf->SetX(245);
		$pdf->Cell(20, 1, 'Numero');
		$pdf->SetX(265);
		$pdf->Cell(10, 1, 'Coletado');
		$pdf->SetX(285);
		$pdf->Cell(10, 1, 'M');
		
		$pdf->Line(10, 34, 290, 34); // insere linha divisória
		$pdf->Ln(5);
		$i=0;
	}

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['hora']);
	
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId'");

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	$pdf->SetX(22);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));
	
	$endereco=substr($cliente['endereco'],0,20).','.$cliente['numero'].' - '.substr($cliente['complemento'],0,10);

	$pdf->SetX(70);
	$pdf->Cell(10, 5, $endereco);

	$pdf->SetX(125);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$pdf->SetX(148);
	$pdf->Cell(10, 5, substr($cliente['referencia'],0,40));

	$pdf->SetX(225);
	$pdf->Cell(10, 5, $mostra['id_contrato']  );

	$pdf->SetX(245);
	$pdf->Cell(10, 5, $mostra['id']);

	$pdf->SetX(265);
	$pdf->Cell(10, 5, _________);

	$pdf->SetX(285);
	$pdf->Cell(10, 5, $mostra['manifesto']);
 

	$pdf->SetX(284);
	//$pdf->Cell(10, 5, $mostra['rota']);

	
	$total++;

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);

		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->Ln(6);
		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Hora');
		$pdf->SetX(22);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(70);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(125);
		$pdf->Cell(20, 1, 'Bairro');
		
		$pdf->SetX(148);
		$pdf->Cell(20, 1, 'Referencia');
		
		$pdf->SetX(225);
		$pdf->Cell(20, 1, 'Contrato');
		$pdf->SetX(245);
		$pdf->Cell(20, 1, 'Numero');
		$pdf->SetX(265);
		$pdf->Cell(10, 1, 'Coletado');
		$pdf->SetX(285);
		$pdf->Cell(10, 1, 'M');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>