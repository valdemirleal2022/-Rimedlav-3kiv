 <?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		if(!empty($_GET['receberNota'])){
			$notaId = $_GET['receberNota'];
			$readreceber = read('receber',"WHERE nota = '$notaId'");
			if(!$readreceber){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readreceber as $edit);
			$receberId = $edit['id'];
			$acao = "atualizar";
		}
	
		if(!empty($_GET['receberEditar'])){
			$receberId = $_GET['receberEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['receberDeletar'])){
			$receberId = $_GET['receberDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['receberEnviar'])){
			$receberId = $_GET['receberEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['receberVisualizar'])){
			$receberId = $_GET['receberVisualizar'];
			$acao = "visualizar";
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}
		 
		$readreceber = read('receber',"WHERE id = '$receberId'");
		if(!$readreceber){
			header('Location: painel.php?execute=suporte/naoencontrado');	
		}
		foreach($readreceber as $edit);

		$contratoId = $edit['id_contrato'];
		
		$clienteId = $edit['id_cliente'];
		$cliente = mostra('cliente',"WHERE id = '$clienteId'");

		$contrato = mostra('contrato',"WHERE id = '$contratoId'");
		if ($contrato['enviar_boleto_correio'] == "1") {
			$edit['enviar_boleto_correio'] = "1";
    	}

		if ($edit['serasa'] == "1") {
				$edit['serasa'] = "checked='CHECKED'";
		} else {
				$edit['serasa'] = "";
		}

		if ($edit['juridico'] == "1") {
			$edit['juridico'] = "checked='CHECKED'";
		} else {
			$edit['juridico'] = "";
		}
		
		$protesto=0;
		if ($edit['protesto'] == "1") {
			$edit['protesto'] = "checked='CHECKED'";
			$protesto=1;
		} else {
			$edit['protesto'] = "";
		}

		if ($edit['recuperacao_credito'] == "1") {
			$edit['recuperacao_credito'] = "checked='CHECKED'";
		} else {
			$edit['recuperacao_credito'] = "";
		}


		if ($edit['enviar_boleto_correio'] == "1") {
			$edit['enviar_boleto_correio'] = "checked='CHECKED'";
		  } else {
			$edit['enviar_boleto_correio'] = "";
		 }

		if ($edit['dispensa'] == "1") {
			$edit['dispensa'] = "checked='CHECKED'";
		  } else {
			$edit['dispensa'] = "";
		 }

		if ($edit['refaturar'] == "1") {
			$edit['refaturar'] = "checked='CHECKED'";
		  } else {
			$edit['refaturar'] = "";
		 }

		if ($edit['refaturado'] == "1") {
			$edit['refaturado'] = "checked='CHECKED'";
		  } else {
			$edit['refaturado'] = "";
		 }

		if ($edit['desconto_autorizar'] == "1") {
			$edit['desconto_autorizar'] = "checked='CHECKED'";
		  } else {
			$edit['desconto_autorizar'] = "";
		 }
 
		
?><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/></head>

<div class="content-wrapper">
	
  <section class="content-header">
      <h1>Receita</h1>
        <ol class="breadcrumb">
        <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
        <li>Site</li>
        <li><a href="painel.php?execute=suporte/cliente/cliente-receber&clienteId=<?PHP print $clienteId; ?>">
        Cliente</a>
        </li>
        <li class="active">Receita</li>
       </ol>
  </section>
	
  <section class="content">
	  
      <div class="box box-default">
		  
            <div class="box-header with-border">
				
                <h3 class="box-title"><small><?php echo $cliente['nome'].' || '.$cliente['email_financeiro']; ?></small></h3>
				
             	<div class="box-tools">
            		<small>
						
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
								
                    </small>
          		</div><!-- /box-tools-->
				
      	  </div><!-- /.box-header -->
		  
     <div class="box-body">
     
	<?php 
		 
		 
		if(isset($_POST['atualizar'])){

				//$cad['id']=  strip_tags(trim(mysql_real_escape_string($_POST['id'])));
				$cad['contrato_tipo']= $contrato['contrato_tipo'];
				$cad['consultor']= $contrato['consultor'];
				$cad['emissao']= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
				$cad['vencimento'] = strip_tags(trim(mysql_real_escape_string($_POST['vencimento'])));
				$cad['valor']= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
				$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor']));
				$cad['desconto']= strip_tags(trim(mysql_real_escape_string($_POST['desconto'])));
				$cad['desconto'] = str_replace(",",".",str_replace(".","",$cad['desconto']));
				$cad['juros']= strip_tags(trim(mysql_real_escape_string($_POST['juros'])));
				$cad['juros'] = str_replace(",",".",str_replace(".","",$cad['juros']));
				$cad['formpag']= strip_tags(trim(mysql_real_escape_string($_POST['formpag'])));
				$cad['banco'] = strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
				$cad['interacao']= date('Y/m/d H:i:s');
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			}else{
				$cad['pagamento']=strip_tags(trim(mysql_real_escape_string($_POST['pagamento'])));
				$cad['observacao']= strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
				$cad['nota']= strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
				$cad['link']= strip_tags(trim(mysql_real_escape_string($_POST['link'])));
				$cad['nota_emissao']= strip_tags(trim(($_POST['nota_emissao'])));
				$cad['nota_data']=strip_tags(trim(mysql_real_escape_string($_POST['nota_data'])));
				$cad['remessa_data']= strip_tags(trim(($_POST['remessa_data'])));
				$cad['serasa']= strip_tags(trim(mysql_real_escape_string($_POST['serasa'])));
				$cad['serasa_data']=strip_tags(trim(mysql_real_escape_string($_POST['serasa_data'])));
				$cad['juridico']=strip_tags(trim(mysql_real_escape_string($_POST['juridico'])));
				$cad['juridico_data']=strip_tags(mysql_real_escape_string($_POST['juridico_data']));
				$cad['protesto']=strip_tags(trim(mysql_real_escape_string($_POST['protesto'])));
				$cad['protesto_data']=strip_tags(mysql_real_escape_string($_POST['protesto_data']));
				$cad['recuperacao_credito']=strip_tags(trim(($_POST['recuperacao_credito'])));
			$cad['recuperacao_credito_data']=strip_tags(trim(($_POST['recuperacao_credito_data'])));
				$cad['enviar_boleto_correio']=strip_tags((($_POST['enviar_boleto_correio'])));

				$cad['protocolo_correio']=strip_tags(trim(($_POST['protocolo_correio'])));
				$cad['enviar_boleto_correio'] = strip_tags(trim(($_POST['enviar_boleto_correio'])));

				$cad['refaturamento_observacao'] = strip_tags(trim(($_POST['refaturamento_observacao'])));
				$cad['refaturado'] = strip_tags(trim(($_POST['refaturado'])));

				$cad['refaturamento_vencimento']=strip_tags($_POST['refaturamento_vencimento']);
				$cad['refaturamento_data']=strip_tags($_POST['refaturamento_data']);
				$cad['refaturamento_data_cliente']=strip_tags($_POST['refaturamento_data_cliente']);

				$cad['dispensa']=strip_tags(trim(($_POST['dispensa'])));
				$cad['dispensa_data']=strip_tags(trim(($_POST['dispensa_data'])));
				$cad['dispensa_motivo']=strip_tags(trim(($_POST['dispensa_motivo'])));

				if($cad['serasa']==1){
					$cad['status']	= 'Serasa';
					$cad['pagamento']=null;
				}

				if($cad['juridico']==1){
					$cad['status']	= 'Juridico';
					$cad['pagamento']=null;
				}

				if($cad['protesto']==1){
					$cad['status']	= 'Protesto';
					$cad['pagamento']=null;
				}

				update('receber',$cad,"id = '$receberId'");

				// INTERAÇÃO
				$interacao='Alteração do Boleto n. '.$receberId;
				interacao($interacao,$contratoId);

				$_SESSION['retorna'] = '<div class="alert alert-success">Atualizado com sucesso!</div>';
				header("Location: ".$_SESSION['url']);
			 }
		}
	
		 
		if(isset($_POST['refaturamento'])){
			
				if(empty($edit['valor_anterior'])){
					$cad['valor_anterior']=$edit['valor'];
				}
			
				$cad['valor_anterior']=$edit['valor'];
				$cad['refaturamento_valor']=mysql_real_escape_string($_POST['refaturamento_valor']);
				$cad['refaturamento_valor'] = str_replace(",",".",str_replace(".","",$cad['refaturamento_valor']));
				$cad['refaturar']= mysql_real_escape_string($_POST['refaturar']);
				$cad['refaturamento_motivo']= strip_tags(trim(mysql_real_escape_string($_POST['refaturamento_motivo'])));
				
				$cad['interacao']= date('Y/m/d H:i:s');
			
				if(in_array('',$cad)){
					//print_r($cad);
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
				}else{
					$cad['refaturamento_vencimento']=strip_tags($_POST['refaturamento_vencimento']);
			
					$cad['refaturamento_data']= date('Y/m/d');

					$cad['refaturamento_data_cliente']=strip_tags($_POST['refaturamento_data_cliente']);
			
					$cad['refaturado'] = strip_tags(trim(($_POST['refaturado'])));
					$cad['refaturamento_observacao'] = strip_tags(trim($_POST['refaturamento_observacao']));
					$cad['refaturamento_autorizacao']= mysql_real_escape_string($_POST['refaturamento_autorizacao']);
					$cad['refaturamento_autorizacao_data']= mysql_real_escape_string($_POST['refaturamento_autorizacao_data']);
					if(empty($cad['refaturamento_autorizacao'])){
						$cad['refaturamento_autorizacao']=0;
					}
					
					if($cad['refaturamento_autorizacao']=='1'){
						$cad['refaturamento_autorizacao_data'] = date('Y/m/d');
					}
					
					if($cad['refaturamento_autorizacao']=='2'){
						$assunto = "Clean Ambiental - Refaturamento não autorizado " . $cliente['nome'];
						$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
						$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";
			
						$msg .= "Cliente : " . $cliente['nome'] . "<br />";
						$msg .= "Nosso Numero : " . $edit['id'] . "<br />";
						$msg .= "Data de Vencimento : " . converteData($edit['vencimento']) . "<br />";
						$msg .= "Valor R$  : " . converteValor($valor) . "<br /><br />";
						$msg .=  "</font>";

						$administrativo='leonardo.gregorio@cleanambiental.com.br';
						$financeiro='administrativo@cleanambiental.com.br';
					 enviaEmail($assunto,$msg,$administrativo,SITENOME,$financeiro,$cliente['nome']);
					}
					
					update('receber',$cad,"id = '$receberId'");
					// INTERAÇÃO
					$interacao='Solicitação de refaturamento do Boleto n. '.$receberId;
					interacao($interacao,$contratoId);
					$_SESSION['retorna'] = '<div class="alert alert-success">Atualizado com sucesso!</div>';
					header("Location: ".$_SESSION['url']);
			 }
		} 
		 
		 
		if(isset($_POST['dispensar'])){
			
				$cad['dispensa']=strip_tags(trim(($_POST['dispensa'])));
				$cad['dispensa_data']=strip_tags(trim(($_POST['dispensa_data'])));
				$cad['dispensa_motivo']=strip_tags(trim(($_POST['dispensa_motivo'])));
			
				$cad['interacao']= date('Y/m/d H:i:s');
			
				if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
				}else{
					$cad['dispensa_observacao'] = strip_tags(trim(($_POST['dispensa_observacao'])));
					$cad['dispensa_autorizacao']= strip_tags(trim(mysql_real_escape_string($_POST['dispensa_autorizacao'])));
					if(empty($cad['dispensa_autorizacao'])){
						$cad['dispensa_autorizacao']=0;
					}
					update('receber',$cad,"id = '$receberId'");
					// INTERAÇÃO
					$interacao='Dispensa de credido do Boleto n. '.$receberId;
					interacao($interacao,$contratoId);
					$_SESSION['retorna'] = '<div class="alert alert-success">Atualizado com sucesso!</div>';
					header("Location: ".$_SESSION['url']);
			 }
		} 
		 
		 if(isset($_POST['autorizar-desconto'])){
				
			 	$cad['desconto_autorizar']=strip_tags(trim(($_POST['desconto_autorizar'])));
			 	$cad['desconto_valor']=mysql_real_escape_string($_POST['desconto_valor']);
				$cad['desconto_valor'] = str_replace(",",".",str_replace(".","",$cad['desconto_valor']));
				$cad['desconto_data']=strip_tags(trim(($_POST['desconto_data'])));
				$cad['desconto_observacao'] = strip_tags(trim(($_POST['desconto_observacao'])));
				$cad['interacao']= date('Y/m/d H:i:s');
			
				if(in_array('',$cad)){
					
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
					
				}else{
					
					$cad['desconto_autorizacao']= strip_tags(trim(mysql_real_escape_string($_POST['desconto_autorizacao'])));

					if(empty($cad['desconto_autorizacao'])){
						$cad['desconto_autorizacao']=0;
					}
				 
					update('receber',$cad,"id = '$receberId'");
					// INTERAÇÃO
					$interacao='Autoriação de Desconto próximo Faturamento n. '.$receberId;
					interacao($interacao,$contratoId);
					$_SESSION['retorna'] = '<div class="alert alert-success">Atualizado com sucesso!</div>';
					header("Location: ".$_SESSION['url']);
			 }
		} 
			
		if(isset($_POST['deletar'])){
			
			$readDeleta = read('receber',"WHERE id = '$receberId'");
			if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('receber',"id = '$receberId'");
					
					// INTERAÇÃO
					$interacao='Exclusão do Boleto n. '.$receberId;
				    interacao($interacao,$contratoId);
					
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div><br />';
					header("Location: ".$_SESSION['url']);
			}
			
		}
			
		if(isset($_POST['enviar-email'])){
			
		 
			if($protesto == 1) {
				
				echo '<div class="alert alert-warning">Titulo protestado não podem enviar email!</div>';
				
			}else{	
				
			
			$linkBoleto = "https://www.cleansistemas.com.br/cliente/painel2.php?execute=boleto/emitir-boleto&boletoId=" . $receberId;

			$valor= $edit['valor'] + $edit['juros'] - $edit['desconto'];

			$linkNota =$edit['link'];
				
			$linkExtrato = "https://www.cleansistemas.com.br/cliente/painel2.php?execute=suporte/contrato/extrato-cliente-resumido&boletoId=" . $receberId;
		 
			$assunto = "Clean Ambiental - Boleto para Pagamento " . $cliente['nome'];
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";
				
			$msg .= "Prezado(a) Cliente,  <br /><br />";
				
			$msg .= "O envio do faturamento é realizado automaticamente através do nosso sistema, no qual disponibilizamos um link com nota fiscal, boleto e extrato. Neste anexo estamos enviando um arquivo em PDF de fácil visualização do seu boleto e da sua Nota Fiscal devidamente autorizados.<br /><br />";

			$msg .= "Cliente : " . $cliente['nome'] . "<br />";
			$msg .= "Email : " . $cliente['email_financeiro'] . "<br />";
			$msg .= "Nosso Numero : " . $edit['id'] . "<br />";
			$msg .= "Data de Vencimento : " . converteData($edit['vencimento']) . "<br />";
			$msg .= "Valor R$  : " . converteValor($valor) . "<br /><br />";
	
			$msg .= "<a href=" . $linkBoleto . ">Gerar Boleto</a> <br />";
				
			if(!empty($receber['nota'])){
			   $msg .= "<a href=" . $linkNota . ">Gerar NFe</a> <br /><br />";
			}
			$msg .= "<a href=" . $linkExtrato . ">Gerar Extrato </a> <br /><br />";
		 
		 
			$msg .= "Informamos também  que disponibilizamos através do portal do atendimento ao cliente:<br /><br />";
			$msg .= "1.	https://www.cleansistemas.com.br/<br />";
	 		$msg .= "2.	No canto inferior direito clicar em LOGIN <br />";
			$msg .= "3.	Clicar em ESQUECEU SUA SENHA? <br />";
			$msg .= "4.	No passo seguinte informar o seu e-mail cadastrado em nossos sistemas e clicar em ENVIAR <br /> <br />";
			
			$msg .= "Automaticamente, será enviado para seu e-mail uma nova senha de acesso aos dados do seu cadastro, tais como: Nota fiscal e boleto.  <br />";
			$msg .= "Salientamos ainda, que o prazo para discordância da nota fiscal são de 48 horas a partir deste recebimento. <br /><br />";
			$msg .= "Obs. Após 60 dias de vencido o(s) título(s) será(o) encaminhado(s) automaticamente ao Cartório/Protesto. <br /><br />";
			
			$msg .= "Estamos também disponíveis no telefone 3104-2992<br /><br />";
			
			$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
			$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
			$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";

			$msg .= "Mensagem enviada automaticamente pelo sistema (I)! 8 <br /><br />";
			$msg .=  "</font>";
	 
			$administrativo='administrativo@cleanambiental.com.br';
			enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email_financeiro'], $cliente['nome']);

		 
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['usuario']	=  $_SESSION['autUser']['nome'];
			update('receber',$cad,"id = '$receberId'");

			// INTERAÇÃO
			$interacao='Link do boleto enviado por email n. '.$receberId;;
			interacao($interacao,$contratoId);
					
			$_SESSION['retorna'] = '<div class="alert alert-success">Sua mensagem foi enviada com sucesso!</div>';
				
			header("Location: ".$_SESSION['url']);	
				
				//  TESTE DE ENVIIO DE EMAIL
				//$cliente['email']='wellington@wpcsistema.com.br';
				//enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'],$cliente['nome']);
				//$cliente['email']='wellington@toyamadedetizadora.com.br';
				//enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'],$cliente['nome']);

				//echo "Host => ". MAILHOST ."<br>";
				//echo "Email => ". MAILUSER ."<br>";
				//echo "Senha => ". MAILPASS ."<br>";
				//echo "Porta => ". MAILPORT ."<br>";
				//echo "Teste n.  => 8<br>";
				//		
				//$mailReturn =enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email'],$cliente['nome']);;
				//echo "O mail foi enviado? => "; var_dump($mailReturn);
				
			}
		}
		
		 
		 if(isset($_POST['desfazer'])){
			
				$cad['pagamento'] 	= null;
			  	$cad['status'] 		= 'Em Aberto';
			  	$cad['interacao']= date('Y/m/d H:i:s');
				$cad['usuario']	=  $_SESSION['autUser']['nome'];
				update('receber',$cad,"id = '$receberId'");
					
				// INTERAÇÃO
				$interacao='Defazer Baixa do Lançamento n. '.$receberId;
				interacao($interacao,$contratoId);

				$_SESSION['cadastro']='<div class="alert alert-success">Baixado com sucesso</div><br />';
				header("Location: ".$_SESSION['url']);
			 
			}
		
	?>
	
   <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
   
    	<div class="box-header with-border">
                <h3 class="box-title">Recebimento</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
    
  			 <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  class="form-control" readonly/>
             </div> 
             <div class="form-group col-xs-12 col-md-2">  
               	<label>Interação</label>
   				<input name="orc_resposta" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div> 
             <div class="form-group col-xs-12 col-md-2">  
            	<label>Status</label>
                <input name="status" type="text" value="<?php echo $edit['status'];?>" readonly class="form-control" /> 
            </div> 
             <div class="form-group col-xs-12 col-md-3">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $edit['usuario'];?>" readonly class="form-control" /> 
            </div> 
            <div class="form-group col-xs-12 col-md-3">  
                <label>Print</label>
   				<input name="imprimir" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['imprimir']));?>" class="form-control" readonly  /> 
            </div> 
            
      		 <div class="form-group col-xs-12 col-md-4">  
                 <label>Emissão</label>
                  <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="date" name="emissao" class="form-control pull-right" value="<?php echo $edit['emissao'];?>" <?php echo $readonly;?>  />
                  </div><!-- /.input group -->
           </div> 
				  
          <div class="form-group col-xs-12 col-md-4">  
                 <label>Vencimento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="vencimento" class="form-control pull-right" value="<?php echo $edit['vencimento'];?>" <?php 
							  if($edit['retorno']=='Confirmado'){
								//echo 'readonly';
							  }elseif($edit['retorno']=='Alterado'){
								//echo 'readonly';
							  };?> 
							  
							  />
                 </div><!-- /.input group -->
           </div>
           
            <div class="form-group col-xs-12 col-md-4">  
                 <label>Pagamento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="pagamento" class="form-control pull-right" value="<?php echo $edit['pagamento'];?>" <?php echo $readonly;?> />
                 </div><!-- /.input group -->
           </div>

           
           <div class="form-group col-xs-12 col-md-4">  
          		<label>Valor</label>
               <div class="input-group">
                      	<div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right" value="<?php echo converteValor($edit['valor']);?>" <?php echo $readonly;?> />
                 </div><!-- /.input group -->
           </div>
           
           
            <div class="form-group col-xs-12 col-md-4">  
          		<label>Desconto</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="desconto" class="form-control pull-right" value="<?php echo converteValor($edit['desconto']);?>" <?php echo $readonly;?> />
                 </div><!-- /.input group -->
           </div>
           
           
            <div class="form-group col-xs-12 col-md-4">  
          		<label>Juros/Taxas</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="juros" class="form-control pull-right" value="<?php echo converteValor($edit['juros']);?>" <?php echo $readonly;?> />
                 </div><!-- /.input group -->
           </div>

          <div class="form-group col-xs-12 col-md-6"> 
              <label>Banco</label>
                <select name="banco" class="form-control" <?php echo $readonly;?> >
                    <option value="">Selecione Banco</option>
                    <?php 
                        $readBanco = read('banco',"WHERE id");
                        if(!$readBanco){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($edit['banco'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div>

         <div class="form-group col-xs-12 col-md-6"> 
            <label>Forma de Pagamento </label>
                <select name="formpag" class="form-control" <?php echo $readonly;?> >
                    <option value="">Forma de Pagamento</option>
                    <?php 
                        $readFormpag = read('formpag',"WHERE id");
                        if(!$readFormpag){
                            echo '<option value="">Não temos Forma de Pagamento no momento</option>';	
                        }else{
                            foreach($readFormpag as $mae):
							   if($edit['formpag'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div>
   
            <div class="form-group col-xs-12 col-md-6"> 
                 <label>Observação</label>
                 <input type="text" name="observacao" value="<?php  echo $edit['observacao'];?>" class="form-control" <?php echo $readonly;?> />
     	    </div>
     	    
           
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Data Remessa Boleto</label>
                  <input type="date" name="remessa_data" class="form-control pull-right" value="<?php echo $edit['remessa_data'];?>"  <?php echo $readonly;?> />
               
             </div>
            
              
             <div class="form-group col-xs-12 col-md-2"> 
                 <label>Remessa</label>
                 <input type="text" name="remessa" value="<?php  echo $edit['remessa'];?>" class="form-control" disabled />
     	     </div>
            
             <div class="form-group col-xs-12 col-md-2"> 
                 <label>Retorno</label>
                 <input type="text" name="retorno" value="<?php  echo $edit['retorno'];?>" class="form-control" disabled />
     	    </div>

             
            <div class="form-group col-xs-12 col-md-2"> 
                 <label>NFe</label>
                 <input type="text" name="nota" value="<?php echo $edit['nota'];?>" class="form-control" <?php echo $readonly;?> />
     		</div>
            
              
           <div class="form-group col-xs-12 col-md-6"> 
                 <label>Link Nfe :</label>
                 <input type="text" name="link" value="<?php echo $edit['link'];?>" class="form-control" <?php echo $readonly;?> />
            </div>
            
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Remessa </label>
                  <input type="date" name="nota_emissao" class="form-control pull-right" value="<?php echo $edit['nota_emissao'];?>"  <?php echo $readonly;?> />
               
           </div>
           <div class="form-group col-xs-12 col-md-2">  
                 <label>Emissão </label>
                  <input type="date" name="nota_data" class="form-control pull-right" value="<?php echo $edit['nota_data'];?>"  <?php echo $readonly;?> />
               
           </div>
           
           
           <div class="form-group col-xs-12 col-md-3">
                   <input name="enviar_boleto_correio" type="checkbox" value="1" <?PHP echo $edit['enviar_boleto_correio']; ?>  class="minimal" <?php echo $disabled;?> >
            	 Enviar Boleto pelo Correio
              </div> 
              
               <div class="form-group col-xs-12 col-md-4">  
                 <label>Protocolo Correio</label>
                  <input type="text" name="protocolo_correio" class="form-control pull-right" value="<?php echo $edit['protocolo_correio'];?>"  <?php echo $readonly;?> />
               
           </div>
           
            
      		</div><!-- /.row -->
        </div><!-- /.box-body -->
           
           <div class="box-header with-border">
                <h3 class="box-title">Serasa/Juridico/Protesto</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
           
            <!-- SERASA-->
            <div class="form-group col-xs-12 col-md-1">
                   <input name="serasa" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['serasa']; ?>  class="minimal"  <?php echo $disabled;?> >
                Serasa
            </div> 
               
            <div class="form-group col-xs-12 col-md-3">  
                 <label>Entrada no Serada</label>
                  <input type="date" name="serasa_data" class="form-control pull-right" value="<?php echo $edit['serasa_data'];?>"  <?php echo $readonly;?> />
               
           </div>
           
            <!-- JURIDICO-->
            <div class="form-group col-xs-12 col-md-1">
                   <input name="juridico" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['juridico']; ?>  class="minimal"  <?php echo $disabled;?> >
                Jurídico
            </div> 
               
            <div class="form-group col-xs-12 col-md-3">  
                 <label>Entrada no Juridico</label>
                  <input type="date" name="juridico_data" class="form-control pull-right" value="<?php echo $edit['juridico_data'];?>"  <?php echo $readonly;?> />
               
           </div>
				  
			  <!-- PROTESTO-->
            <div class="form-group col-xs-12 col-md-1">
                   <input name="protesto" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['protesto']; ?>  class="minimal"  <?php echo $disabled;?> >
                Protesto
            </div> 
               
            <div class="form-group col-xs-12 col-md-3">  
                 <label>Entrada no Protesto</label>
                  <input type="date" name="protesto_data" class="form-control pull-right" value="<?php echo $edit['protesto_data'];?>"  <?php echo $readonly;?> />
               
           </div>
           
            </div><!-- /.row -->
           </div><!-- /.box-body -->
           
           
            <div class="box-header with-border">
                <h3 class="box-title">Recuperação de Crédito</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
           
            <!-- SERASA-->
            <div class="form-group col-xs-12 col-md-3">
                   <input name="recuperacao_credito" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['recuperacao_credito']; ?>  class="minimal"  <?php echo $disabled;?> >
               Recuperação de Crédito
            </div> 
               
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Data da Recuperação</label>
                  <input type="date" name="recuperacao_credito_data" class="form-control pull-right" value="<?php echo $edit['recuperacao_credito_data'];?>"  <?php echo $readonly;?> />
               
           </div>
           
             </div><!-- /.row -->
           </div><!-- /.box-body -->
             
     <div class="box-header with-border">
                <h3 class="box-title">Dispensa de Pagamento</h3>
    </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
           
           <!-- SERASA-->
            <div class="form-group col-xs-12 col-md-4">
                   <input name="dispensa" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['dispensa']; ?>  class="minimal"  <?php echo $disabled;?> >
               Dispensa de Pagamento
            </div> 
				  
			<div class="form-group col-xs-12 col-md-4"> 
              <label>Motivo</label>
                <select name="dispensa_motivo" class="form-control" <?php echo $readonly;?> >
                    <option value="">Selecione Motivo</option>
                    <?php 
                        $readBanco = read('motivo_dispensa',"WHERE id");
                        if(!$readBanco){
                            echo '<option value="">Não temos motivo no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($edit['dispensa_motivo'] == $mae['id']){
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
                 <label>Data da Dispensa</label>
                  <input type="date" name="dispensa_data" class="form-control pull-right" value="<?php echo $edit['dispensa_data'];?>"  <?php echo $readonly;?> />
               
           </div>

				
		    <div class="form-group col-xs-12 col-md-9"> 
                 <label>Observação</label>
                 <input type="text" name="dispensa_observacao" value="<?php  echo $edit['dispensa_observacao'];?>" class="form-control" <?php echo $readonly;?> />
     	    </div>
				  
				  
			<div class="form-group col-xs-3">
				 
               	 <label>Autorização</label>
				   <?php
						if($_SESSION['autUser']['nivel']==5){	//Gerencial 
							echo '<select name="dispensa_autorizacao" class="form-control" >';
						}else{
							echo '<select name="dispensa_autorizacao" class="form-control"  disabled>';
						}
					?>	
				 
                  <option value="">Selecione Autorização</option>
				
                  <option <?php if($edit['dispensa_autorizacao'] == '1') echo' selected="selected"';
						  ?> value="1" >Autorizado </option>
                  <option <?php if($edit['dispensa_autorizacao'] == '2') echo' selected="selected"';
						  ?> value="2">Não Autorizado</option>
				   <option <?php if($edit['dispensa_autorizacao'] == '0') echo' selected="selected"';
						   ?> value="">Aguardando</option>
				 
                 </select>
				  
            </div>  
  
     </div><!-- /.row -->
  </div><!-- /.box-body -->
	   
	   
	     <div class="box-header with-border">
                <h3 class="box-title">Refaturamento</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
           
           <!--  Refaturamento-->
            <div class="form-group col-xs-12 col-md-4">
                   <input name="refaturar" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['refaturar']; ?>  class="minimal"  <?php echo $disabled;?> >
                refaturar
            </div> 
   
          <div class="form-group col-xs-12 col-md-4">  
          		<label>Valor Atual</label>
               <div class="input-group">
                      	<div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text"  class="form-control pull-right" value="<?php echo converteValor($edit['valor']);?>"  readonly />
                 </div><!-- /.input group -->
			  
           </div> <div class="form-group col-xs-12 col-md-4">  
				  
			  <label>Valor do Refaturamento</label>
				   <div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-credit-card"></i>
							  </div>
							  <input type="text" name="refaturamento_valor" class="form-control pull-right" value="<?php echo converteValor($edit['refaturamento_valor']);?>" <?php echo $readonly;?> />
					 </div><!-- /.input group -->'
           </div>
				  
			<div class="form-group col-xs-12 col-md-4">  
                 <label>Data Solicitação Cliente</label>
                  <input type="date" name="refaturamento_data_cliente" class="form-control pull-right" value="<?php echo $edit['refaturamento_data_cliente'];?>"  <?php echo $readonly;?> />
               
           </div>	
				  
			<div class="form-group col-xs-12 col-md-4">  
                 <label>Data do Refaturamento</label>
                  <input type="date" name="refaturamento_data" class="form-control pull-right" value="<?php echo $edit['refaturamento_data'];?>"  readonly />
               
           </div>	
				  
		   <div class="form-group col-xs-12 col-md-4">  
                 <label>Novo Vencimento</label>
                  <input type="date" name="refaturamento_vencimento" class="form-control pull-right" value="<?php echo $edit['refaturamento_vencimento'];?>"  <?php echo $readonly;?> />
               
           </div>	
 
			<div class="form-group col-xs-12 col-md-4"> 
              <label>Motivo</label>
                <select name="refaturamento_motivo" class="form-control" <?php echo $readonly;?> >
                    <option value="">Selecione Motivo</option>
                    <?php 
                        $readBanco = read('motivo_refaturamento',"WHERE id");
                        if(!$readBanco){
                            echo '<option value="">Não temos motivo no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($edit['refaturamento_motivo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div>
				  
				  
			 <div class="form-group col-xs-12 col-md-8"> 
                 <label>Observação</label>
                 <input type="text" name="refaturamento_observacao" value="<?php  echo $edit['refaturamento_observacao'];?>" class="form-control" <?php echo $readonly;?> />
     	    </div>
				  
		  
			 <div class="form-group col-xs-5">
				 
               	 <label>Autorização de Refaturamento</label>
				   <?php
						if($_SESSION['autUser']['nivel']==5){	//Gerencial 
							echo '<select name="refaturamento_autorizacao" class="form-control" >';
						}else{
							echo '<select name="refaturamento_autorizacao" class="form-control"  disabled>';
						}
					?>	
				 
                  <option value="">Selecione Autorização</option>
                  <option <?php if($edit['refaturamento_autorizacao'] == '1') echo' selected="selected"';?> value="1" >Autorizado </option>
                  <option <?php if($edit['refaturamento_autorizacao'] == '2') echo' selected="selected"';?> value="2">Não Autorizado</option>
				   <option <?php if($edit['refaturamento_autorizacao'] == '3') echo' selected="selected"';?> value="3">Aguardando</option>
				 
                 </select>
				  
           </div>  
				
		   <div class="form-group col-xs-12 col-md-4">  
              <label>Data da Autorização</label>
              <input type="date" name="refaturamento_autorizacao_data" class="form-control pull-right" value="<?php echo $edit['refaturamento_autorizacao_data'];?>"  readonly />
           </div>	
				
			<div class="form-group col-xs-12 col-md-3">
               <input name="refaturado" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['refaturado']; ?>  class="minimal"  <?php echo $disabled;?> >
               Refaturado
            </div> 

        </div><!-- /.row -->
       </div><!-- /.box-body -->
		  
		  
	<div class="box-header with-border">
   	 <h3 class="box-title">Desconto Próximo Faturamento</h3>
    </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
	
				  
				<div class="form-group col-xs-12 col-md-2">
					   <input name="desconto_autorizar" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['desconto_autorizar']; ?>  class="minimal"  <?php echo $disabled;?> >
				   Desconto Próximo Faturamento
				</div> 
				  
				 <div class="form-group col-xs-12 col-md-2">  
				  	<label>Valor do Desconto</label>
					 <div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-credit-card"></i>
						 </div>
						 <input type="text" name="desconto_valor" class="form-control pull-right" value="<?php echo converteValor($edit['desconto_valor']);?>" <?php echo $readonly;?> />
					</div><!-- /.input group -->'
			   </div>
 
				<div class="form-group col-xs-12 col-md-2">  
					 <label>Data próximo Faturamento</label>
					  <input type="date" name="desconto_data" class="form-control pull-right" value="<?php echo $edit['desconto_data'];?>"  <?php echo $readonly;?> />
				</div>
				
				<div class="form-group col-xs-6">

					 <label>Autorização</label>
					   <?php
							if($_SESSION['autUser']['nivel']==5){	//Gerencial 
								echo '<select name="desconto_autorizacao" class="form-control" >';
							}else{
								echo '<select name="desconto_autorizacao" class="form-control"  disabled>';
							}
						?>	

					  <option value="">Selecione Autorização</option>

					  <option <?php if($edit['desconto_autorizacao'] == '1') echo' selected="selected"';
							  ?> value="1" >Autorizado </option>
					  <option <?php if($edit['desconto_autorizacao'] == '2') echo' selected="selected"';
							  ?> value="2">Não Autorizado</option>
					   <option <?php if($edit['desconto_autorizacao'] == '0') echo' selected="selected"';
							   ?> value="">Solicitar Autorização</option>

					 </select>

				</div>  
				
				<div class="form-group col-xs-12 col-md-12"> 
					 <label>Observação</label>
					 <input type="text" name="desconto_observacao" value="<?php  echo $edit['desconto_observacao'];?>" class="form-control" <?php echo $readonly;?> />
				</div>
				  

     </div><!-- /.row -->
  </div><!-- /.box-body -->
            
		<div class="box-body">
          <div class="row">
              
            <div class="box-footer">
          	 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
           	  <?php 
				
                if($acao=="atualizar"){
					
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
					
					echo '<input type="submit" name="refaturamento" value="Refaturar" class="btn btn-success" />';
					
				if($_SESSION['autUser']['nivel']==5 ||  $_SESSION['autUser']['nivel']==2){	//Gerencial 	
					echo '<input type="submit" name="dispensar" value="Dispensar" class="btn btn-success" />';
				}
					echo '<input type="submit" name="autorizar-desconto" value="Desconto" class="btn btn-success" />';
					
					 echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
					
					
                   if($_SESSION['autUser']['nivel']==5){	//Gerencial 
				 
						echo '<input type="submit" name="desfazer" value="Desfazer Baixa" class="btn btn-success" />';	
					   
					    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
					 
					}
                }
				
				if($_SESSION['autUser']['nivel']==5){	//Gerencial 
				
					if($acao=="deletar"){
						 echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
					}
					 
				}
                if($acao=="cadastrar"){
                    echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                }
				
                if($acao=="enviar"){
                    echo '<input type="submit" name="enviar-email" value="Enviar Aviso" class="btn btn-success" />';	
                }
				
             ?>  
          </div> 
          
          </div><!-- /.row -->
         </div><!-- /.box-body -->
			 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->

</section>

<section class="content">
  	<div class="box box-warning">
     	 <div class="box-body">
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/receber/receber-negociacao-editar&receberId=<?PHP echo $receberId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small> Negociação  </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('receber_negociacao',"WHERE id AND id_receber = '$receberId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
						<td align="center">Motivo</td>
						<td align="center">Data</td>
						<td align="center">Previsão Pag</td>
						<td align="center">Observação</td>
						<td align="center">Solução</td>
						<td colspan="2" align="center">Gerenciar</td>
					

                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
					  
                              echo '<td>'.$mostra['id'].'</td>';
	
								$motivoId = $mostra['id_motivo'];
								$motivo = mostra('recebe_negociacao_motivo',"WHERE id ='$motivoId'");
								echo '<td>'.$motivo['nome'].'</td>';

								echo '<td>'.converteData($mostra['data']).'</td>';
					  			echo '<td>'.converteData($mostra['previsao_pagamento']).'</td>';


								echo '<td>'.substr($mostra['observacao'],0,25).'</td>';

								$solucaoId = $mostra['id_solucao'];
								$solucao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId'");
								echo '<td>'.$solucao['nome'].'</td>';
					  
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
 	
		 </div>
      </div>
	
</div><!-- /.content-wrapper -->
