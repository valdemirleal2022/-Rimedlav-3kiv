<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}

		
		$leitura = read('cadastro_visita',"WHERE AND status='3' ORDER BY orc_data DESC");
		if(!$leitura){
			 header('Location: painel.php?execute=suporte/error');
		}
  
		foreach($leitura as $visita):
		
			$orcamentoId = $visita['id'];
			$consultorId = $visita['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
			

			// email	
		 $link = "https://www.cleaambiental.com.br/wpc/cliente/painel2.php?execute=suporte/imprimir-proposta&orcamentoId=" . $orcamentoId;

		$msg .= "Proposta Comercial<br /><br />";
	    $msg .= "Para imprimir a proposta comercial completa clique no link IMPRIMIR PROPOSTA abaixo.<br/><br />";
	    $msg .= "Nao quer mais receber este EMAIL ou desistiu do ORÇAMENTO ! clique no link abaixo CANCELAR ORÇAMENTO. <br /><br />";

		$msg .= "Proposta N&deg; : " . $edit['id'] . "<br />";
		$msg .= "Nome : " . $edit['nome'] . "<br />";
		$msg .= "Email : " . $edit['email'] . "<br />";
		$msg .= "Telefone : " . $edit['telefone'] . "<br />";
		
		$msg .= "Data do Orçamento : " . converteData($edit['orc_data']) . "<br />";
		
		$msg .= "Tipo de Resíduo : " . $edit['orc_residuo'] . "<br />";
		$msg .= "Freqüencia da Coleta : " . $edit['orc_frequencia'] . "<br />";
		$msg .= "Dia da Semana : " . $edit['orc_dia'] . "<br />";
		$msg .= "Tipo de Equipamento : " . $edit['orc_equipamento'] . "<br />";
		
		$msg .= "Valor Unitário : " . converteValor($edit['orc_valor_unitario']). "<br />";
		$msg .= "Valor Extra Unitário : " .  converteValor($edit['orc_valor_extra']) . "<br />";
		$msg .= "Valor Mensal R$ : " .  converteValor($edit['orc_valor']) . "<br />";
		
		$msg .= "Forma de Pagamento : " . $edit['orc_forma_pag'] . "<br />";
		$msg .= "Equipamento por Comodato :" . $edit['orc_comodato'] . "<br />";
		
		$msg .= "Observaçao da Coleta : " . nl2br($edit['orc_observacao']) . "<br /><br />";

		$msg .= SITENOME . "<br />";
		$msg .= "Consultor : " . $consultor['nome'] . "<br />";
		$msg .= "Email : " . $consultor['email'] . "<br />";
		$msg .= "Telefone : " . $consultor['telefone'] . "<br />";
		$msg .= "Data : " . date('d/m/Y'). "<br /><br />";

		$msg .= "<a href=" . $link . ">IMPRIMIR PROPOSTA</a> <br /><br />";

		$msg .= "Pense no ambiente antes de imprimir esta página. A natureza agradece!<br />";	
		$msg .=  "</font>";

		$cad['interacao']= date('Y/m/d H:i:s');
		update('cadastro_visita',$cad,"id = '$orcamentoId'");	

 		enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);

	 endforeach;
	 
	 header("Location: ".$_SESSION['url']);
?>

