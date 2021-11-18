 <meta charset="iso-8859-1">

<?php 
		
		 // SUSPENDE, CANCELA E PROTESTA O CONTRATO GERARANDO DA A PARTIR DA AÇÃO PARA BLOQUEAR A GERAÇÃO
		//    DAS ORDENS DE SERVÇO

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}

		if(!empty($_GET['contratoBaixarEditar'])){
			$contratoBaixarId = $_GET['contratoBaixarEditar'];
			$acao = "atualizar";
		}
		
		if(!empty($_GET['contratoId'])){
			$contratoId = $_GET['contratoId'];
			$acao = "cadastrar";
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			if(!$contrato){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			 
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			if(!$cliente){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			$acao = "cadastrar";
			
			$edit['interacao']= date('Y/m/d H:i:s');
			$edit['data'] = date('Y-m-d');
			
			$_SESSION['aba']='8';
				
		}

		if(!empty($contratoBaixarId)){
			$edit = mostra('contrato_baixa',"WHERE id = '$contratoBaixarId'");
			if(!$edit){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			
			if ($edit['falta_pagamento'] == "1") {
				$edit['falta_pagamento'] = "checked='CHECKED'";
			} else {
				$edit['falta_pagamento'] = "";
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


		if(!empty($_GET['contratoEnviar'])){
			$contratoEnviar = $_GET['contratoEnviar'];
			$edit = mostra('contrato_baixa',"WHERE id = '$contratoEnviar'");
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
			
			$consultorId = $contrato['consultor'];
			$consultor= mostra('contrato_consultor',"WHERE id = '$consultorId'");
			
			$acao = "enviar";
		}
		
		$_SESSION['aba']=1;
 ?>
 


<div class="content-wrapper">
     <section class="content-header">
         <h1>Contrato</h1>
         <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=suporte/contrato/contrato-editar">baixar</a></li>
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
	
		if(isset($_POST['cadastrar'])){
			
			$cad['id_contrato'] = $contratoId;
			$cad['id_cliente'] = $clienteId;
			$cad['tipo'] = mysql_real_escape_string($_POST['tipo']);
			$cad['data'] = mysql_real_escape_string($_POST['data']);
			$cad['motivo'] = mysql_real_escape_string($_POST['motivo']);
			$cad['motivo_cancelamento'] = mysql_real_escape_string($_POST['motivo_cancelamento']);
			$cad['falta_pagamento'] = mysql_real_escape_string($_POST['falta_pagamento']);
			$cad['interacao']= date('Y/m/d H:i:s');
			
			if( ($cad['tipo']=='5') & empty($cad['motivo_cancelamento']) ) { // 9 Contrato Cancelado
				echo '<div class="alert alert-warning">Motivo do cancelamento é obrigatório!</div>';
			}else{
			 	create('contrato_baixa',$cad);	
			}
				
			$dataApartir=$cad['data'];
			
			if($cad['tipo'] =='1'){ // 5 Contrato Ativo 
				
				$con['status']='5'; // ativo
				$interacao='Contrato Reativado';
				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);	

				header("Location: ".$_SESSION['contrato-editar']);

				$_SESSION['aba']='8';
			}
			
			if($cad['tipo'] =='2'){ // 6 Contrato Suspensos
				
				$con['status']='6';  //  suspensos
				$con['data_suspensao']=$dataApartir; 
				$interacao='Contrato Suspenso';
				
				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);	
				header("Location: ".$_SESSION['contrato-editar']);

				$_SESSION['aba']='8';
			}
			
			if($cad['tipo'] =='3'){ // 6 Contrato Rescindir
				
				$con['status']='7';  //  Rescindir
				$con['data_rescisao']=$dataApartir; 
				$interacao='Contrato Rescindido';
				
				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);	
				header("Location: ".$_SESSION['contrato-editar']);
				$_SESSION['aba']='8';
			}
	 	
			if($cad['tipo'] =='5'){ // 9 Contrato Cancelado
				
				if(!empty($cad['motivo_cancelamento'])) { // 9 Contrato Cancelado
					$con['status']='9'; //  cancelado
					$con['data_cancelamento']=$dataApartir; 

					$interacao='Contrato Cancelado';
					// atualiza status do contrato
					update('contrato',$con,"id = '$contratoId'");	
					interacao($interacao,$contratoId);
					$_SESSION['aba']='8';
					header("Location: ".$_SESSION['contrato-editar']);
				}
				 	
			}
			
			
			if($cad['tipo'] =='6'){ // 10 Ação JUDICIAL
				$con['status']=10; 
				$con['interacao']= date('Y/m/d H:i:s');
				$con['data_judicial']=$dataApartir; 
				$interacao='Ação Judicial';
				
				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);	
				header("Location: ".$_SESSION['contrato-editar']);
				$_SESSION['aba']='8';
			}
		 
			if($cad['tipo'] =='7'){ // 10 Ação JUDICIAL
				$con['status']=10; 
				$con['interacao']= date('Y/m/d H:i:s');
				$con['data_judicial']=$dataApartir; 
				$interacao='Ação Judicial Interna';
				
				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);	
				header("Location: ".$_SESSION['contrato-editar']);
				$_SESSION['aba']='8';
			}
		
			if($cad['tipo'] =='8'){ // 6 Contrato Suspensão temporaria
				
				$con['status']='19';  //  suspensos temporaria
				$con['data_suspensao']=$dataApartir; 
				$interacao='Contrato Suspenso Temporariamente';
				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);	
				header("Location: ".$_SESSION['contrato-editar']);
				$_SESSION['aba']='8';
			}
		
			if($cad['tipo'] =='9'){ // 9 Coleta Única/Coleta Avulsa  
				
				$con['status']='24';  //  Coleta Única/Coleta Avulsa   
				$interacao='Contrato Coleta Única/Coleta Avulsa';
				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);	
				header("Location: ".$_SESSION['contrato-editar']);
				$_SESSION['aba']='8';
			}
  
		}
		
		if(isset($_POST['atualizar'])){
			$cad['tipo'] = mysql_real_escape_string($_POST['tipo']);
			$cad['data'] = mysql_real_escape_string($_POST['data']);
			$cad['falta_pagamento'] = mysql_real_escape_string($_POST['falta_pagamento']);
			$cad['motivo'] = mysql_real_escape_string($_POST['motivo']);
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_baixa',$cad,"id = '$contratoBaixarId'");	
			
			$dataApartir=$cad['data'];
				
			if($cad['tipo'] =='1'){ // 5 Contrato Ativo 
				$con['status']=5;
				$interacao='Contrato Reativado';
				$con['interacao']= date('Y/m/d H:i:s');
			}
			
			if($cad['tipo'] =='2'){ // 6 Contrato Suspensos
				$con['status']=6; 
				$con['data_suspensao']=$dataApartir; 
				$con['interacao']= date('Y/m/d H:i:s');
				$interacao='Contrato Suspenso';
			}
			
			if($cad['tipo'] =='3'){ // 9 Contrato Cancelado
				$con['status']=7; 
				$con['data_rescisao']=$dataApartir; 
				$con['interacao']= date('Y/m/d H:i:s');
				$interacao='Contrato Rescindido';
			}
			
	
			if($cad['tipo'] =='5'){ // 9 Contrato Cancelado
				$con['status']=9; 
				$con['data_cancelamento']=$dataApartir; 
				$con['interacao']= date('Y/m/d H:i:s');
				$interacao='Contrato Cancelado';
			}
			
			if($cad['tipo'] =='6'){ // 9 Ação JUDICIAL
				$con['status']=10; 
				$con['interacao']= date('Y/m/d H:i:s');
				$interacao='Ação Judicial';
			}
			
		
			// atualiza status do contrato
			update('contrato',$con,"id = '$contratoId'");	
			
			interacao($interacao,$contratoId);
			
			header("Location: ".$_SESSION['contrato-editar']);
			$_SESSION['aba']='8';
		}
		
		if(isset($_POST['enviar'])){

			$assunto = "Clean Ambiental - Suspensão de Coleta " . $cliente['nome'];
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";
			$msg .= "Consta em nosso sistema  parcela(s) referente(s) aos serviços prestados pelo grupo Clean/Padrao Ambiental à sua empresa, por isso nosso sistema efetuou a suspensão automática das coletas não gerando mais a partir desta data Ordem de Serviço. <br /><br />";
							
			$msg .= "Cliente : " . $cliente['nome'] . "<br />";
			$msg .= "Contrato Numero : " . substr($contrato['controle'],0,6) . "<br /><br />";
			$msg .= "Entretanto, na eventualidade da nota fiscal já estar quitada, favor desconsiderar esta cobrança enviando o comprovante do pagamento neste mesmo e-mail, ou entrar em contato no telefone 3104-2992/ 3104-2993 <br /><br />";
			$msg .= "Após 60 dias de vencido o(s) título(s) será(o) encaminhado(s) automaticamente ao Cartório/Protesto. <br /><br />";
			$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
			$msg .=  "</font>";
			
		//	$cliente['nome']='welllington';
		//	$cliente['email_financeiro']='wellington@wpcsistema.com.br';
		
			enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email_financeiro'], $cliente['nome']);
			
			
			enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'], $cliente['nome']);
			
			//enviaEmail($assunto,$msg,MAILUSER,SITENOME,$consultor['email'], $consultor['nome']);
			
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_baixa',$cad,"id = '$contratoEnviar'");	
				
			$_SESSION['retorna'] = '<div class="alert alert-success">Sua mensagem foi enviada com sucesso!</div>';	
			header("Location: ".$_SESSION['url']);
		}

	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 				
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
                   		 <label>Intera&ccedil;ao</label>
                  	 	 <input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control"  disabled /> 
                     </div>
          
                   <div class="form-group col-xs-12 col-md-4">  
                       <label>Tipo de baixar</label>
                      <select name="tipo"  class="form-control" >
                            <option value="">Selecione tipo de baixar</option>
                                <?php 
                                    $leitura = read('contrato_baixa_tipo',"WHERE id ORDER BY id ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de baixar no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo'] == $mae['id']){
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
                 <label>A partir da Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
              			 <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                </div><!-- /.input group -->
           </div> 
				   
		 <div class="form-group col-xs-12 col-md-9"> 
					<label>Motivo </label>
					<input type="text" name="motivo" value="<?php echo $edit['motivo'];?>"  class="form-control" >
		</div> 
					
					
		   <div class="form-group col-xs-12 col-md-4">  
                       <label>Motivo do Cancelamento</label>
                      <select name="motivo_cancelamento"  class="form-control" >
                            <option value="">Selecione Motivo</option>
                                <?php 
                                    $leitura = read(' contrato_cancelamento_motivo',"WHERE id ORDER BY id ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos motivo no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['motivo_cancelamento'] == $mae['id']){
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
                   <input name="falta_pagamento" type="checkbox" value="1" <?PHP echo $edit['falta_pagamento']; ?>  class="minimal" <?php echo $disabled;?> >
            	Falta de Pagamento
              </div> 
			 
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
 
       	  <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>	
			  
				 <?php 
		
					if($acao=="atualizar"){
						 echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
					}
			  
			  		if($acao=="cadastrar"){
						 echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';
					}
					
					if($acao=="deletar"){
						 echo '<input type="submit" name="deletar" value="Excluir" class="btn btn-danger" />';
					}
			  
			  		if($acao=="enviar"){
						echo '<input type="submit" name="enviar" value="Enviar Aviso" class="btn btn-primary" />';	
					}
				?>

				 </div> <!-- /.box-footer -->
		  </form>
		  
		  
		     
    </div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
  
   

</div><!-- /.content-wrapper -->
  

