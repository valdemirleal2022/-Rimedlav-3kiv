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
	  $titulo="Clientes Sem Email";
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


$total=0;
$leitura = read('cliente',"WHERE id ORDER BY email ASC, email_financeiro ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(5);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(150);
$pdf->Cell(20, 1, 'Telefone');
$pdf->SetX(180);
$pdf->Cell(20, 1, 'Email|Email Financeiro');
$pdf->SetX(250);
$pdf->Cell(10, 1, 'Contrato');
$pdf->SetX(270);
$pdf->Cell(10, 1, 'Consultor');
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;


foreach($leitura as $mostra):

	$listar='SIM';
	$emailTeste=substr($mostra['email'],0,2);

	if(is_numeric($emailTeste)){
         $listar='SIM';
	}else{
		$listar='NAO';
	}
	if(empty($mostra['email'])){
        $listar='SIM';
	}
	
	if($listar=='SIM'){
		 $total++;

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->SetX(5);
		$pdf->Cell(10, 5,substr($mostra['nome'],0,30));

		$endereco=substr($mostra['endereco'],0,50).','.$mostra['numero'].' - '.$mostra['complemento'];
		$pdf->SetX(70);
		$pdf->Cell(10, 5, substr($endereco,0,30));

		$pdf->SetX(120);
		$pdf->Cell(10, 5, substr($mostra['bairro'],0,15));

		$pdf->SetX(150);
		$pdf->Cell(10, 5, substr($mostra['telefone'],0,20));

		$pdf->SetX(180);
		$pdf->Cell(10, 5, $mostra['email'].'|'.$mostra['email_financeiro']);

		$clienteId = $mostra['id'];
		$contrato = mostra('contrato',"WHERE id_cliente ='$clienteId'");
		
		$pdf->SetX(250);
		$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

		$pdf->SetX(270);
		$consultorId=$contrato['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$pdf->Cell(10, 5, $consultor['nome']);


		$i=$i+1;
	}
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(80);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Telefone');
		$pdf->SetX(180);
		$pdf->Cell(20, 1, 'Email|Email Financeiro');
		$pdf->SetX(250);
		$pdf->Cell(10, 1, 'Contrato');
		$pdf->SetX(270);
		$pdf->Cell(10, 1, 'Consultor');
		$pdf->Line(5, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>