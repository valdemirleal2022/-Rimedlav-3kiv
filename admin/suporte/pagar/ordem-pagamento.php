<?php


	$ordemId = $_GET['ordemImprimir'];
	$despesa = mostra('pagar',"WHERE id AND id='$ordemId'");

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
	  $titulo="Ordem de Pagamento";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Pсgina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

	$pdf=new RELATORIO();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '___________________________________________________________________________________________________');
	$pdf->Ln(10);


	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Id : '.$despesa['id']);
	$pdf->Ln(5);

 	$empresaId=$despesa['empresa'];
	$empresa = mostra('empresa_pagar',"WHERE id ='$empresaId'");

	$pdf->SetX(05);
	$pdf->Cell(5, 5, 'Empresa  : '. $empresa['nome']);

	$pdf->Ln(5);

	$fornecedorId=$despesa['fornecedor'];
	$fornecedor = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");

	$pdf->SetX(05);
	$pdf->Cell(5, 5, 'fornecedor  : '.$fornecedor['nome']);
	$pdf->Ln(5);

	$funcionarioId=$despesa['funcionario'];
	$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId'");

	$pdf->SetX(05);
	$pdf->Cell(5, 5, 'funcionario  : '. $funcionario['nome']);
	$pdf->Ln(5);

	$pdf->SetX(05);
	$pdf->Cell(5, 5, 'Documento  : '. $despesa['nota']);

	$pdf->SetX(80);
	$pdf->Cell(5, 5, 'Parcelas  : '. $despesa['parcela']);
	$pdf->Ln(5);
	
 	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Emissуo : '. converteData($despesa['emissao']));

	$pdf->SetX(55);
	$pdf->Cell(10, 5, 'Vencimento : '.converteData($despesa['vencimento']));

	$pdf->SetX(110);
	$pdf->Cell(10, 5, 'Programaчуo : '. converteData($despesa['programacao']));

	$pdf->SetX(160);
	$pdf->Cell(10, 5, 'Pagamento : ' . converteData($despesa['pagamento']));
	$pdf->Ln(5);

	$bancoId=$despesa['banco'];
	$banco = mostra('banco',"WHERE id ='$bancoId'");

	$pdf->SetX(05);
	$pdf->Cell(5, 5, 'Banco  : '. $banco['nome']);
	 
	$formpagId=$despesa['formpag'];
	$formpag = mostra('formpag',"WHERE id ='$formpagId'");

	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'Forma Pagamento : '. $formpag['nome']);
	$pdf->Ln(5);
	

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'codigo_barra : '. $despesa['codigo_barra']);
	$pdf->Ln(5);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Linha Digitсvel : '. $despesa['linha_digital']);
	$pdf->Ln(5);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Descriчуo : '. $despesa['descricao']);
	$pdf->Ln(5);
	$pdf->Ln(5);

	$contaId=$despesa['id_conta1'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo1'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor1']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta2'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo2'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor2']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta3'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo3'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor3']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta4'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo4'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor4']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta5'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo5'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor5']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta6'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo6'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor6']));
	$pdf->Ln(5);
	
	$contaId=$despesa['id_conta7'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo7'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor7']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta8'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo8'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor8']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta9'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo9'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor9']));
	$pdf->Ln(5);

	$contaId=$despesa['id_conta10'];
	$conta = mostra('pagar_conta',"WHERE id ='$contaId'");
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Conta : '. $conta['nome']);

	$grupoId=$despesa['id_grupo10'];
	$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");
	$pdf->SetX(105);
	$pdf->Cell(5, 5, 'grupo : '. $grupo['nome']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, 'Valor : '.converteValor($despesa['valor10']));
	$pdf->Ln(10);

 	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Valor Total : '.converteValor($despesa['valor']));

	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'Valor ICMS : '.converteValor($despesa['icms']));

	$pdf->SetX(160);
	$pdf->Cell(10, 5, 'Valor IPI : '.converteValor($despesa['ipi']));
	$pdf->Ln(10);


	$pdf->SetX(10);
	$pdf->Cell(5, 5, 'PEDIDO');

	$pdf->SetX(80);
	$pdf->Cell(5, 5, 'FINANCEIRO');

	$pdf->SetX(150);
	$pdf->Cell(5, 5, 'CONTROLLER');

	$pdf->Ln(20);

	$pdf->SetX(10);
	$pdf->Cell(5, 5, '___________________');

	$pdf->SetX(80);
	$pdf->Cell(5, 5, '___________________');

	$pdf->SetX(150);
	$pdf->Cell(5, 5, '___________________');

	
	$pdf->Ln(20);
	
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '====================================================================================================');
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();

	$i=$i+1;
 

ob_clean();  
$pdf->Output('ordem-pagamento.pdf', 'I');
 
?>