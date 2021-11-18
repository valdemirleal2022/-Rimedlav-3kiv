<?php


	session_start();
	
	if(!empty($_GET['boletoId'])){
		
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
	 }
	 

$senderNome =  $cliente["nome"];
$senderEmail =  $cliente["email"];
$senderDdd =  $cliente["ddd"];
$senderFone =  $cliente["telefone"];
$senderCpf = $cliente["cpf"];

$codigoCompra = $boleto["id"];

$urlNotificacao = "http://www.wpcsistema.com.br/PagSeguro/notificacao.php?compra=".$codigoCompra;
$urlFim = "http://www.wpcsistema.com.br/PagSeguro/fimcompra.php?cod=".$codigoCompra;

$itens = Array(
        0 => array(
            'id' => $boleto["id"],
            'description' => $boleto["observacao"],
            'quantity' => 1,
            'amount' => $boleto["valor"],
            'weight' => 1000
        )
        );

$dadosEnvio = Array(
                'postalCode' => $cliente["cep"],
                'street' => $cliente["endereco"],
                'number' => $cliente["end_num"],
                'complement' => $cliente["end_compl"],
                'district' => $cliente["bairro"],
                'city' => $cliente["cidade"],
                'state' => $cliente["uf"],
                'country' => 'BRA'
);