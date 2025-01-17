<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}

		if(!empty($_GET['contratoVisualizar'])){
			$contratoId = $_GET['contratoVisualizar'];
			$acao = "visualizar";
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}
		if(!empty($_GET['contratoEditar'])){
			$contratoId = $_GET['contratoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['contratoAprovar'])){
			$contratoId = $_GET['contratoAprovar'];
			$acao = "aprovar";
		}
		if(!empty($_GET['contratoAtivar'])){
			$contratoId = $_GET['contratoAtivar'];
			$acao = "ativar";
		}
		if(!empty($_GET['contratoBaixar'])){
			$contratoId = $_GET['contratoBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['contratoDeletar'])){
			$contratoId = $_GET['contratoDeletar'];
			$acao = "deletar";
		}


		if(!empty($_GET['clienteId'])){
			
			$clienteId = $_GET['clienteId'];
			
			$edit['contrato']= date('Y/m/d H:i:s');
            $edit['status'] =5;
			$edit['tipo'] =2;
			$edit['situacao']=1;

			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readCliente as $cliente);
			$acao = "cadastrar";
		}
		
		
		if(!empty($contratoId)){
			
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readContrato as $edit);
			
			if ($edit['domingo'] == "1") {
				$edit['domingo'] = "checked='CHECKED'";
			} else {
				$edit['domingo'] = "";
		    }
			if ($edit['segunda'] == "1") {
				$edit['segunda'] = "checked='CHECKED'";
			} else {
				$edit['segunda'] = "";
		    }
			if ($edit['terca'] == "1") {
				$edit['terca'] = "checked='CHECKED'";
			} else {
				$edit['terca'] = "";
		    }
			if ($edit['quarta'] == "1") {
				$edit['quarta'] = "checked='CHECKED'";
			} else {
				$edit['quarta'] = "";
		    }
			if ($edit['quinta'] == "1") {
				$edit['quinta'] = "checked='CHECKED'";
			} else {
				$edit['quinta'] = "";
		    }
			if ($edit['sexta'] == "1") {
				$edit['sexta'] = "checked='CHECKED'";
			} else {
				$edit['sexta'] = "";
		    }
			if ($edit['sabado'] == "1") {
				$edit['sabado'] = "checked='CHECKED'";
			} else {
				$edit['sabado'] = "";
		    }
			
			if ($edit['nao_construir_faturamento'] == "1") {
				$edit['nao_construir_faturamento'] = "checked='CHECKED'";
			} else {
				$edit['nao_construir_faturamento'] = "";
		    }
			
			if ($edit['enviar_boleto_correio'] == "1") {
				$edit['enviar_boleto_correio'] = "checked='CHECKED'";
			} else {
				$edit['enviar_boleto_correio'] = "";
		    }
			
			if ($edit['nao_enviar_extrato'] == "1") {
				$edit['nao_enviar_extrato'] = "checked='CHECKED'";
			} else {
				$edit['nao_enviar_extrato'] = "";
		    }

			$dataInicio = $edit['inicio'];
			
			$clienteId = $edit['id_cliente'];
			
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				//header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readCliente as $cliente);
			
			$coleta = mostra('contrato_coleta',"WHERE id AND id_contrato='$contratoId' ORDER BY vencimento ASC");
			$edit['valor_mensal'] = $coleta['valor_mensal']; 
			
		}
 
		if($acao == "aprovar"){
			$edit['tipo'] =2;
			$edit['status']=4;
			$edit['aprovacao']= date('Y-m-d',strtotime(date( "Y/m/d")));
		}

		if($acao == "ativar"){
			$edit['tipo'] =2;
			$edit['status']=4;
			$edit['aprovacao']= date('Y-m-d',strtotime(date( "Y/m/d")));
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}

		$_SESSION['contrato']=$contratoId;
		$_SESSION['dataInicio']=$dataInicio;

		$_SESSION['contrato-editar']=$_SERVER['REQUEST_URI'];

		$_SESSION['url']=$_SERVER['REQUEST_URI'];

		if (isset($_POST['checkBoxOrdemTotal'])) {
			$_SESSION['ordemTotal']='1';
			$ordemTotal = "checked='CHECKED'";
			$_SESSION['aba']='3';
		}else{
			$_SESSION['ordemTotal']='0';
			$ordemTotal = "";
		}


		if (isset($_POST['checkBoxInteracaoTotal'])) {
			$_SESSION['interacaoTotal']='1';
			$interacaoTotal = "checked='CHECKED'";
			$_SESSION['aba']='11';
		}else{
			$_SESSION['interacaoTotal']='0';
			$interacaoTotal = "";
		}


		// APROVADO
		// $edit['status']=4; 

		// ATIVO
		// $edit['status'] =5;] 

		// SUSPENSO
		// $edit['status'] =6;] 

		// PROTESTADO
		// $edit['status'] =7;]

		// CANCELADO
		// $edit['status'] =9;]
	
?>

<div class="content-wrapper">
     <section class="content-header">
         <h1>Contrato</h1>
         <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=suporte/cliente/clientes">Contrato</a></li>
             <li class="active">Editar</li>
          </ol>
      </section>
      
	 <section class="content">
         <div class="box box-default">
           <div class="box-header with-border">
            	 <?php require_once('cliente-modal.php');?>
          
               	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->

	<div class="box-body table-responsive">
	
	 <?php
	
		if(isset($_POST['voltar'])){
			header("Location: ".$_SESSION['url']);
		}
		
		if(isset($_POST['followup'])){
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['tipo']= 1;
			$cad['status']= 3;
			update('contrato',$cad,"id = '$contratoId'");	
			header("Location: ".$_SESSION['url']);
		}

		if(isset($_POST['deletar'])){
				$readDeleta = read('contrato',"WHERE id = '$contratoId'");
				if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro n�o existe</div>';
				}else{
					delete('contrato',"id = '$contratoId'");
					header("Location: ".$_SESSION['url']);
				}
		}

		
		if(isset($_POST['aprovar'])){
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['tipo'] =2;
			$cad['status']=4;
			$cad['aprovacao'] = mysql_real_escape_string($_POST['aprovacao']);
			$cad['inicio'] = mysql_real_escape_string($_POST['inicio']);
			$cad['consultor'] = mysql_real_escape_string($_POST['consultor']);
			$cad['indicacao'] = mysql_real_escape_string($_POST['indicacao']);
			update('contrato',$cad,"id = '$contratoId'");	
			
			$interacao='Contrato aprovado';
			interacao($interacao,$contratoId);	
			
			$cli['status'] = '1';
			update('cliente',$cli,"id = '$clienteId'");	
			header('Location: painel.php?execute=suporte/contrato/contrato-aprovados');	
		}
		
		
		if(isset($_POST['ativar'])){
		 
		   $cad['tipo'] =2;
			$cad['status']=5;
		 
			update('contrato',$cad,"id = '$contratoId'");	
			
			$interacao='Contrato Ativado';
			interacao($interacao,$contratoId);	
			
			header('Location: painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$contratoId);
		}
		
		if(isset($_POST['atualizar'])){

		$cad['contrato_tipo']= strip_tags(trim(mysql_real_escape_string($_POST['contrato_tipo'])));
			$cad['controle']= strip_tags(trim(mysql_real_escape_string($_POST['controle'])));
			$cad['atendente']= strip_tags(trim(mysql_real_escape_string($_POST['atendente'])));
			$cad['consultor']= strip_tags(trim(mysql_real_escape_string($_POST['consultor'])));
			$cad['pos_venda']= strip_tags(trim(mysql_real_escape_string($_POST['pos_venda'])));
			$cad['indicacao']  = mysql_real_escape_string($_POST['indicacao']);
			$cad['aprovacao']= strip_tags(trim(mysql_real_escape_string($_POST['aprovacao'])));
			$cad['inicio'] = mysql_real_escape_string($_POST['inicio']);
		$cad['valor_mensal']= strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal'])));
			$cad['valor_mensal'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal']));

			$cad['frequencia'] = mysql_real_escape_string($_POST['frequencia']);
			$cad['quinzenal'] = mysql_real_escape_string($_POST['quinzenal']);
			
			$cad['domingo'] = mysql_real_escape_string($_POST['domingo']);
			$cad['domingo_quantidade'] = mysql_real_escape_string($_POST['domingo_quantidade']);
			$cad['domingo_hora1'] = mysql_real_escape_string($_POST['domingo_hora1']);
			$cad['domingo_rota1'] = mysql_real_escape_string($_POST['domingo_rota1']);
			$cad['domingo_hora2'] = mysql_real_escape_string($_POST['domingo_hora2']);
			$cad['domingo_rota2'] = mysql_real_escape_string($_POST['domingo_rota2']);
			$cad['domingo_hora3'] = mysql_real_escape_string($_POST['domingo_hora3']);
			$cad['domingo_rota3'] = mysql_real_escape_string($_POST['domingo_rota3']);
			
			$cad['segunda'] = mysql_real_escape_string($_POST['segunda']);
			$cad['segunda_quantidade'] = mysql_real_escape_string($_POST['segunda_quantidade']);
			$cad['segunda_hora1'] = mysql_real_escape_string($_POST['segunda_hora1']);
			$cad['segunda_rota1'] = mysql_real_escape_string($_POST['segunda_rota1']);
			$cad['segunda_hora2'] = mysql_real_escape_string($_POST['segunda_hora2']);
			$cad['segunda_rota2'] = mysql_real_escape_string($_POST['segunda_rota2']);
			$cad['segunda_hora3'] = mysql_real_escape_string($_POST['segunda_hora3']);
			$cad['segunda_rota3'] = mysql_real_escape_string($_POST['segunda_rota3']);
			
			$cad['terca'] = mysql_real_escape_string($_POST['terca']);
			$cad['terca_quantidade'] = mysql_real_escape_string($_POST['terca_quantidade']);
			$cad['terca_hora1'] = mysql_real_escape_string($_POST['terca_hora1']);
			$cad['terca_rota1'] = mysql_real_escape_string($_POST['terca_rota1']);
			$cad['terca_hora2'] = mysql_real_escape_string($_POST['terca_hora2']);
			$cad['terca_rota2'] = mysql_real_escape_string($_POST['terca_rota2']);
			$cad['terca_hora3'] = mysql_real_escape_string($_POST['terca_hora3']);
			$cad['terca_rota3'] = mysql_real_escape_string($_POST['terca_rota3']);
			
			$cad['quarta'] = mysql_real_escape_string($_POST['quarta']);
			$cad['quarta_quantidade'] = mysql_real_escape_string($_POST['quarta_quantidade']);
			$cad['quarta_hora1'] = mysql_real_escape_string($_POST['quarta_hora1']);
			$cad['quarta_rota1'] = mysql_real_escape_string($_POST['quarta_rota1']);
			$cad['quarta_hora2'] = mysql_real_escape_string($_POST['quarta_hora2']);
			$cad['quarta_rota2'] = mysql_real_escape_string($_POST['quarta_rota2']);
			$cad['quarta_hora3'] = mysql_real_escape_string($_POST['quarta_hora3']);
			$cad['quarta_rota3'] = mysql_real_escape_string($_POST['quarta_rota3']);
			
			$cad['quinta'] = mysql_real_escape_string($_POST['quinta']);
			$cad['quinta_quantidade'] = mysql_real_escape_string($_POST['quinta_quantidade']);
			$cad['quinta_hora1'] = mysql_real_escape_string($_POST['quinta_hora1']);
			$cad['quinta_rota1'] = mysql_real_escape_string($_POST['quinta_rota1']);
			$cad['quinta_hora2'] = mysql_real_escape_string($_POST['quinta_hora2']);
			$cad['quinta_rota2'] = mysql_real_escape_string($_POST['quinta_rota2']);
			$cad['quinta_hora3'] = mysql_real_escape_string($_POST['quinta_hora3']);
			$cad['quinta_rota3'] = mysql_real_escape_string($_POST['quinta_rota3']);
			
			$cad['sexta'] = mysql_real_escape_string($_POST['sexta']);
			$cad['sexta_quantidade'] = mysql_real_escape_string($_POST['sexta_quantidade']);
			$cad['sexta_hora1'] = mysql_real_escape_string($_POST['sexta_hora1']);
			$cad['sexta_rota1'] = mysql_real_escape_string($_POST['sexta_rota1']);
			$cad['sexta_hora2'] = mysql_real_escape_string($_POST['sexta_hora2']);
			$cad['sexta_rota2'] = mysql_real_escape_string($_POST['sexta_rota2']);
			$cad['sexta_hora3'] = mysql_real_escape_string($_POST['sexta_hora3']);
			$cad['sexta_rota3'] = mysql_real_escape_string($_POST['sexta_rota3']);
			
			$cad['sabado'] = mysql_real_escape_string($_POST['sabado']);
			$cad['sabado_quantidade'] = mysql_real_escape_string($_POST['sabado_quantidade']);
			$cad['sabado_hora1'] = mysql_real_escape_string($_POST['sabado_hora1']);
			$cad['sabado_rota1'] = mysql_real_escape_string($_POST['sabado_rota1']);
			$cad['sabado_hora2'] = mysql_real_escape_string($_POST['sabado_hora2']);
			$cad['sabado_rota2'] = mysql_real_escape_string($_POST['sabado_rota2']);
			$cad['sabado_hora3'] = mysql_real_escape_string($_POST['sabado_hora3']);
			$cad['sabado_rota3'] = mysql_real_escape_string($_POST['sabado_rota3']);
					
			$cad['coletar_feriado'] = mysql_real_escape_string($_POST['coletar_feriado']);
			$cad['hora_limite'] = mysql_real_escape_string($_POST['hora_limite']);
			
			$cad['cobranca_coleta'] = mysql_real_escape_string($_POST['cobranca_coleta']);
			$cad['dia_fechamento'] = mysql_real_escape_string($_POST['dia_fechamento']);
			$cad['dia_vencimento'] = mysql_real_escape_string($_POST['dia_vencimento']);
			$cad['nao_construir_faturamento'] = mysql_real_escape_string($_POST['nao_construir_faturamento']);
				
			$cad['boleto_bancario'] = mysql_real_escape_string($_POST['boleto_bancario']);
			$cad['boleto_valor']		= strip_tags(trim(mysql_real_escape_string($_POST['boleto_valor'])));
			$cad['boleto_valor'] = str_replace(",",".",str_replace(".","",$cad['boleto_valor']));
			
			$cad['nota_fiscal'] = mysql_real_escape_string($_POST['nota_fiscal']);
			$cad['iss_valor'] = mysql_real_escape_string($_POST['iss_valor']);
			$cad['iss_valor'] = str_replace(",",".",str_replace(".","",$cad['iss_valor']));
			$cad['iss_retido'] = mysql_real_escape_string($_POST['iss_retido']);
			$cad['observacao_nota'] = mysql_real_escape_string($_POST['observacao_nota']);
			$cad['nota_no_faturamento'] = mysql_real_escape_string($_POST['nota_no_faturamento']);

			
			$cad['saldo_etiqueta_minimo'] = mysql_real_escape_string($_POST['saldo_etiqueta_minimo']);
		//	$cad['saldo_etiqueta'] = mysql_real_escape_string($_POST['saldo_etiqueta']);
			
			if($cad['saldo_etiqueta_minimo'] ==0){
				$cad['saldo_etiqueta'] = 0;
			}
			$cad['enviar_boleto_correio'] = mysql_real_escape_string($_POST['enviar_boleto_correio']);
			$cad['nao_enviar_extrato'] = mysql_real_escape_string($_POST['nao_enviar_extrato']);

			$cad['comissao_fechamento'] = mysql_real_escape_string($_POST['comissao_fechamento']);
			$cad['comissao_fechamento'] = str_replace(",",".",str_replace(".","",$cad['comissao_fechamento']));
			$cad['comissao_manutencao'] = mysql_real_escape_string($_POST['comissao_manutencao']);
			$cad['comissao_manutencao'] = str_replace(",",".",str_replace(".","",$cad['comissao_manutencao']));
			
			$cad['cobrar_locacao'] = mysql_real_escape_string($_POST['cobrar_locacao']);
					
			$cad['manifesto'] = mysql_real_escape_string($_POST['manifesto']);
			$cad['manifesto_valor']	= strip_tags(trim(mysql_real_escape_string($_POST['manifesto_valor'])));
			$cad['manifesto_valor'] = str_replace(",",".",str_replace(".","",$cad['manifesto_valor']));
			
			update('contrato',$cad,"id = '$contratoId'");	
			
			$interacao='Contrato atualizado';
			interacao($interacao,$contratoId);	
			
			header('Location: painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$contratoId);
		}
		
		
		if(isset($_POST['cadastrar'])){

			$cad['contrato_tipo']= strip_tags(trim(mysql_real_escape_string($_POST['contrato_tipo'])));
			$cad['controle']= strip_tags(trim(mysql_real_escape_string($_POST['controle'])));

			$cad['atendente']= strip_tags(trim(mysql_real_escape_string($_POST['atendente'])));
			$cad['consultor']= strip_tags(trim(mysql_real_escape_string($_POST['consultor'])));
			$cad['pos_venda']= strip_tags(trim(mysql_real_escape_string($_POST['pos_venda'])));
			$cad['indicacao']  = mysql_real_escape_string($_POST['indicacao']);
			$cad['aprovacao']= strip_tags(trim(mysql_real_escape_string($_POST['aprovacao'])));
			$cad['inicio'] = mysql_real_escape_string($_POST['inicio']);
			$cad['valor_mensal'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal'])));
			$cad['valor_mensal'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal']));

			$cad['frequencia'] = mysql_real_escape_string($_POST['frequencia']);
			$cad['quinzenal'] = mysql_real_escape_string($_POST['quinzenal']);
			
			$cad['domingo'] = mysql_real_escape_string($_POST['domingo']);
			$cad['domingo_quantidade'] = mysql_real_escape_string($_POST['domingo_quantidade']);
			$cad['domingo_hora1'] = mysql_real_escape_string($_POST['domingo_hora1']);
			$cad['domingo_rota1'] = mysql_real_escape_string($_POST['domingo_rota1']);
			$cad['domingo_hora2'] = mysql_real_escape_string($_POST['domingo_hora2']);
			$cad['domingo_rota2'] = mysql_real_escape_string($_POST['domingo_rota2']);
			$cad['domingo_hora3'] = mysql_real_escape_string($_POST['domingo_hora3']);
			$cad['domingo_rota3'] = mysql_real_escape_string($_POST['domingo_rota3']);
			$cad['segunda'] = mysql_real_escape_string($_POST['segunda']);
			$cad['segunda_quantidade'] = mysql_real_escape_string($_POST['segunda_quantidade']);
			$cad['segunda_hora1'] = mysql_real_escape_string($_POST['segunda_hora1']);
			$cad['segunda_rota1'] = mysql_real_escape_string($_POST['segunda_rota1']);
			$cad['segunda_hora2'] = mysql_real_escape_string($_POST['segunda_hora2']);
			$cad['segunda_rota2'] = mysql_real_escape_string($_POST['segunda_rota2']);
			$cad['segunda_hora3'] = mysql_real_escape_string($_POST['segunda_hora3']);
			$cad['segunda_rota3'] = mysql_real_escape_string($_POST['segunda_rota3']);
			$cad['terca'] = mysql_real_escape_string($_POST['terca']);
			$cad['terca_quantidade'] = mysql_real_escape_string($_POST['terca_quantidade']);
			$cad['terca_hora1'] = mysql_real_escape_string($_POST['terca_hora1']);
			$cad['terca_rota1'] = mysql_real_escape_string($_POST['terca_rota1']);
			$cad['terca_hora2'] = mysql_real_escape_string($_POST['terca_hora2']);
			$cad['terca_rota2'] = mysql_real_escape_string($_POST['terca_rota2']);
			$cad['quarta'] = mysql_real_escape_string($_POST['quarta']);
			$cad['quarta_quantidade'] = mysql_real_escape_string($_POST['quarta_quantidade']);
			$cad['quarta_hora1'] = mysql_real_escape_string($_POST['quarta_hora1']);
			$cad['quarta_rota1'] = mysql_real_escape_string($_POST['quarta_rota1']);
			$cad['quarta_hora2'] = mysql_real_escape_string($_POST['quarta_hora2']);
			$cad['quarta_rota2'] = mysql_real_escape_string($_POST['quarta_rota2']);
			$cad['quarta_hora3'] = mysql_real_escape_string($_POST['quarta_hora3']);
			$cad['quarta_rota3'] = mysql_real_escape_string($_POST['quarta_rota3']);
			$cad['quinta'] = mysql_real_escape_string($_POST['quinta']);
			$cad['quinta_quantidade'] = mysql_real_escape_string($_POST['quinta_quantidade']);
			$cad['quinta_hora1'] = mysql_real_escape_string($_POST['quinta_hora1']);
			$cad['quinta_rota1'] = mysql_real_escape_string($_POST['quinta_rota1']);
			$cad['quinta_hora2'] = mysql_real_escape_string($_POST['quinta_hora2']);
			$cad['quinta_rota2'] = mysql_real_escape_string($_POST['quinta_rota2']);
			$cad['sexta'] = mysql_real_escape_string($_POST['sexta']);
			$cad['sexta_quantidade'] = mysql_real_escape_string($_POST['sexta_quantidade']);
			$cad['sexta_hora1'] = mysql_real_escape_string($_POST['sexta_hora1']);
			$cad['sexta_rota1'] = mysql_real_escape_string($_POST['sexta_rota1']);
			$cad['sexta_hora2'] = mysql_real_escape_string($_POST['sexta_hora2']);
			$cad['sexta_rota2'] = mysql_real_escape_string($_POST['sexta_rota2']);
			$cad['sexta_hora3'] = mysql_real_escape_string($_POST['sexta_hora3']);
			$cad['sexta_rota3'] = mysql_real_escape_string($_POST['sexta_rota3']);
			$cad['sabado'] = mysql_real_escape_string($_POST['sabado']);
			$cad['sabado_quantidade'] = mysql_real_escape_string($_POST['sabado_quantidade']);
			$cad['sabado_hora1'] = mysql_real_escape_string($_POST['sabado_hora1']);
			$cad['sabado_rota1'] = mysql_real_escape_string($_POST['sabado_rota1']);
			$cad['sabado_hora2'] = mysql_real_escape_string($_POST['sabado_hora2']);
			$cad['sabado_rota2'] = mysql_real_escape_string($_POST['sabado_rota2']);
			$cad['sabado_hora3'] = mysql_real_escape_string($_POST['sabado_hora3']);
			$cad['sabado_rota3'] = mysql_real_escape_string($_POST['sabado_rota3']);
					
			$cad['coletar_feriado'] = mysql_real_escape_string($_POST['coletar_feriado']);
			$cad['hora_limite'] = mysql_real_escape_string($_POST['hora_limite']);
			
			$cad['cobranca_coleta'] = mysql_real_escape_string($_POST['cobranca_coleta']);
			$cad['dia_fechamento'] = mysql_real_escape_string($_POST['dia_fechamento']);
			$cad['dia_vencimento'] = mysql_real_escape_string($_POST['dia_vencimento']);
			$cad['nao_construir_faturamento'] = mysql_real_escape_string($_POST['nao_construir_faturamento']);
			
			$cad['boleto_bancario'] = mysql_real_escape_string($_POST['boleto_bancario']);
			$cad['boleto_valor'] = strip_tags(trim(mysql_real_escape_string($_POST['boleto_valor'])));
			$cad['boleto_valor'] = str_replace(",",".",str_replace(".","",$cad['boleto_valor']));
			
			$cad['nota_fiscal'] = mysql_real_escape_string($_POST['nota_fiscal']);
			$cad['iss_valor'] = mysql_real_escape_string($_POST['iss_valor']);
			$cad['iss_valor'] = str_replace(",",".",str_replace(".","",$cad['iss_valor']));
			$cad['iss_retido'] = mysql_real_escape_string($_POST['iss_retido']);
			$cad['observacao_nota'] = mysql_real_escape_string($_POST['observacao_nota']);
			$cad['nota_no_faturamento'] = mysql_real_escape_string($_POST['nota_no_faturamento']);
			
			$cad['saldo_etiqueta_minimo'] = mysql_real_escape_string($_POST['saldo_etiqueta_minimo']);
			$cad['enviar_boleto_correio'] = mysql_real_escape_string($_POST['enviar_boleto_correio']);
			

			$cad['comissao_fechamento'] = mysql_real_escape_string($_POST['comissao_fechamento']);
			$cad['comissao_fechamento'] = str_replace(",",".",str_replace(".","",$cad['comissao_fechamento']));
			$cad['comissao_manutencao'] = mysql_real_escape_string($_POST['comissao_manutencao']);
			$cad['comissao_manutencao'] = str_replace(",",".",str_replace(".","",$cad['comissao_manutencao']));
			
			$cad['cobrar_locacao'] = mysql_real_escape_string($_POST['cobrar_locacao']);
					
			$cad['manifesto'] = mysql_real_escape_string($_POST['manifesto']);
			$cad['manifesto_valor']	= strip_tags(trim(mysql_real_escape_string($_POST['manifesto_valor'])));
			$cad['manifesto_valor'] = str_replace(",",".",str_replace(".","",$cad['manifesto_valor']));

			$cad['id_cliente']	= $clienteId;
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['tipo'] =2;
			$cad['status']=5;
			
			create('contrato',$cad);	
			
			//$contratoId=mysql_insert_id();
			
			//$cli['status'] = '1';
			//update('cliente',$cli,"id = '$clienteId'");	
			
			$interacao='Contrato cadastrado';
			interacao($interacao,$contratoId);
			
			header('Location: painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$contratoId);
			
			
			//header("Location: ".$_SESSION['url']);
		}
		
		
		if(isset($_POST['pdf-contrato'])){
			// arquivo
			$arquivo = $_FILES['arquivo'];
			// Tamanho m�ximo do arquivo (em Bytes)
			$tamanhoPermitido = 1024 * 1024 * 2; // 2Mb
			//Define o diretorio para onde enviaremos o arquivo
			$diretorio = "../uploads/contratos/";

			// verifica se arquivo foi enviado e sem erros
			if( $arquivo['error'] == UPLOAD_ERR_OK ){

				// pego a extens�o do arquivo
				$extensao = extensao($arquivo['name']);

				// valida a extens�o
				if( in_array( $extensao, array("pdf") ) ){

					// verifica tamanho do arquivo
					if ( $arquivo['size'] > $tamanhoPermitido ){
							echo '<div class="alert alert-info">O arquivo enviado � muito grande!</div>'.'<br>';	
					}else{

						// atribui novo nome ao arquivo
						$novo_nome  = $edit['id'].".".$extensao;
						// faz o upload
						$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);

						if($enviou){
							echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
						}else{
							echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
						}
					}

				}else{
					echo '<div class="alert alert-info">Somente arquivos PDF s�o permitidos</div>'.'<br>';
				}

			}else{
				echo '<div class="alert alert-info">Voc� deve enviar um arquivo!</div>'.'<br>';
			}
		}
		
		
		if(isset($_POST['pdf-assinatura'])){
			// arquivo
			$arquivo = $_FILES['arquivo'];
			// Tamanho m�ximo do arquivo (em Bytes)
			$tamanhoPermitido = 1024 * 1024 * 2; // 2Mb
			//Define o diretorio para onde enviaremos o arquivo
			$diretorio = "../uploads/assinaturas/";

			// verifica se arquivo foi enviado e sem erros
			if( $arquivo['error'] == UPLOAD_ERR_OK ){

				// pego a extens�o do arquivo
				$extensao = extensao($arquivo['name']);

				// valida a extens�o
				if( in_array( $extensao, array("pdf") ) ){

					// verifica tamanho do arquivo
					if ( $arquivo['size'] > $tamanhoPermitido ){
							echo '<div class="alert alert-info">O arquivo enviado � muito grande!</div>'.'<br>';	
					}else{

						// atribui novo nome ao arquivo
						$novo_nome  = $edit['id'].".".$extensao;
						// faz o upload
						$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);

						if($enviou){
							echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
						}else{
							echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
						}
					}

				}else{
					echo '<div class="alert alert-info">Somente arquivos PDF s�o permitidos</div>'.'<br>';
				}

			}else{
				echo '<div class="alert alert-info">Voc� deve enviar um arquivo!</div>'.'<br>';
			}
		}
		
		if(isset($_POST['pdf-cancelamento'])){
			// arquivo
			$arquivo = $_FILES['arquivo'];
			// Tamanho m�ximo do arquivo (em Bytes)
			$tamanhoPermitido = 1024 * 1024 * 2; // 2Mb
			//Define o diretorio para onde enviaremos o arquivo
			$diretorio = "../uploads/cancelamentos/";

			// verifica se arquivo foi enviado e sem erros
			if( $arquivo['error'] == UPLOAD_ERR_OK ){

				// pego a extens�o do arquivo
				$extensao = extensao($arquivo['name']);

				// valida a extens�o
				if( in_array( $extensao, array("pdf") ) ){

					// verifica tamanho do arquivo
					if ( $arquivo['size'] > $tamanhoPermitido ){
							echo '<div class="alert alert-info">O arquivo enviado � muito grande!</div>'.'<br>';	
					}else{

						// atribui novo nome ao arquivo
						$novo_nome  = $edit['id'].".".$extensao;
						// faz o upload
						$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);

						if($enviou){
							echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
						}else{
							echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
						}
					}

				}else{
					echo '<div class="alert alert-info">Somente arquivos PDF s�o permitidos</div>'.'<br>';
				}

			}else{
				echo '<div class="alert alert-info">Voc� deve enviar um arquivo!</div>'.'<br>';
			}
		}


		function extensao($arquivo){
			$arquivo = strtolower($arquivo);
			$explode = explode(".", $arquivo);
			$arquivo = end($explode);
			return ($arquivo);
		}
	?>
	

	<div id="abas">

		<div class="nav-tabs-custom">

			<ul class="nav nav-tabs">
				<li class="<?php echo ($_SESSION['aba']=='1' ? " active " : " " );?>"><a href="#aba-1" data-toggle="tab">Contrato</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='2' ? " active " : " " );?>"><a href="#aba-2" data-toggle="tab">Tipo</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='3' ? " active " : " " );?>"><a href="#aba-3" data-toggle="tab">Ordem</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='4' ? " active " : " " );?>"><a href="#aba-4" data-toggle="tab">Recebimento</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='5' ? " active " : " " );?>"><a href="#aba-5" data-toggle="tab">Qualidade</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='6' ? " active " : " " );?>"><a href="#aba-6" data-toggle="tab">Agenda</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='7' ? " active " : " " );?>"><a href="#aba-7" data-toggle="tab">Atendimento</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='8' ? " active " : " " );?>"><a href="#aba-8" data-toggle="tab">Baixa</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='9' ? " active " : " " );?>"><a href="#aba-9" data-toggle="tab">Equipamento</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='10' ? " active " : " " );?>"><a href="#aba-10" data-toggle="tab">Aditivo</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='11' ? " active " : " " );?>"><a href="#aba-11" data-toggle="tab">Intera��o</a>
					
				<li class="<?php echo ($_SESSION['aba']=='12' ? " active " : " " );?>"><a href="#aba-12" data-toggle="tab">Ouvidoria</a>
				</li>
		 
			</ul>
			
			
			<!-- /.ABAS -->

<!-- /.CONTRATO -->
		<div class="tab-content"> 
				
		<!-- /.ABAS 01 -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='1' ? " active " : " " );?>" id="aba-1">
	
			<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 				
                <div class="box-header with-border">
                
  					<a href="painel.php?execute=suporte/relatorio/ficha-contrato-pdf&contratoId=<?PHP echo 
								$contratoId; ?>"  target="_blank">
						  		<img src="ico/contratos.png" title="Imprimir Cronograma" />
						 		 <small>Ficha do Contrato</small>
						 </a>
					
					
                </div><!-- /.box-header -->
                
                
                 <div class="box-header with-border">
                 
                  <h3 class="box-title">Status do Contrato</h3>
              
                </div><!-- /.box-header -->
                
                <div class="box-body">
                
                <div class="row">
                    
                     <div class="form-group col-xs-12 col-md-2">  
                     <label>Id</label>
                      <input name="id"  class="form-control" type="text" value="<?php echo $edit['id'];?>" disabled/>
                     </div>
                     
                     
                     <div class="form-group col-xs-12 col-md-3">  
                     <label>Controle</label>
                      <input name="controle"  class="form-control" type="text" value="<?php echo $edit['controle'];?>" <?php echo $readonly;?> />
                     </div>
                     
                     
                     <div class="form-group col-xs-12 col-md-3">  
                       <label>Status</label>
                      <select name="status" disabled="disabled" class="form-control" >
                            <option value="">Selecione Status</option>
                                <?php 
                                    $leitura = read('contrato_status',"WHERE id");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['status'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                     </div>
                     
                     <div class="form-group col-xs-12 col-md-4">  
                   		 <label>Intera&ccedil;&atilde;o</label>
                  	 	 <input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control"  disabled /> 
                   </div>
                   
                
        	 </div><!-- /.row -->
           </div><!-- /.box-body -->
               
                 
                <div class="box-header with-border">
                  <h3 class="box-title">Tipo de Contrato</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                
                <div class="row"> 
                    
                     <div class="form-group col-xs-12 col-md-4">
                		<label>Tipo de Contrato </label>
                		<select name="contrato_tipo"  class="form-control" <?php echo $disabled;?> />
                        	<option value="">Selecione o Tipo</option>
								<?php 
                                    $leitura = read('contrato_tipo',"WHERE id");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos atendente no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['contrato_tipo'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                 	 </select>
               		</div> 
                   
               
               		 <div class="form-group col-xs-12 col-md-4">
                		<label>Atendente </label>
                		<select name="atendente"  class="form-control" <?php echo $disabled;?> />
                        	<option value="">Selecione o Atendente</option>
								<?php 
                                    $leitura = read('contrato_atendente',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos atendente no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['atendente'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                 	 </select>
               		</div> 
                   
                   
                     <div class="form-group col-xs-12 col-md-4">
                        <label>Indica&ccedil;ao</label>
                        <select name="indicacao"  class="form-control" <?php echo $disabled;?>/>
                            <option value="">Selecione o Indica&ccedil;ao</option>
                                <?php 
                                    $leitura = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos indicacao no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['indicacao'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                    </div> 
                    
                    
                   <div class="form-group col-xs-12 col-md-4">
						<label>Aprova&ccedil;&atilde;o</label>
						<input type="date" name="aprovacao" value="<?php echo $edit['aprovacao'];?>" class="form-control" <?php echo $readonly;?> >
				  </div> 

				   <div class="form-group col-xs-12 col-md-4"> 
						<label>In&iacute;cio </label>
						<input type="date" name="inicio" value="<?php echo $edit['inicio'];?>"  class="form-control" <?php echo $readonly;?> >
				  </div> 

				    <div class="form-group col-xs-12 col-md-4"> 
						<label>Valor Mensal</label>
						<input type="text" name="valor_mensal" style="text-align:right" value="<?php echo converteValor($edit['valor_mensal']);?>" class="form-control dinheiro"  <?php echo $readonly;?>>
					</div> 
            
                    </div><!-- /.row -->
           </div><!-- /.box-body -->
               
                 
                <div class="box-header with-border">
                  <h3 class="box-title">Consultor/Comiss�o</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                
                <div class="row">        
               
                    <div class="form-group col-xs-12 col-md-4">
                    <label>Consultor </label>
                    <select name="consultor" class="form-control" <?php echo $disabled;?> />
                            <option value="">Selecione o Consultor</option>
                                <?php 
                                    $leitura = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos consultor no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['consultor'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                    </div>
                    
                   <div class="form-group col-xs-12 col-md-2"> 
						<label>Fechamento (%) </label>
						<input type="text" name="comissao_fechamento" value="<?php echo converteValor($edit['comissao_fechamento']);?>"  class="form-control percentual" <?php echo $readonly;?> >
				  </div>
					
					 <div class="form-group col-xs-12 col-md-4">
                    <label> Pos-Venda </label>
                    <select name="pos_venda" class="form-control" <?php echo $disabled;?> />
                            <option value="">Selecione o Pos-Venda</option>
                                <?php 
                                    $leitura = read('contrato_pos_venda',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos pos_venda no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['pos_venda'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                    </div>
                   
                    <div class="form-group col-xs-12 col-md-2"> 
						<label>Manuten&ccedil;&atilde;o (%) </label>
						<input type="text" name="comissao_manutencao" value="<?php echo converteValor($edit['comissao_manutencao']);?>"  class="form-control percentual"  <?php echo $readonly;?>>
				  </div>
        
            
        	 </div><!-- /.row -->
           </div><!-- /.box-body -->
                 
			<div class="box-header with-border">
                  <h3 class="box-title">Cronograma</h3>
                </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
              
				   <div class="form-group col-xs-12 col-md-4">
					<label>Frequ&ecirc;ncia da Coleta</label>
					<select name="frequencia" class="form-control" <?php echo $disabled;?>/>
							<option value="">Selecione o Frequ&ecirc;ncia</option>
								<?php 
									$leitura = read('contrato_frequencia',"WHERE id ORDER BY id ASC");
									if(!$leitura){
										echo '<option value="">N�o temos Frequencia no momento</option>';	
									}else{
										foreach($leitura as $mae):
										   if($edit['frequencia'] == $mae['id']){
												echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
											 }else{
												echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
											}
										endforeach;	
									}
								?> 
					  </select>
				 </div> 
  		  
  		  
  		   <div class="form-group col-xs-12 col-md-3">
					<label>Coleta Quinzenal</label>
					<select name="quinzenal" class="form-control" <?php echo $disabled;?> />
							<option value="">Selecione o Quinzena</option>
								<?php 
									$leitura = read('contrato_quinzenal',"WHERE id ORDER BY id ASC");
									if(!$leitura){
										echo '<option value="">N�o temos Frequencia no momento</option>';	
									}else{
										foreach($leitura as $mae):
										   if($edit['quinzenal'] == $mae['id']){
												echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
											 }else{
												echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
											}
										endforeach;	
									}
								?> 
					  </select>
				 </div> 
   		  
   		       <div class="form-group col-xs-12 col-md-2">
               <label>Hora Limite</label>
                <input type="text" name="hora_limite" value="<?php echo $edit['hora_limite'];?>" class="form-control" <?php echo $readonly;?>>
              </div> 
    		  
    		  <div class="form-group col-xs-3">
                <label>Coletar no Feriado</label>
                <select name="coletar_feriado" class="form-control" <?php echo $readonly;?>>
                  <option value="">Selecione</option>
                  <option <?php if($edit['coletar_feriado'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['coletar_feriado'] == '0') echo' selected="selected"';?> value="0">Nao</option>
                 </select>
            </div><!-- /.row -->
            
               
     	 </div><!-- /.row -->
   
            <div class="row">
      
			   <div class="form-group col-xs-12 col-md-2">
                  <label>Dia da Semana</label>
              </div> 
               <div class="form-group col-xs-12 col-md-1">
                  <label>Quantidade</label>
              </div>
              <div class="form-group col-xs-12 col-md-2">
                  <label>Rota</label>
              </div> 
              <div class="form-group col-xs-12 col-md-1">
                  <label>Hora 1</label>
              </div> 
              <div class="form-group col-xs-12 col-md-2">
                  <label>Rota 2</label>
              </div> 
              <div class="form-group col-xs-12 col-md-1">
                  <label>Hora 2</label>
              </div> 
              <div class="form-group col-xs-12 col-md-2">
                  <label>Rota 3</label>
              </div> 
              <div class="form-group col-xs-12 col-md-1">
                  <label>Hora 3</label>
              </div> 
              
             <!-- DOMINGO-->
               <div class="form-group col-xs-12 col-md-2">
                   <input name="domingo" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['domingo']; ?>  class="minimal"  <?php echo $disabled;?> >
                Domingo
              </div> 
              
              <div class="form-group col-xs-12 col-md-1"> 
                 <input name="domingo_quantidade" type="number" max="3" min="0" value="<?php echo $edit['domingo_quantidade'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
              
                
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="domingo_rota1"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['domingo_rota1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="domingo_hora1" value="<?php echo $edit['domingo_hora1'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="domingo_rota2"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['domingo_rota2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="domingo_hora2" value="<?php echo $edit['domingo_hora2'];?>" class="form-control" <?php echo $readonly;?>>
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="domingo_rota3"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['domingo_rota3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="domingo_hora3" value="<?php echo $edit['domingo_hora3'];?>" class="form-control" <?php echo $readonly;?>>
              </div> 
               
    <!-- SEGUNDA-->
               	<div class="form-group col-xs-12 col-md-2">
                   <input name="segunda" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['segunda']; ?>  class="minimal" <?php echo $disabled;?> >
                Segunda

              </div> 
              
              <div class="form-group col-xs-12 col-md-1"> 
                 <input name="segunda_quantidade" type="number" max="3" min="0" value="<?php echo $edit['segunda_quantidade'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
              
                
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="segunda_rota1"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['segunda_rota1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="segunda_hora1" value="<?php echo $edit['segunda_hora1'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="segunda_rota2"  class="form-control"  <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['segunda_rota2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="segunda_hora2" value="<?php echo $edit['segunda_hora2'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="segunda_rota3"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['segunda_rota3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="segunda_hora3" value="<?php echo $edit['segunda_hora3'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
              
  <!-- TER�A-->
               	<div class="form-group col-xs-12 col-md-2">
                   <input name="terca" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['terca']; ?>  class="minimal" <?php echo $disabled;?> >
               Ter&ccedil;a

              </div> 
              
              <div class="form-group col-xs-12 col-md-1"> 
                <input name="terca_quantidade" type="number" max="3" min="0" value="<?php echo $edit['terca_quantidade'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
              
                
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="terca_rota1"  class="form-control"  <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['terca_rota1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="terca_hora1" value="<?php echo $edit['terca_hora1'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="terca_rota2"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['terca_rota2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="terca_hora2" value="<?php echo $edit['terca_hora2'];?>" class="form-control"  <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="terca_rota3"  class="form-control"  <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['terca_rota3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="terca_hora3" value="<?php echo $edit['terca_hora3'];?>" class="form-control"  <?php echo $readonly;?> >
              </div> 
              
              
 <!-- QUARTA-->
               	<div class="form-group col-xs-12 col-md-2">
                   <input name="quarta" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['quarta']; ?>  class="minimal" <?php echo $disabled;?> >
               Quarta

              </div> 
              
              <div class="form-group col-xs-12 col-md-1"> 
                <input name="quarta_quantidade" type="number" max="3" min="0" value="<?php echo $edit['quarta_quantidade'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
              
                
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="quarta_rota1"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['quarta_rota1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="quarta_hora1" value="<?php echo $edit['quarta_hora1'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="quarta_rota2"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['quarta_rota2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="quarta_hora2" value="<?php echo $edit['quarta_hora2'];?>" class="form-control" <?php echo $readonly;?>  >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="quarta_rota3"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['quarta_rota3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="quarta_hora3" value="<?php echo $edit['quarta_hora3'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
              
              
 <!-- quinta-->
               	<div class="form-group col-xs-12 col-md-2">
                   <input name="quinta" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['quinta']; ?>  class="minimal" <?php echo $disabled;?> >
              Quinta

              </div> 
              
              <div class="form-group col-xs-12 col-md-1"> 
                <input name="quinta_quantidade" type="number" max="3" min="0" value="<?php echo $edit['quinta_quantidade'];?>" class="form-control"  <?php echo $readonly;?> />
    		  </div>
              
                
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="quinta_rota1"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['quinta_rota1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="quinta_hora1" value="<?php echo $edit['quinta_hora1'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="quinta_rota2"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['quinta_rota2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="quinta_hora2" value="<?php echo $edit['quinta_hora2'];?>" class="form-control"  <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="quinta_rota3"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['quinta_rota3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="quinta_hora3" value="<?php echo $edit['quinta_hora3'];?>" class="form-control"  <?php echo $readonly;?> >
              </div> 
              
      
 <!-- sexta-->
              <div class="form-group col-xs-12 col-md-2">
                   <input name="sexta" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['sexta']; ?>  class="minimal"  <?php echo $disabled;?> >
              Sexta
              </div> 
              
              <div class="form-group col-xs-12 col-md-1"> 
                <input name="sexta_quantidade" type="number" max="3" min="0" value="<?php echo $edit['sexta_quantidade'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
              
                
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="sexta_rota1"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['sexta_rota1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="sexta_hora1" value="<?php echo $edit['sexta_hora1'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="sexta_rota2"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['sexta_rota2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="sexta_hora2" value="<?php echo $edit['sexta_hora2'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="sexta_rota3"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['sexta_rota3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="sexta_hora3" value="<?php echo $edit['sexta_hora3'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
              
              
 <!-- sabado-->
              <div class="form-group col-xs-12 col-md-2">
                   <input name="sabado" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['sabado']; ?>  class="minimal" <?php echo $disabled;?> >
            	 S&aacute;bado
              </div> 
              
              <div class="form-group col-xs-12 col-md-1"> 
                <input name="sabado_quantidade" type="number" max="3" min="0" value="<?php echo $edit['sabado_quantidade'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
              
                
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="sabado_rota1"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['sabado_rota1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="sabado_hora1" value="<?php echo $edit['sabado_hora1'];?>" class="form-control"  <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="sabado_rota2"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['sabado_rota2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="sabado_hora2" value="<?php echo $edit['sabado_hora2'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
               
               <div class="form-group col-xs-12 col-md-2">  
                      <select name="sabado_rota3"  class="form-control" <?php echo $disabled;?> >
                            <option value="">Selecione Rota</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos status no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['sabado_rota3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                </div>
                
              <div class="form-group col-xs-12 col-md-1">
                <input type="text" name="sabado_hora3" value="<?php echo $edit['sabado_hora3'];?>" class="form-control" <?php echo $readonly;?> >
              </div> 
              
              
 				</div><!-- /.row -->
		  </div><!-- /.box-body -->
          
<!-- /.Faturamento-->          
          
          	<div class="box-header with-border">
                  <h3 class="box-title">Faturamento</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
              
             <div class="form-group col-xs-12 col-md-4">
                <label>Cobran&ccedil;a da Coleta</label>
                <select name="cobranca_coleta" class="form-control" <?php echo $disabled;?> >
                        <option value="">Selecione a Cobran&ccedil;a</option>
                            <?php 
                                $leitura = read('contrato_cobranca',"WHERE id ORDER BY id ASC");
                                if(!$leitura){
                                    echo '<option value="">N�o temos Cobran�a no momento</option>';	
                                }else{
                                    foreach($leitura as $mae):
                                       if($edit['cobranca_coleta'] == $mae['id']){
                                            echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                         }else{
                                            echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                        }
                                    endforeach;	
                                }
                            ?> 
                  </select>
     		  </div><!-- /.form-group col-xs-12 col-md-1 -->
                
               <div class="form-group col-xs-12 col-md-2"> 
                  <label>Dia do Fechamento</label>
                 <input name="dia_fechamento" type="number" max="30" min="1" value="<?php echo $edit['dia_fechamento'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
             
              <div class="form-group col-xs-12 col-md-2"> 
                  <label>Dia do Vencimento</label>
                 <input name="dia_vencimento" type="number" max="30" min="1" value="<?php echo $edit['dia_vencimento'];?>" class="form-control" <?php echo $readonly;?> />
    		  </div>
           
              <div class="form-group col-xs-12 col-md-4">
                   <input name="nao_construir_faturamento" type="checkbox" value="1" <?PHP echo $edit['nao_construir_faturamento']; ?>  class="minimal" <?php echo $disabled;?> >
            	 N&atilde;o Construir Faturamento
              </div> 
           </div><!-- /.row -->
		   </div><!-- /.box-body -->
         
         
         	<div class="box-header with-border">
                  <h3 class="box-title">Boleto Banc&aacute;rio</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
                  
             <div class="form-group col-xs-2">
                <label>Boleto Banc&aacute;rio</label>
                <select name="boleto_bancario" class="form-control" <?php echo $disabled;?> >
                  <option value="">Selecione</option>
                  <option <?php if($edit['boleto_bancario'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['boleto_bancario'] == '0') echo' selected="selected"';?> value="0">Nao</option>
                 </select>
            </div><!-- /.row -->
             
           <div class="form-group col-xs-12 col-md-2"> 
                <label>Valor da Tarifa</label>
              	<input type="text" name="boleto_valor" style="text-align:right" value="<?php echo converteValor($edit['boleto_valor']);?>" class="form-control dinheiro" <?php echo $readonly;?> >
            </div> 
            
            
            <div class="form-group col-xs-12 col-md-4">
                   <input name="enviar_boleto_correio" type="checkbox" value="1" <?PHP echo $edit['enviar_boleto_correio']; ?>  class="minimal" <?php echo $disabled;?> >
            	 Enviar Boleto pelo Correio
              </div> 
              
               
            <div class="form-group col-xs-12 col-md-4">
                   <input name="nao_enviar_extrato" type="checkbox" value="1" <?PHP echo $edit['nao_enviar_extrato']; ?>  class="minimal" <?php echo $disabled;?> >
            	  N&atilde;o Enviar Extrato
              </div> 
            
            
             </div><!-- /.row -->
		   </div><!-- /.box-body -->
            
           <div class="box-header with-border">
                  <h3 class="box-title">Nota Fiscal</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
            
           <div class="form-group col-xs-12 col-md-2"> 
                <label>Nota Fiscal</label>
                <select name="nota_fiscal" class="form-control" <?php echo $disabled;?> >
                  <option value="">Selecione</option>
                  <option <?php if($edit['nota_fiscal'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['nota_fiscal'] == '0') echo' selected="selected"';?> value="0">Nao</option>
                 </select>
            </div><!-- /.row -->
             
             <div class="form-group col-xs-12 col-md-2"> 
                <label>Iss %</label>
              	<input type="text" name="iss_valor" style="text-align:right" value="<?php echo converteValor($edit['iss_valor']);?>" class="form-control"  <?php echo $readonly;?> <?php echo $readonly;?>>
            </div> 
             
             <div class="form-group col-xs-12 col-md-2"> 
                <label>Iss Retido</label>
                <select name="iss_retido" class="form-control" <?php echo $disabled;?> >
                  <option value="">Selecione</option>
                  <option <?php if($edit['iss_retido'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['iss_retido'] == '0') echo' selected="selected"';?> value="0">Nao</option>
                 </select>
            </div><!-- /.row -->
				  
			 <div class="form-group col-xs-12 col-md-3"> 
                <label>Nota Fiscal no Faturamento</label>
                <select name="nota_no_faturamento" class="form-control" <?php echo $disabled;?> >
                  <option value="">Selecione</option>
                  <option <?php if($edit['nota_no_faturamento'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['nota_no_faturamento'] == '0') echo' selected="selected"';?> value="0">Nao</option>
                 </select>
            </div><!-- /.row -->
    
        
              <div class="form-group col-xs-12 col-md-12"> 
				<label>Observa&ccedil;&atilde;o da Nota </label>
               <input type="text" name="observacao_nota" value="<?php echo $edit['observacao_nota'];?>" class="form-control"   <?php echo $disabled;?> >
			</div>
              </div><!-- /.row -->
		   </div><!-- /.box-body -->
         
			
<!-- /.Manifesto & Cobran�a-->
        	
         	<div class="box-header with-border">
                  <h3 class="box-title">Manifesto & Cobran&ccedil;a</h3>
                </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
                <div class="form-group col-xs-4">
                <label>Manifesto</label>
                <select name="manifesto" class="form-control" <?php echo $disabled;?> >
                        <option value="">Selecione o Manifesto</option>
                            <?php 
                                $leitura = read('contrato_manifesto',"WHERE id ORDER BY id ASC");
                                if(!$leitura){
                                    echo '<option value="">N�o temos registo no momento</option>';	
                                }else{
                                    foreach($leitura as $mae):
                                       if($edit['manifesto'] == $mae['id']){
                                            echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                         }else{
                                            echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                        }
                                    endforeach;	
                                }
                            ?> 
                  </select>
               </div>  
                 
             <div class="form-group col-xs-12 col-md-2"> 
                <label>Valor Manifesto</label>
               	<input type="text" name="manifesto_valor" style="text-align:right" value="<?php echo converteValor($edit['manifesto_valor']);?>" class="form-control dinheiro" <?php echo $readonly;?> >
            </div>
            
            <div class="form-group col-xs-12 col-md-2"> 
                <label>Cobrar Loca&ccedil;&atilde;o</label>
                <select name="cobrar_locacao" class="form-control" <?php echo $disabled;?> > 
                  <option value="">Selecione</option>
                  <option <?php if($edit['cobrar_locacao'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['cobrar_locacao'] == '0') echo' selected="selected"';?> value="0">Nao</option>
                 </select>
            </div><!-- /.row --> 
           
            </div><!-- /.row -->
          </div><!-- /.box-body --> 
            
         	<div class="box-header with-border">
                  <h3 class="box-title">Etiquetas</h3>
                </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
            
             <div class="form-group col-xs-12 col-md-3"> 
                <label>Saldo de Etiqueta</label>
               	<input type="text" name="saldo_etiqueta" style="text-align:right" value="<?php echo $edit['saldo_etiqueta'];?>" class="form-control" <?php echo $readonly;?> >
            </div>
            
              <div class="form-group col-xs-12 col-md-3"> 
                <label>Saldo M&iacute;nimo de Etiqueta</label>
               	<input type="text" name="saldo_etiqueta_minimo" style="text-align:right" value="<?php echo $edit['saldo_etiqueta_minimo'];?>" class="form-control" <?php echo $readonly;?> >
            </div> 
 
          
            </div><!-- /.row -->
          </div><!-- /.box-body -->
                
           
       	  <div class="box-footer">
              <a href="javascript:window.history.go(-1)">
              <input type="button" value="Voltar" class="btn btn-warning">
              </a>	
			  <?php 
			  
			if($acao=="baixar"){
				echo '<input type="submit" name="ativar" value="Ativar Contrato" class="btn btn-primary" />';
				echo '<input type="submit" name="suspender" value="Suspender" class="btn btn-success" />';
				echo '<input type="submit" name="cancelar" value="Cancelar" class="btn btn-danger" />';
			}
			  
			if($acao=="aprovar"){
				echo '<input type="submit" name="ativar" value="Ativar Contrato" class="btn btn-success" />';
			}
			  
			if($acao=="ativar"){
				echo '<input type="submit" name="ativar" value="Ativar Contrato" class="btn btn-success" />';
			}
			  
			if($acao=="atualizar"){
				echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
				/* echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclus�o do Registro ?\')" />';*/
			}
			  
			if($acao=="cadastrar"){
					echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';
			}

			  ?>
			</div> <!-- /.box-footer -->
		</form>
	
	</div><!-- /.tab-pane 1 -->
			
			
<!-- /.TIPO DE RESIDUO -->

			
		<div class="tab-pane <?php echo ($_SESSION['aba']=='2' ? " active " : " " );?>" id="aba-2">

     		<div class="box-header with-border">
                  <h3 class="box-title">Tipo de Coleta</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/contrato/coleta-editar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					 <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
					  Nova Coleta
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left-->
 			</div><!-- /.box-header -->
 					
			<div class="box-body table-responsive">  
			 <div class="box-body table-responsive data-spy='scroll'">
			  <div class="col-md-12 scrool">  
     
				<?php 

				$leitura = read('contrato_coleta',"WHERE id AND id_contrato='$contratoId' ORDER BY vencimento ASC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Tipo de Coleta</td>
								<td align="center">Quant</td>
								<td align="center">Vl Unit�rio</td>
								<td align="center">Vl Extra</td>
								<td align="center">In�cio</td>
								<td align="center">Vencimento</td>
								<td align="center">Valor Mensal</td>
								<td align="center">Indice</td>
								<td align="center">Intera��o</td>
								<td align="center" colspan="5">Gerenciar</td>
							</tr>';
					foreach($leitura as $mostra2):

						echo '<tr>';
					
							echo '<td align="center">'.$mostra2['id'].'</td>';

							$tipoColetaId = $mostra2['tipo_coleta'];
							$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

							echo '<td>'.$tipoColeta['nome'].'</td>';
							echo '<td align="center">'.$mostra2['quantidade'].'</td>';
							echo '<td align="center">'.converteValor($mostra2['valor_unitario']).'</td>';
							echo '<td align="center">'.converteValor($mostra2['valor_extra']).'</td>';

							echo '<td align="center">'.converteData($mostra2['inicio']).'</td>';
							echo '<td align="center">'.converteData($mostra2['vencimento']).'</td>';
							echo '<td align="center">'.converteValor($mostra2['valor_mensal']).'</td>';
					
							echo '<td align="center">'.$mostra2['percentual'].'</td>';
					
							echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra2['interacao'])).'</td>';
					
							echo '<td align="center">
										<a href="painel.php?execute=suporte/contrato/coleta-editar&coletaEditar='.$mostra2['id'].'">
											<img src="ico/editar.png" alt="Editar" title="Editar Coleta" class="tip" />
										</a>
									  </td>';
							echo '<td align="center">
									<a href="painel.php?execute=suporte/contrato/coleta-editar&coletaDeletar='.$mostra2['id'].'">
										<img src="ico/excluir.png" alt="Editar" title="Deletar Coleta" class="tip" />
									</a>
								  </td>';
						echo '</tr>';
					 endforeach;
					 echo '</table>';
					}
				?>
 			
      		        </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->
  													
		</div><!-- /.tab-pane 2 -->
		
<!-- /.ORDEM DE SERVI�O -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='3' ? " active " : " " );?>" id="aba-3">
		
				<div class="box-header with-border">
                  <h3 class="box-title">Ordem de Servi&ccedil;o</h3>
                </div><!-- /.box-header -->
      
               <div class="box-header">
                
                 <div class="col-xs-10 col-md-3 pull-left">  
					<a href="painel.php?execute=suporte/ordem/ordem-editar&contratoId=<?PHP echo $contratoId;?>" >
				  		<img src="ico/novo.png" title="Criar Novo" />
            		    <small> Ordem de Servi&ccedil;o</small>
            		 </a> 
            	</div><!-- /col-xs-10 col-md-5 pull-right-->
	 
            	<div class="col-xs-10 col-md-3 pull-left">  
            		  	<a href="painel.php?execute=suporte/relatorio/cronograma-pdf&contratoId=<?PHP echo $contratoId; ?>"  target="_blank">
						  	<img src="ico/contratos.png" title="Imprimir Cronograma" />
						 	<small>Cronograma</small>
						</a>
            	</div><!-- /col-xs-10 col-md-5 pull-right-->

          		<div class="col-xs-10 col-md-3 pull-left">  
            		  	<a href="painel.php?execute=suporte/relatorio/ficha-cliente-pdf&contratoId=<?PHP echo $contratoId; ?>"  target="_blank">
						  		<img src="ico/contratos.png" title="Imprimir Cronograma" />
						 		 <small>Ficha de Cliente</small>
						 </a>
            	</div><!-- /col-xs-10 col-md-5 pull-right-->
				   
				<div class="col-xs-10 col-md-3 pull-left">     
				      <form name="formPesquisa" method="post" class="form-inline " role="form">
                      <input type="checkbox"  name="checkBoxOrdemTotal"  <?PHP echo $ordemTotal; ?> class="minimal"  onclick="this.form.submit()"/>
                        <!--  <input type="checkbox" onclick="this.form.submit()"/>-->
                          <small>Visualizar todas Ordens de Servi�o</small> 
                     </form>
  				</div><!-- /col-xs-10 col-md-5 pull-right-->
				   
				   
       	  </div><!-- /.box-header -->

          <div class="box-body table-responsive">  
           <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
         
			<?php 
					
				$_SESSION['atendimentocontrato']=$_SERVER['REQUEST_URI'];
			
				if($_SESSION['ordemTotal']=='1'){
					$leitura = read('contrato_ordem',"WHERE id AND id_contrato='$contratoId' ORDER BY data DESC, hora ASC");
				}else{
					$data = date("Y-m-d", strtotime("-60 day"));
					$leitura = read('contrato_ordem',"WHERE id AND id_contrato='$contratoId' AND data>'$data' ORDER BY data DESC, hora ASC");
				}
		
				
				if($leitura){
						echo '<table class="table table-hover">	
							<tr class="set">
								<td align="center">ID</td>
								<td align="center">Tipo de Coleta</td>
								<td align="center">Coleta</td>
								<td align="center">Data</td>
								<td align="center">Hora</td>
								<td align="center">H Col</td>
								<td align="center">Rota</td>
								<td align="center">Status</td>
								<td align="center">Observa��o</td>
								<td>Foto</td>
								<td>Assi</td>
								<td>Qr</td>
							<td align="center" colspan="5">Gerenciar</td>
							</tr>';
				foreach($leitura as $mostra):
					echo '<tr>';
					
						echo '<td align="center">'.$mostra['id'].'</td>';
			
						$tipoColetaId = $mostra['tipo_coleta1'];
						$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

						echo '<td>'.$coleta['nome'].'</td>';
					    echo '<td>'.$mostra['quantidade1'].'</td>';
					
						echo '<td>'.converteData($mostra['data']).'</td>';
						echo '<td>'.$mostra['hora'].'</td>';
						echo '<td>'.$mostra['hora_coleta'].'</td>';
					
						$rotaId = $mostra['rota'];
						$rota= mostra('contrato_rota',"WHERE id ='$rotaId'");
						echo '<td align="left">'.$rota['nome'].'</td>';
					
					
						$statusId = $mostra['status'];
						$status = mostra('contrato_status',"WHERE id ='$statusId'");
					
						if($statusId==12){
							echo '<td>'.$status['nome'].'</td>';
							
						 }else if($statusId==14){
						   echo '<td align="center"><span class="badge bg-green">'.$status['nome'].'</span></td>';
							
						  }else if($statusId==15){
						   echo '<td align="center"><span class="badge bg-red">'.$status['nome'].'</span></td>';
						  }else if($statusId==13){
							if( $mostra['nao_coletada']<>1){
								echo '<td align="center"><span class="badge bg-light-blue">'.$status['nome'].'</span></td>';
							}else{
								echo '<td align="center"><span class="badge bg-green">N�o Coletado</span></td>';
							}
						  }else{
							echo '<td align="center"><span class="badge bg-light-blue">'.$status['nome'].'</span></td>';

						}
					
						echo '<td>'.substr($mostra['observacao'],0,20).'</td>';
					if($mostra['foto']!= '' && file_exists('../uploads/fotos/'.$mostra['foto'])){
                        echo '<td align="center">
                              <img class="img-thumbnail imagem-tabela zoom" src="'.URL.'/uploads/fotos/'
                                         .$mostra['foto'].'">';
                      }else{
                        echo '<td align="center">
                             <i class="fa fa-picture-o"></i>
                         </td>';
                    }	
		
					if($mostra['assinatura']!= '' && file_exists('../uploads/assinaturas-ordem/'.$mostra['assinatura'])){
                        echo '<td align="center">
                              <img class="img-thumbnail imagem-tabela zoom" src="'.URL.'/uploads/assinaturas-ordem/'.$mostra['assinatura'].'">';
                      }else{
                        echo '<td align="center">
                             <i class="fa fa-picture-o"></i>
                         </td>';
                    }	
		
					if($mostra['qrcode']== '1'){
                       	echo '<td align="center"><span class="badge bg-green">*</span></td>';

                      }else{
                        	echo '<td align="center">-</td>';

                    }	
					
						
							echo '<td align="center">
                                        <a href="painel.php?execute=suporte/ordem/ordem-editar&ordemEditar='.$mostra['id'].'">
                                            <img src="ico/editar.png" alt="Editar" title="Editar"  />
                                        </a>
                                      </td>';
						
							echo '<td align="center">
									<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
										<img src="ico/baixar.png" alt="Editar" title="Baixar"  />
									</a>
								  </td>';	
					
							// imprimir ordem
							echo '<td align="center">
									<a href="painel.php?execute=suporte/ordem/ordem-servico&ordemImprimir='.$mostra['id'].'" target="_blank">
										<img src="ico/imprimir.png" alt="Imprimir" title="Imprimir"  />
									</a>
							  </td>';	
	
					 endforeach;
					 echo '</table>';
					}
				?>
      		 
      		        </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->

		</div><!-- /.tab-pane3 -->
			
			
<!-- /.RECIBIMENTO -->
				
		<div class="tab-pane <?php echo ($_SESSION['aba']=='4' ? " active " : " " );?>" id="aba-4">
			 
			 
			<div class="box-header with-border">
                  <h3 class="box-title">Recebimento</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
              <div class="col-xs-10 col-md-3 pull-left">  
				 <a href="painel.php?execute=suporte/receber/receber-novo&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				 </a>
			  </div><!-- /.col-xs-10 col-md-3 pull-left -->
			</div><!-- /.box-header -->
		   
					
			 <div class="box-body table-responsive data-spy='scroll'">
				<div class="col-md-12 scrool">  
					<div class="box-body table-responsive">
			  
		 	<?php 
			  $hoje=date('Y/m/d');
			  
               $total=0;
			  $leitura = read('receber',"WHERE id AND id_contrato = '$contratoId' ORDER BY emissao ASC");
			    if($leitura){
					echo '<table class="table table-hover">	 
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Emiss�o</td>
					<td align="center">Vencimento</td>
					<td align="center">Prog Pag</td>
					<td align="center">Pagamento</td>
					<td align="center">Valor Bruto</td>
					<td align="center">Juros</td>
					<td align="center">Desc</td>
					<td align="center">Valor L�quido</td>
					<td align="center">Status</td>
					<td align="center">Nota</td>
					<td align="center">Pag/Banco</td>
					<td align="center">B</td>
					<td align="center">Obs</td>
					<td align="center" colspan="8">Gerenciar</td>
				</tr>';
				foreach($leitura as $mostra):
						echo '<tr>';
					
							echo '<td>'.$mostra['id'].'</td>';
							
							echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
							echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
					
							if(!empty($mostra['refaturamento_vencimento']) ){
								echo '<td align="center">'.converteData($mostra['refaturamento_vencimento']).'</td>';
							}else{
								echo '<td align="center">-</td>';
							}
					
							if($mostra['status']<>'Em Aberto'){
							   echo '<td align="center">'.converteData($mostra['pagamento']).'</td>';
							  }else{
								echo '<td align="center">-</td>';  
							}
					
							echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
							
							echo '<td align="right">'.converteValor($mostra['juros']).'</td>';
							echo '<td align="right">'.converteValor($mostra['desconto']).'</td>';
							echo '<td align="right">'.converteValor($mostra['valor']+$mostra['juros']-$mostra['desconto']).'</td>';
							
							echo '<td>'.$mostra['status'].'</td>';
				
			
							echo '<td align="center">'.$mostra['nota'].'</td>';
							
							$bancoId=$mostra['banco'];
							$banco = mostra('banco',"WHERE id ='$bancoId'");
							$formId=$mostra['formpag'];
							$formpag = mostra('formpag',"WHERE id ='$formId'");
							echo '<td align="left">'.$banco['nome']. "|".substr($formpag['nome'],0,11).'</td>';
							if(empty($mostra['imprimir'])){
								echo '<td align="center">-</td>';
							}else{
								echo '<td align="center">*</td>';
							}
							echo '<td align="left">'.substr($mostra['observacao'],0,7).'</td>';
					
							if($mostra['status']=='Em Aberto'){
								$valorEmAberto=$valorEmAberto+$mostra['valor'];
							}elseif($mostra['status']=='Baixado'){
								$valorQuitado=$valorQuitado+$mostra['valor'];
							}else{
								$valorOutros=$valorOutros+$mostra['valor'];
							}
					
							 $total= $total+1;
					
							$valorTotal = $valorEmAberto+$valorQuitado+$valorOutros;
					
							echo '<td align="center">
										<a href="../cliente/painel2.php?execute=suporte/contrato/extrato-cliente-resumido&boletoId='.$mostra['id'].'" target="_blank">
											<img src="ico/extrato.png" alt="Extrato" title="Extrato Cliente"  />
										</a>
										</td>';	
					
							echo '<td align="center">
										<a href="../cliente/painel2.php?execute=suporte/contrato/extrato-cliente&boletoId='.$mostra['id'].'" target="_blank">
											<img src="ico/extrato.png" alt="Extrato" title="Extrato Interno"  />
										</a>
										</td>';	
							
							if($mostra['status']=='Em Aberto' AND $mostra['protesto']<>1) {
								echo '<td align="center">
										 <a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
											<img src="ico/editar.png" alt="Editar" title="Editar" />
										 </a>
									  </td>';
								
								echo '<td align="center">
										 <a href="painel.php?execute=suporte/receber/receber-baixar&receberNumero='.$mostra['id'].'">
											<img src="ico/baixar.png" alt="Baixar" title="Baixar" />
										 </a>
									  </td>';
								
								
								if($edit['enviar_boleto_correio']==''){
									
									echo '<td align="center">
										<a href="painel.php?execute=suporte/receber/receber-editar&receberEnviar='.$mostra['id'].'">
											<img src="ico/email.png" alt="Aviso" title="Aviso-Email"  />
										</a>
										</td>';
								}else{
									echo '<td align="center">
										<a href="#">
											<img src="ico/correios.png" alt="Enviar por Correio" title="Enviar por Correio"  />
										</a>
										</td>';
								}
	

								echo '<td align="center">
										<a href="../cliente/painel2.php?execute=boleto/emitir-boleto&boletoId='.$mostra['id'].'" target="_blank">
											<img src="ico/boleto.png" alt="Boleto" title="Boleto"  />
										</a>
										</td>';		
		
								if(empty($mostra['link'])){
							 			echo '<td align="center">
									<a href="../cliente/painel2.php?execute=suporte/contrato/rps&rps='.$mostra['id'].'" target="_blank">
										<img src="ico/rps.png" alt="RPS" title="RPS"  />
									</a>
									</td>';	
									
								}else{
									 echo '<td align="center">
										<a href="'.$mostra['link'] .'" target="_blank">
											<img src="ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
									  </td>';
								}
								 
							  }else{
									echo '<td align="center">
										 <a href="painel.php?execute=suporte/receber/receber-editar&receberVisualizar='.$mostra['id'].'">
											<img src="ico/visualizar.png" alt="Editar" title="Visualizar" />
										 </a>
									  </td>';
								
								echo '<td align="center"></td>';
								echo '<td align="center"></td>';
								
								if(empty($mostra['link'])){
									echo '<td align="center"></td>';
										echo '<td align="center">
						<a href="../cliente/painel2.php?execute=suporte/contrato/rps&rps='.$mostra['id'].'" target="_blank">
							<img src="ico/rps.png" alt="RPS" title="RPS"  />
              			</a>
						</td>';	
									
								}else{
									echo '<td align="center"></td>';
									 echo '<td align="center">
										<a href="'.$mostra['link'] .'" target="_blank">
											<img src="ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
									  </td>';
								}
							}
					
							echo '</tr>';
                     endforeach;
					  
					 echo '<tfoot>';
					
                        echo '<tr>';
                        echo '<td colspan="21">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
			
						echo '<tr>';
                        echo '<td colspan="21">' . 'Valor Baixado R$ : ' .converteValor($valorQuitado);
						echo '</tr>';
                       
                       	echo '<tr>';
						echo '<td colspan="21">' . 'Valor Em Aberto R$ : ' .converteValor($valorEmAberto);
					  	echo '</tr>';
					
						echo '<tr>';
						echo '<td colspan="21">' . 'Valor Protesto/Juridico/Dispensa R$ : ' .converteValor($valorOutros);
					  	echo '</tr>';

						echo '<tr>';
                        echo '<td colspan="21">' . 'Valor Total R$ : ' .converteValor($valorTotal);
						echo '</tr>';
	
        				echo '</tfoot>';
					  
                    echo '</table>';
                  }
               ?>   
					
						<br>
						<br>
						
			<?php 
      		
             $leitura = read('receber_negociacao',"WHERE id AND id_cliente= '$clienteId' ORDER BY id DESC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Boleto</td>
						<td align="center">Motivo</td>
						<td align="center">Data</td>
						<td align="center">Previs�o Pag</td>
						<td align="center">Observa��o</td>
						<td align="center">Solu��o</td>
						<td align="center">Peso</td>
						<td colspan="2" align="center">Gerenciar</td>
					

                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
					  
                              echo '<td>'.$mostra['id_receber'].'</td>';
	
								$motivoId = $mostra['id_motivo'];
								$motivo = mostra('recebe_negociacao_motivo',"WHERE id ='$motivoId'");
								echo '<td>'.$motivo['nome'].'</td>';

								echo '<td>'.converteData($mostra['data']).'</td>';
					  			echo '<td>'.converteData($mostra['previsao_pagamento']).'</td>';


								echo '<td>'.substr($mostra['observacao'],0,25).'</td>';

								$solucaoId = $mostra['id_solucao'];
								$solucao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId'");
								echo '<td>'.$solucao['nome'].'</td>';
					  			echo '<td>'.$solucao['peso'].'</td>';
					  
							 echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-negociacao-editar&negociacaoVisualizar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png"   title="Visualizar" />
              			</a>
				      </td>';
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
 	
               
                 </div><!--/col-md-12 scrool-->   
				 </div><!-- /.box-body table-responsive --> 
       		 </div><!--/box-body table-responsive-->
               

		</div><!-- /.tab-pane4 -->
		
		
<!-- /.QUALIDADE -->


		<div class="tab-pane <?php echo ($_SESSION['aba']=='5' ? " active " : " " );?>" id="aba-5">
		 
		 
		 	<div class="box-header with-border">
                  <h3 class="box-title">Qualidade</h3>
            </div><!-- /.box-header -->
            
            
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/agenda/qualidade-editar&contratoId=
								<?PHP echo $contratoId; ?>" class="btnnovo">
						<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
					 </a>
        		</div><!-- /.col-xs-10 col-md-3 pull-left -->
         	</div><!-- /.box-header -->
         	 
          	<div class="box-body table-responsive">  
          	   <div class="box-body table-responsive data-spy='scroll'">
     				<div class="col-md-12 scrool">  
         
			 <?php 
    
             $leitura = read('contrato_qualidade',"WHERE id AND id_contrato = '$contratoId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Retorno</td>
                        <td align="center">Descri��o</td>
                        <td align="center">Atendente</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
                                echo '<td>'.$mostra['id'].'</td>';
                               echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
								echo '<td>'.converteData($mostra['retorno']).'</td>';
                                echo '<td>'.$mostra['descricao'].'</td>';
								$atendenteId = $mostra['atendente'];
								$atendente = mostra('contrato_atendente',"WHERE id ='$atendenteId '");
								echo '<td>'.$atendente['nome'].'</td>';
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/agenda/qualidade-editar&qualidadeEditar='.$mostra['id'].'">
                                            <img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/agenda/qualidade-editar&qualidadeDeletar='.$mostra['id'].'">
                                            <img src="ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/agenda/qualidade-editar&qualidadeBaixar='.$mostra['id'].'">
                                            <img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
                                        </a>
                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
                   
                     </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->

		</div><!--/aba-5-->
		
		
<!-- /.AGENDA -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='6' ? " active " : " " );?>" id="aba-6">
		
		
			<div class="box-header with-border">
                  <h3 class="box-title">Agenda</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/agenda/agenda-editar&contratoId=
					 			<?PHP echo $contratoId; ?>" class="btnnovo">
						<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
					 </a>
       			 </div><!-- /.col-xs-10 col-md-3 pull-left-->
        	 </div><!-- /.box-header -->
        	 
        	 <div class="box-body table-responsive">  
          	   <div class="box-body table-responsive data-spy='scroll'">
     				<div class="col-md-12 scrool">  
        	 
			 <?php 
    
             $leitura = read('agenda',"WHERE id AND id_contrato = '$contratoId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Retorno</td>
                        <td align="center">Descri��o</td>
                        <td align="center">Atendente</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
                                echo '<td>'.$mostra['id'].'</td>';
                               echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
								echo '<td>'.converteData($mostra['retorno']).'</td>';
                                echo '<td>'.$mostra['descricao'].'</td>';
								$atendenteId = $mostra['atendente'];
								$atendente = mostra('contrato_atendente',"WHERE id ='$atendenteId '");
								echo '<td>'.$atendente['nome'].'</td>';
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/agenda/agenda-editar&agendaEditar='.$mostra['id'].'">
                                            <img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/agenda/agenda-editar&agendaDeletar='.$mostra['id'].'">
                                            <img src="ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/agenda/agenda-editar&agendaBaixar='.$mostra['id'].'">
                                            <img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
                                        </a>
                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
                   
                 </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->
			   

		</div><!-- /.tab-pane6 -->
			
			
<!-- /.Atendimento -->
				
		<div class="tab-pane <?php echo ($_SESSION['aba']=='7' ? " active " : " " );?>" id="aba-7">
		
			
               <div class="box-header with-border">
                  <h3 class="box-title">Atendimento</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/atendimento/pedido-editar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
						<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo"  />
					 </a>
       			 </div><!-- /.col-xs-10 col-md-3 pull-left-->
        	 </div><!-- /.box-header -->
        	 
        	 <div class="box-body table-responsive">  
          	   <div class="box-body table-responsive data-spy='scroll'">
     				<div class="col-md-12 scrool">  
        	 
			 <?php 
    
             $leitura = read('pedido',"WHERE id AND id_contrato = '$contratoId' ORDER BY id DESC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Motivo</td>
						<td align="center">Origem</td>
                        <td align="center">Solicitacao</td>
                        <td align="center">Solu��o</td>
						<td align="center">Status</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $pedido):
                            echo '<tr>';
                                echo '<td>'.$pedido['id'].'</td>';
								echo '<td>'.converteData($pedido['data_solicitacao']).'</td>';
								$suporteId = $pedido['id_suporte'];
								$suporte = mostra('pedido_suporte',"WHERE id ='$suporteId '");
								echo '<td>'.$suporte['nome'].'</td>';
					  			$origemId = $pedido['id_origem'];
								$origem = mostra('pedido_origem',"WHERE id ='$origemId '");
								echo '<td>'.$origem['nome'].'</td>';
					  		    echo '<td>'.substr($pedido['solicitacao'],0,30).'</td>';
					  			echo '<td>'.converteData($pedido['data_solucao']).'</td>';
					  			echo '<td>'.$pedido['status'].'</td>';
					  		
								echo '<td align="center">
									<a href="painel.php?execute=suporte/atendimento/pedido-editar&suporteEditar='.$pedido['id'].'">
												<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
											</a>
										  </td>';
									echo '<td align="center">
									<a href="painel.php?execute=suporte/atendimento/pedido-editar&suporteBaixar='.$pedido['id'].'">
												<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
											</a>
										  </td>';
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
                   
                 </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->
			
			
			   <div class="box-header with-border">
                  <h3 class="box-title">Atendimento - Pos-Venda</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/atendimento/atendimento-editar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
						<img src="ico/novo.png" title="Criar Novo"  />
					 </a>
       			 </div><!-- /.col-xs-10 col-md-3 pull-left-->
        	 </div><!-- /.box-header -->
        	 
        	 <div class="box-body table-responsive">  
          	   <div class="box-body table-responsive data-spy='scroll'">
     				<div class="col-md-12 scrool">  
        	 
			 <?php 
    
             $leitura = read('contrato_atendimento_pos_venda',"WHERE id AND id_contrato = '$contratoId' ORDER BY id DESC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Motivo</td>
                        <td align="center">Solicitacao</td>
                        <td align="center">Solu��o</td>
						<td align="center">Status</td>
						<td align="center">Foto</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $atendimento):
                            echo '<tr>';
                                echo '<td>'.$atendimento['id'].'</td>';
								echo '<td>'.converteData($atendimento['data_solicitacao']).'</td>';
								$motivoId = $atendimento['motivo'];
								$motivo = mostra('contrato_atendimento_pos_venda_motivo',"WHERE id ='$motivoId'");
								echo '<td>'.$motivo['nome'].'</td>';
				
					  		    echo '<td>'.substr($atendimento['solicitacao'],0,30).'</td>';
					  			echo '<td>'.converteData($atendimento['data_solucao']).'</td>';
					  			echo '<td>'.$atendimento['status'].'</td>';
					  
	if($atendimento['foto']!= '' && file_exists('../uploads/atendimentos/'.$atendimento['foto'])){ 
									echo '<td align="center">
										  <img class="img-thumbnail imagem-tabela zoom" src="'.URL.'/uploads/atendimentos/'
													 .$atendimento['foto'].'">';
								  }else{

									echo '<td align="center">
										 <i class="fa fa-picture-o"></i>
									 </td>';
								}	
					  		
								echo '<td align="center">
									<a href="painel.php?execute=suporte/atendimento/atendimento-editar&atendimentoEditar='.$atendimento['id'].'">
												<img src="ico/editar.png" title="Editar" />
											</a>
										  </td>';
									echo '<td align="center">
									<a href="painel.php?execute=suporte/atendimento/atendimento-editar&atendimentoBaixar='.$atendimento['id'].'">
												<img src="ico/baixar.png" title="Baixar" />
											</a>
										  </td>';
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
                   
                 </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->
			   

		</div><!-- /.tab-pane7 -->
		
<!-- /.BAIXA DE CONTRATO -->

		<div class="tab-pane <?php echo ($_SESSION['aba']=='8' ? " active " : " " );?>" id="aba-8">

     		<div class="box-header with-border">
                  <h3 class="box-title">Baixa/Status</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/contrato/contrato-baixar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					 <img src="ico/novo.png" title="Baixa de Contrato" />
					  Baixa de Contrato
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left -->
			</div><!-- /.box-header -->
 				
  			<div class="box-body table-responsive">  
     
				<?php 

				$leitura = read('contrato_baixa',"WHERE id AND id_contrato='$contratoId' ORDER BY interacao DESC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Tipo de Baixa</td>
								<td align="center">Apartir De</td>
								<td align="center">Motivo</td>
								<td align="center">Intera��o</td>
								<td align="center" colspan="5">Gerenciar</td>
							</tr>';
					foreach($leitura as $contratoBaixa):

						echo '<tr>';
							
							echo '<td>'.$contratoBaixa['id'].'</td>';
					
							$tipoId = $contratoBaixa['tipo'];
							$tipo = mostra('contrato_baixa_tipo',"WHERE id ='$tipoId'");
							echo '<td align="left">'.$tipo['nome'].'</td>';

						 	echo '<td align="left">'.converteData($contratoBaixa['data']).'</td>';
					
							echo '<td align="left">'.substr($contratoBaixa['motivo'],0,30) .'</td>';
												
							
							echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($contratoBaixa['interacao'])).'</td>';
							echo '<td align="center">
                                        <a href="painel.php?execute=suporte/contrato/contrato-baixar&contratoBaixarEditar='.$contratoBaixa['id'].'">
                                            <img src="ico/editar.png" alt="Editar" title="Editar"  />
                                        </a>
                                      </td>';
					
							echo '<td align="center">
                                        <a href="painel.php?execute=suporte/contrato/contrato-baixar&contratoEnviar='.$contratoBaixa['id'].'">
                                            <img src="ico/email.png" alt="Enviar Mensagem" title="Enviar Mensagem"  />
                                        </a>
                                      </td>';
 			
						echo '</tr>';
					 endforeach;
					 echo '</table>';
					}
				?>
				
				
 			</div><!-- /.box-body table-responsive -->
      
      
       <div class="box-header with-border">
                  <h3 class="box-title">Cancelamento</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/contrato/contrato-cancelamento&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					 <img src="ico/novo.png" title="Solicitar Cancelamento"/>
					  Solicitar Cancelamento
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left -->
			</div><!-- /.box-header -->
 				
  			<div class="box-body table-responsive">  
     
				<?php 

				$leitura = read('contrato_cancelamento',"WHERE id AND id_contrato='$contratoId' ORDER BY interacao DESC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Solicita��o</td>
								<td align="center">Encerramento</td>
								<td align="center">Motivo</td>
								<td align="center">Status</td>
								<td align="center">Recuperada</td>
								<td align="center" colspan="5">Gerenciar</td>
							</tr>';
					foreach($leitura as $cancelamento):

						echo '<tr>';
							
							echo '<td>'.$cancelamento['id'].'</td>';
							echo '<td>'.converteData($cancelamento['data_solicitacao']).'</td>';
							echo '<td>'.converteData($cancelamento['data_encerramento']).'</td>';
					 		$tipoId = $cancelamento['motivo'];
					
							$tipo = mostra('contrato_cancelamento_motivo',"WHERE id ='$tipoId'");
							echo '<td align="left">'.$tipo['nome'].'</td>';
												
							echo '<td>'.$cancelamento['status'].'</td>';
					
							if ($cancelamento['recuperada']==1){
								echo '<td align="left">Sim</td>';
							}elseif ($cancelamento['recuperada']==2){
								echo '<td align="left">N�o</td>';
							}else{
								echo '<td align="left">-</td>';
							}
						 
							echo '<td align="center">
                                <a href="painel.php?execute=suporte/contrato/contrato-cancelamento&contratoCancelamento='.$cancelamento['id'].'">
                                    <img src="ico/editar.png" alt="Editar" title="Editar"  />
                                  </a>
                                 </td>';
					
							echo '<td align="center">
									<a href="painel.php?execute=suporte/relatorio/cancelamento&cancelamentoId='.$cancelamento['id'].'" target="_blank">
										<img src="ico/extrato.png" title="Cancelamento"  />
									</a>
									</td>';
 			
						echo '</tr>';
					 endforeach;
					
					 echo '</table>';
					}
				?>

 	  </div><!-- /.box-body table-responsive -->
            
	</div><!--/aba-8-->

<!-- /.EQUIPAMENTO -->

		<div class="tab-pane <?php echo ($_SESSION['aba']=='9' ? " active " : " " );?>" id="aba-9">
			
            <div class="box-header with-border">
                  <h3 class="box-title">Equipamento & Etiqueta</h3>
            </div><!-- /.box-header -->

            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/estoque/equipamento-retirada-editar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					 <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
						 <small> Solicita&ccedil;&atilde;o de Equipamento</small>
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left -->
			</div><!-- /.box-header -->
 				
  			<div class="box-body table-responsive">  
     
				<?php 

				$leitura = read('estoque_equipamento_retirada',"WHERE id AND id_contrato='$contratoId' ORDER BY data_solicitacao DESC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Equipamento</td>
								<td align="center">Quantidade</td>
								<td align="center">Solicita��o</td>
								<td align="center">Entrega</td>
								<td align="center">Status</td>
							</tr>';
					foreach($leitura as $mostra2):
						echo '<tr>';
						$equipamentoId = $mostra2['id_equipamento'];
						$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
						echo '<td align="left">'.$mostra2['id'].'</td>';
						echo '<td align="left">'.$equipamento['nome'].'</td>';
						echo '<td align="right">'.$mostra2['quantidade'].'</td>';
						echo '<td align="right">'.converteData($mostra2['data_solicitacao']).'</td>';
						echo '<td align="right">'.converteData($mostra2['data_entrega']).'</td>';
						echo '<td align="center">'.$mostra2['status'].'</td>';
						echo '</tr>';
					 endforeach;
					 echo '</table>';
					}
				?>

 	 	   </div><!-- /.box-body table-responsive -->
			
			
			   <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/estoque/equipamento-manutencao-editar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					 <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
						 <small>Manuten��o/Fabrica��o de Containers</small>
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left -->
			</div><!-- /.box-header -->
 				
  			<div class="box-body table-responsive">  
     
				<?php 

				$leitura = read('estoque_equipamento_manutencao',"WHERE id AND id_contrato='$contratoId' ORDER BY data_solicitacao DESC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Equipamento</td>
								<td align="center">Quantidade</td>
								<td align="center">Solicita��o</td>
								<td align="center">Entrega</td>
								<td align="center">Status</td>
							</tr>';
					foreach($leitura as $mostra2):
						echo '<tr>';
						$equipamentoId = $mostra2['id_equipamento'];
						$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
						echo '<td align="left">'.$mostra2['id'].'</td>';
						echo '<td align="left">'.$equipamento['nome'].'</td>';
						echo '<td align="right">'.$mostra2['quantidade'].'</td>';
						echo '<td align="right">'.converteData($mostra2['data_solicitacao']).'</td>';
						echo '<td align="right">'.converteData($mostra2['data_entrega']).'</td>';
						echo '<td align="center">'.$mostra2['status'].'</td>';
						echo '</tr>';
					 endforeach;
					 echo '</table>';
					}
				?>

 	 	   </div><!-- /.box-body table-responsive -->
           
   	  		<div class="box-header with-border">
                  <h3 class="box-title"><small>Saldo Atual de Etiqueta : </small>
                  <?PHP echo '  '.$edit['saldo_etiqueta']; ?>
                  </h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/estoque/etiqueta-retirada-editar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					 <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
					 <small> Solicita&ccedil;&atilde;o de Etiqueta</small>
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left -->
				
			</div><!-- /.box-header -->
				
 				
  			<div class="box-body table-responsive">  
     
				<?php 

				$leitura = read('estoque_etiqueta_retirada',"WHERE id AND id_contrato='$contratoId' ORDER BY data_solicitacao DESC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Equipamento</td>
								<td align="center">Quantidade</td>
								<td align="center">Solicita��o</td>
								<td align="center">Entrega</td>
								<td align="center">Status</td>
							</tr>';
					foreach($leitura as $mostra2):

						echo '<tr>';

						$etiquetaId = $mostra2['id_etiqueta'];
						$etiqueta = mostra('estoque_etiqueta',"WHERE id ='$etiquetaId'");
						echo '<td align="left">'.$mostra2['id'].'</td>';
						echo '<td align="left">'.$etiqueta['nome'].'</td>';
						echo '<td align="right">'.$mostra2['quantidade'].'</td>';
						echo '<td align="right">'.converteData($mostra2['data_solicitacao']).'</td>';
						echo '<td align="right">'.converteData($mostra2['data_entrega']).'</td>';
						echo '<td align="center">'.$mostra2['status'].'</td>';
			
						echo '</tr>';
					 endforeach;
					 echo '</table>';
					}
				?>

 	  </div><!-- /.box-body table-responsive -->
            
	</div><!--/aba-9-->
		
<!-- /.INTERACOES -->

		<div class="tab-pane <?php echo ($_SESSION['aba']=='10' ? " active " : " " );?>" id="aba-10">
			
		<div class="box-header with-border">
                  <h3 class="box-title">Aditivo</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/contrato/contrato-aditivo&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
					 <img src="ico/novo.png" title="Aditivo" />
					 Aditivo
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left -->
			</div><!-- /.box-header -->
 				
  			<div class="box-body table-responsive">  
     
				<?php 

				$leitura = read('contrato_aditivo',"WHERE id AND id_contrato='$contratoId' AND solicitacao_consultor is null ORDER BY interacao DESC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Aprova��o</td>
								<td align="center">Motivo</td>
								<td align="center">Frequencia</td>
								<td align="center">Tipo Coleta</td>
								<td align="center">Quantidade</td>
								<td align="center">Vl Unit�rio</td>
								<td align="center">Vl Extra</td>
								<td align="center">Vl Mensal</td>
								<td align="center" colspan="5">Gerenciar</td>
							</tr>';
					foreach($leitura as $contratoAditivo):

						echo '<tr>';
							
							echo '<td>'.$contratoAditivo['id'].'</td>';
					 		echo '<td align="left">'.converteData($contratoAditivo['aprovacao']).'</td>';
					
							$motivoId = $contratoAditivo['motivo'];
							$motivo = mostra('contrato_aditivo_motivo',"WHERE id ='$motivoId'");
							echo '<td>'.$motivo['nome'].'</td>';
					
							echo '<td align="left">'.$contratoAditivo['endereco_aditivo'].'</td>';

						 	echo '<td align="left">'.$contratoAditivo['frequencia_aditivo'].'</td>';
					
							$tipoColetaId=$contratoAditivo['tipo_coleta_aditivo'];							$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					
							echo '<td align="left">'.$tipoColeta['nome'].'</td>';
							echo '<td align="left">'.$contratoAditivo['quantidade_aditivo'].'</td>';
							echo '<td align="left">'.$contratoAditivo['valor_unitario_aditivo'].'</td>';
							echo '<td align="left">'.$contratoAditivo['valor_extra_aditivo'].'</td>';
							echo '<td align="left">'.$contratoAditivo['valor_mensal_aditivo'].'</td>';	
			
							echo '<td align="center">
                                        <a href="painel.php?execute=suporte/contrato/contrato-aditivo&contratoAditivoEditar='.$contratoAditivo['id'].'">
                                            <img src="ico/editar.png" alt="Editar" title="Editar"  />
                                        </a>
                                      </td>';
					
							echo '<td align="center">
                                        <a href="painel.php?execute=suporte/contrato/contrato-aditivo&contratoAditivoDeletar='.$contratoAditivo['id'].'">
                                            <img src="ico/excluir.png"  title="Deletar"  />
                                        </a>
                                      </td>';
					
				 $pdf='../uploads/aditivos/'.$contratoAditivo['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../uploads/aditivos/'.$contratoAditivo['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" title="Aditivos" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}
 			
						echo '</tr>';
					 endforeach;
					 echo '</table>';
					}
				?>
				
				
 			</div><!-- /.box-body table-responsive -->
			
			<div class="box-header with-border">
                  <h3 class="box-title">Aditivo Consultor</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 
			</div><!-- /.box-header -->
			
			
			<div class="box-body table-responsive">  
     
				<?php 

				$leitura = read('contrato_aditivo',"WHERE id AND id_contrato='$contratoId' AND solicitacao_consultor='1' ORDER BY interacao DESC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Aprova��o</td>
								<td align="center">Endere�o</td>
								<td align="center">Frequencia</td>
								<td align="center">Tipo Coleta</td>
								<td align="center">Quantidade</td>
								<td align="center">Vl Unit�rio</td>
								<td align="center">Vl Extra</td>
								<td align="center">Vl Mensal</td>
								<td align="center" colspan="5">Gerenciar</td>
							</tr>';
					foreach($leitura as $contratoAditivo):

						echo '<tr>';
							
								echo '<td>'.$contratoAditivo['id'].'</td>';
					 		echo '<td align="left">'.converteData($contratoAditivo['aprovacao']).'</td>';
							echo '<td align="left">'.$contratoAditivo['endereco_aditivo'].'</td>';

						 	echo '<td align="left">'.$contratoAditivo['frequencia_aditivo'].'</td>';
					
							$tipoColetaId=$contratoAditivo['tipo_coleta_aditivo'];							$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					
							echo '<td align="left">'.$tipoColeta['nome'].'</td>';
							echo '<td align="left">'.$contratoAditivo['quantidade_aditivo'].'</td>';
							echo '<td align="left">'.$contratoAditivo['valor_unitario_aditivo'].'</td>';
							echo '<td align="left">'.$contratoAditivo['valor_extra_aditivo'].'</td>';
							echo '<td align="left">'.$contratoAditivo['valor_mensal_aditivo'].'</td>';	
					
							echo '<td align="center">
                                        <a href="painel.php?execute=suporte/contrato/contrato-aditivo-consultor&contratoAditivoEditar='.$contratoAditivo['id'].'">
                                            <img src="ico/editar.png" alt="Editar" title="Editar"  />
                                        </a>
                                      </td>';
					
							echo '<td align="center">
                                        <a href="painel.php?execute=suporte/contrato/contrato-aditivo-consultor&contratoAditivoDeletar='.$contratoAditivo['id'].'">
                                            <img src="ico/excluir.png"  title="Deletar"  />
                                        </a>
                                      </td>';
 			
						echo '</tr>';
					 endforeach;
					 echo '</table>';
					}
				?>
				
				
 			</div><!-- /.box-body table-responsive -->

		</div><!--/aba-10-->
	
	
		
<!-- /.INTERACOES -->

		<div class="tab-pane <?php echo ($_SESSION['aba']=='11' ? " active " : " " );?>" id="aba-11">
			
			<div class="box-header with-border">
                 <h3 class="box-title">Intera&ccedil;oes</h3>
             </div><!-- /.box-header -->

               <div class="box-header">

				<div class="col-xs-10 col-md-3 pull-left">     
				      <form name="formPesquisa" method="post" class="form-inline " role="form">
                      <input type="checkbox"  name="checkBoxInteracaoTotal"  <?PHP echo $interacaoTotal; ?> class="minimal"  onclick="this.form.submit()"/>
                        <!--  <input type="checkbox" onclick="this.form.submit()"/>-->
                          <small>Visualizar todas Intera��es</small> 
                     </form>
  				</div><!-- /col-xs-10 col-md-5 pull-right-->
				   
				   
       	  </div><!-- /.box-header -->
             
             	
  			<div class="box-body table-responsive">  
  			  <div class="box-body table-responsive data-spy='scroll'">
     			<div class="col-md-12 scrool">  
     			
				 <?php 
					
					if($_SESSION['interacaoTotal']=='1'){
						$leitura = read('interacao',"WHERE id AND id_contrato = '$contratoId' ORDER BY data DESC");
					}else{
						$data = date("Y-m-d", strtotime("-30 day"));
                 		$leitura = read('interacao',"WHERE id AND id_contrato = '$contratoId' AND data>'$data' ORDER BY data DESC");
					}
					
				   	if($leitura){
						echo '<table class="table table-hover">	       
							<tr class="set">
							<td align="center">Id</td>
							<td align="center">Data</td>
							<td align="center">Intera��o</td>
							<td align="center">Usu�rio</td>
                    	</tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
                               echo '<td align="left">'.$mostra['id'].'</td>';
                               echo '<td align="left">'.date('d/m/Y H:i:s',strtotime($mostra['data'])).'</td>';
                               echo '<td align="left">'.$mostra['interacao'].'</td>';
							   echo '<td align="left">'.$mostra['usuario'].'</td>';
                             echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
				 
                   ?>   

              </div><!--/col-md-12 scrool-->   
			</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->

		</div><!--/aba-11-->


<!-- /.Ouvidoria -->
	<?php if($usuario['nivel']=='5'){ // COMERCIAL & GERENCIAL ?>

		<div class="tab-pane <?php echo ($_SESSION['aba']=='12' ? " active " : " " );?>" id="aba-12">
		
			
               <div class="box-header with-border">
                  <h3 class="box-title">Ouvidoria</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/atendimento/ouvidoria-editar&contratoId=<?PHP echo $contratoId; ?>" class="btnnovo">
						<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo"  />
					 </a>
       			 </div><!-- /.col-xs-10 col-md-3 pull-left-->
        	 </div><!-- /.box-header -->
        	 
        	 <div class="box-body table-responsive">  
          	   <div class="box-body table-responsive data-spy='scroll'">
     				<div class="col-md-12 scrool">  
        	 
			 <?php 
    
             $leitura = read('ouvidoria',"WHERE id AND id_contrato = '$contratoId' ORDER BY id DESC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Motivo</td>
						<td align="center">Origem</td>
                        <td align="center">Solicitacao</td>
                        <td align="center">Solu��o</td>
						<td align="center">Status</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $pedido):
                            echo '<tr>';
                                echo '<td>'.$pedido['id'].'</td>';
								echo '<td>'.converteData($pedido['data_solicitacao']).'</td>';
								$motivoId = $pedido['id_motivo'];
								$motivo = mostra('ouvidoria_motivo',"WHERE id ='$motivoId'");
								echo '<td>'.$motivo['nome'].'</td>';
					  		    echo '<td>'.substr($pedido['solicitacao'],0,30).'</td>';
					  			echo '<td>'.converteData($pedido['data_solucao']).'</td>';
					  			echo '<td>'.$pedido['status'].'</td>';
					  		
								echo '<td align="center">
									<a href="painel.php?execute=suporte/atendimento/ouvidoria-editar&suporteEditar='.$pedido['id'].'">
												<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
											</a>
										  </td>';
									echo '<td align="center">
									<a href="painel.php?execute=suporte/atendimento/ouvidoria-editar&suporteBaixar='.$pedido['id'].'">
												<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
											</a>
										  </td>';
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
                   
                 </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->
	  
		</div><!-- /.tab-pane7 -->
	<?php } ?>

	</div><!--/nav-tabs-custom-->
</div> 	<!--/abas-->

</div>
<!--/box box-default-->

</section>
<!-- /.content -->

<section class="content">
		
	   <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				 <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
						   <input type="file" name="arquivo">
						   <br><br>
						  <input type="submit" name="pdf-contrato" value="Upload Contrato PDF" class="btn btn-primary" />
					</form>
				  </div> 
				
				  <div class="form-group pull-right">
					<?php
						$pdf='../uploads/contratos/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/contratos/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" alt="Contrato" title="Contrato" />
								</a>';	
						}
					?>
				</div> 
	
			</div>
		 </div>
		</div>

	   <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
					   <input type="file" name="arquivo">
					   <br><br>
					  <input type="submit" name="pdf-assinatura" value="Upload Assinatura PDF" class="btn btn-primary"/>
					  
					</form>
				  </div> 
				
					<div class="form-group pull-right">
					
						<?php
						$pdf='../uploads/assinaturas/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/assinaturas/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" alt="Assinaturas" title="Assinaturas" />
								</a>';	
						}
					?>
				</div> 
	
			</div>
		 </div>
		</div>
													 
													  
		 <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
					   <input type="file" name="arquivo" placeholder="Procurar">
						
					   <br><br>
						
					  <input type="submit" name="pdf-cancelamento" value="Upload Carta Cancelamento PDF" class="btn btn-primary"/>
					</form>
				  </div> 
				
					<div class="form-group pull-right">
					
						<?php
						$pdf='../uploads/cancelamentos/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/cancelamentos/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Carta de Cancelamento" />
								</a>';	
						}
					?>
				</div> 

			</div>
		 </div>
		</div>
				
	</section><!-- /.content -->	

	
<section class="content">
  	<div class="box box-warning">
     	 <div class="box-body">
      		  
       	 <?php
           echo '<p align="center">'.$cliente['nome'].', '.$cliente['telefone'].' / '.$cliente['contato'].'</p>';
			echo '<p align="center">'.$cliente['endereco'].', '.$cliente['numero'].'  '.$cliente['complemento'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'].'</p>';
				
			$address = url($cliente['endereco'].', '.$cliente['numero'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'])
				
        ?>
           		
         		
       	<iframe width='100%' height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
        </iframe>

  		 </div>
	 </div>
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
           