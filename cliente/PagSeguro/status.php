<?php

    require_once "PagSeguroLibrary/PagSeguroLibrary.php";
    
    $credenciais = PagSeguroConfig::getAccountCredentials();

    $transaction_id = $_GET['transaction_id'];

    $transaction = PagSeguroTransactionSearchService::searchByCode( $credenciais, $transaction_id );

    switch($transaction->getStatus()->getValue()){
        case 1 :
            echo "Aguardando pagamento";
            break;
        case 2 :
            echo "Em análise";
            break;
        case 3 :
            echo "Paga";
            break;
        case 4 :
            echo "Disponível";
            break;
        case 5 :
            echo "Em disputa";
            break;
        case 6 :
            echo "Devolvida";
            break;
        case 7 :
            echo "Cancelada";
            break;
    }