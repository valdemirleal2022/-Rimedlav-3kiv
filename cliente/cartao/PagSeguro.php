<?php
	
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1",true) ;
	require_once "../../PagSeguro/PagSeguroLibrary/PagSeguroLibrary.php";
	/** INICIO PROCESSO PAGSEGURO */
	$paymentRequest = new PagSeguroPaymentRequest();
 
	$paymentRequest->addItem('0001', $_SESSION['descricao'], 1, $_SESSION['valor']); 
	
	//Definindo moeda
	$paymentRequest->setCurrency('BRL');
	
	$paymentRequest->setSender(  
    $_SESSION['nome'],   
    $_SESSION['email'],   
    '11',   
    '56273440'  
	);  
	
	$paymentRequest->setShippingAddress(  
    '20260132',   
    'Av. Brig. Faria Lima',       
    '1384',       
    'apto. 114',       
    'Jardim Paulistano',      
    'São Paulo',      
    'SP',     
    'BRA'     
	);  
 
	
	$paymentRequest->setShipping(3);
	$paymentRequest->setReference("I9635");  
	 
	$credentials = PagSeguroConfig::getAccountCredentials();//credenciais do vendedor
	 
	//$compra_id = App_Lib_Compras::insert($produto);
	//$paymentrequest->setReference($compra_id);//Referencia;
	 
	$url = $paymentRequest->register($credentials);
	 
	header("Location: $url");
?>