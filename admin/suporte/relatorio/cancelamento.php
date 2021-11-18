<?php

require_once( "js/fpdf/fpdf.php" );
define( "FPDF_FONTPATH", "font/" );

class RELATORIO extends FPDF {
	function Header() {
		$titulo = SITENOME;
		$this->SetFont( 'Arial', 'B', 12 );
		$titulo = "SUSPENSÃO DE CONTRATO";
		$this->Cell( 0, 5, $titulo, 0, 0, 'C' );
		$this->Ln();
	}
	function Footer() {
		$this->SetY( -15 );
		$this->SetFont( 'Arial', 'I', 9 );
		$this->Cell( 0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C' );
	}
}

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Ln();


	if(!empty($_GET['cancelamentoId'])){
		$cancelamentoId = $_GET['cancelamentoId'];
	}

	if(!empty($cancelamentoId)){
			$edit = mostra('contrato_cancelamento',"WHERE id = '$cancelamentoId'");
			if(!$edit){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			$contratoId = $edit['id_contrato'];;
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			if(!$contrato){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			 
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			if(!$cliente){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
	}

	
	$pdf->ln(5);
	
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Nome :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $cliente['nome']);

	$pdf->SetX(160);
	$pdf->Cell(10, 5, 'Status :');
	$pdf->SetX(175);
	$pdf->Cell(10, 5, $edit['status']);

	$pdf->ln(5);

	$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Endereço :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $endereco);

	$pdf->ln(5);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Telefone :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $cliente['telefone']);

	$pdf->SetX(85);
	$pdf->Cell(10, 5, 'Contato :');
	$pdf->SetX(105);
	$pdf->Cell(10, 5, $cliente['contato']);

	$pdf->ln(5);

	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Contrato n. :');
	$pdf->SetX(28);
	$pdf->Cell(10, 5, $contrato['id']. '|'. substr($contrato['controle'],0,6));

	
	$tipoContratoId=$contrato['contrato_tipo'];
	$tipoContrato = mostra('contrato_tipo',"WHERE id ='$tipoContratoId'");
	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Tipo :');
	$pdf->SetX(152);
	$pdf->Cell(10, 5, $tipoContrato['nome']);

	$pdf->ln(6);
	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');
	$pdf->ln(6);
 
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Data da Solicitação :');
	$pdf->SetX(42);
	$pdf->Cell(10, 5, converteData($edit['data_solicitacao']) );

	$pdf->SetX(70);
	$pdf->Cell(10, 5, 'Data do início de contrato :');
	$pdf->SetX(118);
	$pdf->Cell(10, 5, converteData($contrato['inicio']) );

	$pdf->ln(6);
	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');
	$pdf->ln(6);
 
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Data de Encerramento :');
	$pdf->SetX(48);
	$pdf->Cell(10, 5, converteData($edit['data_encerramento']) );

	$consultorId=$contrato['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->SetX(90);
	$pdf->Cell(22, 5, 'Consultor :');
	$pdf->SetX(112);
	$pdf->Cell(22, 5, $consultor['nome']);

	$pdf->ln(6);
	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(05, 5, 'Tipo de Contato :');

	$pdf->SetX(40);
	if ($edit['tipo_contato']==1){
		$pdf->Cell(10, 5, 'Telefone');
	}
	if ($edit['tipo_contato']==2){
		$pdf->Cell(10, 5, ' Visita');
	}
	if ($edit['tipo_contato']==3){
		$pdf->Cell(10, 5, ' E-mail');
	}


	$pdf->ln(6);

	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(05, 5, 'Período Rescisório : ');

	$pdf->SetX(40);
	if ($edit['periodo_rescisorio']==1){
		$pdf->Cell(10, 5, 'Imediato -somente coletado');
	}
	if ($edit['periodo_rescisorio']==2){
		$pdf->Cell(10, 5, 'Imediato - coletado + vl contra');
	}

	$pdf->SetX(110);
	$pdf->Cell(05, 5, 'Período : ');

	$pdf->SetX(130);
	if ($edit['periodo_rescisorio_tempo']==1){
		$pdf->Cell(10, 5, '30');
	}
	if ($edit['periodo_rescisorio_tempo']==2){
		$pdf->Cell(10, 5, '60');
	}
	if ($edit['periodo_rescisorio_tempo']==3){
		$pdf->Cell(10, 5, '90');
	}


	$pdf->ln(6);

	// LINHA
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(05,5,'(  ) 30 dias (  ) 60 dias (  ) 90 dias (  ) Com coletas (  ) Sem coletas');

	$pdf->ln(6);
	
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');
	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Cont. em comodato :');

	$pdf->SetX(45);
	if ($edit['comodato_equipamento']==1){
		$pdf->Cell(10, 5, 'Container 1.2m³');
	}
	if ($edit['comodato_equipamento']==2){
		$pdf->Cell(10, 5, 'Container 1.0m³ ');
	}
	if ($edit['comodato_equipamento']==3){
		$pdf->Cell(10, 5, 'Container 240L ');
	}
	 
	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Quantidade:');
	$pdf->SetX(28);
	$pdf->Cell(22, 5, $edit['comodato_quantidade']);

	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'Data da Retirada:');
	$pdf->SetX(112);
	$pdf->Cell(10, 5, converteData($edit['comodato_retirada']) );
 
	$pdf->ln(6);

	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);

	$motivoId=$edit['motivo'];
	$motivo = mostra('contrato_cancelamento_motivo',"WHERE id ='$motivoId'");
	$pdf->SetX(05);
	$pdf->Cell(22, 1, 'Motivo :');
	$pdf->SetX(25);
	$pdf->Cell(22, 1, $motivo['nome']);

	$pdf->ln(6);
	
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);


	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Alguma vez foi feita alguma reclamação sobre o problema via telefone, e-mail ou diretamente com o');
	$pdf->ln(5);
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'representante comercial ? ');
	
	$pdf->SetX(50);
	if ($edit['reclamacao']==1){
		$pdf->Cell(10, 5, 'SIM ');
	}
	if ($edit['reclamacao']==2){
		$pdf->Cell(10, 5, 'NAO ');
	}

	$pdf->ln(6);

	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(22, 5, 'Descrição da Solicitação:');
	$pdf->ln(5);
	$pdf->SetX(05);
	$pdf->MultiCell(190, 5, $edit['reclamacao_descricao']) ;


	$pdf->ln(6);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Recuperada:');
	$pdf->SetX(28);
	if ($edit['recuperada']==1){
		$pdf->SetX(35);
		$pdf->Cell(10, 5, 'SIM ');
	}elseif ($edit['recuperada']==2){
		$pdf->SetX(35);
		$pdf->Cell(10, 5, 'NAO ');
	}
 
	$pdf->ln(6);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	$pdf->ln(6);
	$pdf->SetX(05);
	$pdf->Cell(22, 5, 'Observação Comercial');
	$pdf->ln(5);
	$pdf->SetX(05);
	$pdf->MultiCell(190, 5, $edit['observacao_comercial']) ;

	//fim do extrato

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>