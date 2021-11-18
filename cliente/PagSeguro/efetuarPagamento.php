<?php

	session_start();
	
	// atualizado em 15/09/2014 - usar _dados.php para teste
	
	if(isset($_GET['boletoId'])){
		
        $boletoId = $_GET['boletoId'];
		$boleto = mostra('receber',"WHERE id = '$boletoId'");
	 		
		// Recupera boleto 
		$id_boleto=$boleto['id'];
		$nome_banco=$boleto['banco'];
		$emissao=$boleto['emissao'];
		$id_cliente=$boleto['id_cliente'];
		$vencimento = $boleto['vencimento'];
		$emissao = implode("/",array_reverse(explode("-",$emissao)));
		$valor_normal = $boleto["valor"];
		$valor =$boleto["valor"];
		
		$banco = mostra('banco',"WHERE nome = '$nome_banco'");
	 	
		// boleto vencido - calculando juros	
		$valor_juros_multa=0;
		$percentual_juros=$banco["juros"];
		$percentual_multa=$banco["multa"];
		
		if (($vencimento < date('Y-m-d'))) {
			$hoje=date('Y-m-d');
			$time_inicial = strtotime($vencimento);
			$time_final = strtotime($hoje);
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			
			$juros_dia = ($valor*$percentual_juros)/100;
			$juros =  (($juros_dia * $dias));
			$multa = (($percentual_multa * $valor) / 100); 
			$valor = $valor+ ($juros + $multa);
			$valor_juros_multa=$valor;
			
			// 1 dia para pagar
			$vencimento=date("Y-m-d");
			$vencimento = date("Y-m-d", strtotime("$vencimento + 1 DAYS"));
		  }
	  	  $clienteId = $boleto['id_cliente'];
	  	  $cliente = mostra('cliente',"WHERE id = '$clienteId'");
	 
	 
	 
	 		// recupera dados para o cartao
		
	 		$senderNome =  $cliente["nome"];
			$senderEmail =  $cliente["email"];
			$senderDdd =  limpaNumero($cliente["ddd"]);
			$senderFone =  substr(limpaNumero($cliente["telefone"])-8);
 			$senderCpf = "11111111111";

			$codigoCompra = $boleto["id"];
			
			$urlNotificacao = "http://www.wpcsistema.com.br/cliente/PagSeguro/notificacao.php?compra=".$codigoCompra;
			$urlFim = "http://www.wpcsistema.com.br/cliente/PagSeguro/fimcompra.php?cod=".$codigoCompra;
			
			
			
			$dadosEnvio = Array(
							'postalCode' => limpaNumero($cliente["cep"]),
							'street' => $cliente["end_nome"],
							'number' => limpaNumero($cliente["end_num"]),
							'complement' => $cliente["end_comp"],
							'district' => $cliente["bairro"],
							'city' => $cliente["cidade"],
							'state' => $cliente["uf"],
							'country' => 'BRA'
			);

			
		  			
// 		

  
					
			$itens = Array(
					0 => array(
						'id' => $boleto["id"],
						'description' => 'Manutencao do Sistema n. ' .$boleto["id"],
						'quantity' => 1,
						'amount' =>  $boleto["valor"],
						'weight' => 1000
					)
			);
			//
//			$dadosEnvio = Array(
//							'postalCode' => '01452002',
//							'street' => 'Av. Brig. Faria Lima',
//							'number' => '1384',
//							'complement' => 'apto 100',
//							'district' => 'Jardim Paulistano',
//							'city' => 'São Paulo',
//							'state' => 'SP',
//							'country' => 'BRA'
//			);
        
        require_once "PagSeguroLibrary/PagSeguroLibrary.php";
        
        $requisicaoPagamento = new PagSeguroPaymentRequest();
        
        $credenciais = PagSeguroConfig::getAccountCredentials();
        
        $requisicaoPagamento->setItems($itens);
        
        $requisicaoPagamento->setSender($senderNome, $senderEmail, $senderDdd, $senderFone);
        
        $requisicaoPagamento->setShippingAddress($dadosEnvio);
        
        $requisicaoPagamento->setShippingType(3);
        
        $requisicaoPagamento->setCurrency("BRL");
        
        $requisicaoPagamento->setReference($codigoCompra);
        
        $requisicaoPagamento->setRedirectURL($urlFim);
        
        $requisicaoPagamento->addParameter('notificationURL', $urlNotificacao);
        $requisicaoPagamento->addParameter('senderCPF', $senderCpf);
        
        $url = $requisicaoPagamento->register($credenciais);
        
        header("Location: ".$url);
        
	}
?>

 