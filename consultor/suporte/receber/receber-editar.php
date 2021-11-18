
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
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
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

		
?><head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>

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
                  <h3 class="box-title"><small><?php echo $cliente['nome'].' || '.$cliente['email_financeiro'];?></small></h3>
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
		
			$cad['contrato_tipo']= $contrato['contrato_tipo'];
			$cad['emissao']= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
			$cad['vencimento']	= strip_tags(trim(mysql_real_escape_string($_POST['vencimento'])));
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
			
			$cad['dispensa']=strip_tags(trim(($_POST['dispensa'])));
			$cad['dispensa_data']=strip_tags(trim(($_POST['dispensa_data'])));
			$cad['dispensa_motivo']=strip_tags(trim(($_POST['dispensa_motivo'])));
		 	$cad['protocolo_correio']=strip_tags(trim(($_POST['protocolo_correio'])));
			$cad['enviar_boleto_correio'] = strip_tags(trim(($_POST['enviar_boleto_correio'])));
			update('receber',$cad,"id = '$receberId'");
				  
			// INTERAÇÃO
			$interacao='Alteração do Boleto';
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
					$interacao='Exclusão do Boleto';
				    interacao($interacao,$contratoId);
					
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div><br />';
					header("Location: ".$_SESSION['url']);
			}
			
		}
			
		if(isset($_POST['enviar'])){
			
			if($protesto == 1) {
				echo '<div class="alert alert-warning">Titulo protestado não podem enviar email!</div>';	
			}else{	
			
				$linkBoleto = URL."/cliente/painel2.php?execute=boleto/emitir-boleto&boletoId=" . $receberId;

				// calcular juros - valido ate 31/12/2017
				if (($edit['vencimento'] < date('Y-m-d'))) {
					if (($edit['valor'] < '2000')) {
						$linkBoleto = URL."/cliente/painel2.php?execute=boleto/emitir-boleto-juros&boletoId=" . $receberId;
					}
				}

				$linkNota =$edit['link'];
				$linkExtrato = URL."/cliente/painel2.php?execute=suporte/contrato/extrato-cliente&boletoId=" . $receberId;

				$assunto = "Clean Ambiental - Boleto para Pagamento " . $cliente['nome'];
				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";
				$msg .= "Ola! informamos que a cobrança abaixo se encontra disponível para pagamento: <br /><br />";

				$msg .= "Cliente : " . $cliente['nome'] . "<br />";
				$msg .= "Nosso Numero : " . $edit['id'] . "<br />";
				$msg .= "Data de Vencimento : " . converteData($edit['vencimento']) . "<br />";
				$msg .= "Valor R$  : " . $edit['valor'] . "<br />";
				$msg .= "Referente  : " . $edit['observacao'] . "<br />";
				$msg .= "Caso deseje visualizar rapidamente o boleto, a nota fiscal eletrônica ou o extrato, por gentileza clique nos links abaixo ou acesse o nosso site <br /><br />";

				$msg .= "<a href=" . $linkBoleto . ">Gerar Boleto</a> <br /><br />";

				if(!empty($edit['nota'])){
					$msg .= "<a href=" . $linkNota . ">Gerar NFe</a> <br /><br />";
				}

				$msg .= "<a href=" . $linkExtrato . ">Gerar Extrato </a> <br /><br />";

				$msg .= "Estamos também disponíveis no telefone 3104-2992 <br /><br />";
				$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
				$msg .=  "</font>";

			//	$cliente['nome']='welllington';
	//			$cliente['email']='wellington@wpcsistema.com.br';
	//		
				enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email_financeiro'], $cliente['nome']);
				if( $cliente['email_financeiro'] <> $cliente['email_financeiro2'] ){
					enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email_financeiro2'], $cliente['nome']);
				}

				$cad['interacao']= date('Y/m/d H:i:s');
				update('receber',$cad,"id = '$receberId'");

				$_SESSION['retorna'] = '<div class="alert alert-success">Sua mensagem foi enviada com sucesso!</div>';	
				header("Location: ".$_SESSION['url']);
			}
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
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
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
                          <input type="date" name="emissao" class="form-control pull-right" value="<?php echo $edit['emissao'];?>" <?php echo $readonly;?> />
                  </div><!-- /.input group -->
           </div> 
           <div class="form-group col-xs-12 col-md-4">  
                 <label>Vencimento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="vencimento" class="form-control pull-right" value="<?php echo $edit['vencimento'];?>" <?php echo $readonly;?> />
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

             
            <div class="form-group col-xs-12 col-md-1"> 
                 <label>NFe</label>
                 <input type="text" name="nota" value="<?php echo $edit['nota'];?>" class="form-control" <?php echo $readonly;?> />
     		</div>
            
              
           <div class="form-group col-xs-12 col-md-7"> 
                 <label>Link Nfe :</label>
                 <input type="text" name="link" value="<?php echo $edit['link'];?>" class="form-control" <?php echo $readonly;?> />
            </div>
            
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Data Remessa NFe</label>
                  <input type="date" name="nota_emissao" class="form-control pull-right" value="<?php echo $edit['nota_emissao'];?>"  <?php echo $readonly;?> />
               
           </div>
           <div class="form-group col-xs-12 col-md-2">  
                 <label>Emissão NFe</label>
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
            <div class="form-group col-xs-12 col-md-3">
                   <input name="dispensa" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['dispensa']; ?>  class="minimal"  <?php echo $disabled;?> >
               Dispensa de Pagamento
            </div> 
              
              <div class="form-group col-xs-3">
                <label>Motivo da Dispensa</label>
                <select name="dispensa_motivo" class="form-control" <?php echo $disabled;?> >
                  <option value="">Selecione</option>
                  <option <?php if($edit['dispensa_motivo'] == '1') echo' selected="selected"';?> value="1">Cobrança Indevida</option>
                  <option <?php if($edit['dispensa_motivo'] == '2') echo' selected="selected"';?> value="0">Por Duplicidade</option>
                 </select>
            </div><!-- /.row -->
              
              <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Recuperação</label>
                  <input type="date" name="dispensa_data" class="form-control pull-right" value="<?php echo $edit['dispensa_data'];?>"  <?php echo $readonly;?> />
               
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
					
                    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
                }
                if($acao=="deletar"){
                     echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
                }
                if($acao=="cadastrar"){
                    echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                }
                if($acao=="enviar"){
                    echo '<input type="submit" name="enviar" value="Enviar Aviso" class="btn btn-primary" />';	
                }
             ?>  
          </div> 
          
          </div><!-- /.row -->
         </div><!-- /.box-body -->
			 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
