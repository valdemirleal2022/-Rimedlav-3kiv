
<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}

		echo '<script>';
			echo 'function confirmacao() {';
				echo 'if ( confirm("Confirma Exclusão de Registro?") ) {';
					echo 'return true;';
				echo '}';
					echo 'javascript:window.history.go(-1)';
				echo '}';
		echo '</script>';
		
		
		if(!empty($_GET['ordemBaixar'])){
			$ordemId = $_GET['ordemBaixar'];
			$acao = "baixar";
		}

		if(isset($_POST['pesquisar_numero'])){
			$ordemId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			header('Location: painel.php?execute=suporte/ordem/ordem-baixar&ordemBaixar='.$ordemId);
		}

		if(!empty($ordemId)){
			$readordem = read('contrato_ordem',"WHERE id = '$ordemId'");
			if(!$readordem){
				header('Location: painel.php?execute=suporte/403');
			}
			foreach($readordem as $edit);
			
			if ($edit['nao_coletada'] == "1") {
				$edit['nao_coletada'] = "checked='CHECKED'";
			} else {
				$edit['nao_coletada'] = "";
		    }
			
			$contratoId = $edit['id_contrato'];
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				header('Location: painel.php?execute=suporte/403');	
			}
			foreach($readContrato as $contrato);
			
			$clienteId = $edit['id_cliente'];
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/403');	
			}
			foreach($readCliente as $cliente);
			
			
		}

		$_SESSION['url2']=$_SERVER['REQUEST_URI'];

 ?>

 <div class="content-wrapper">
     <section class="content-header">
              <h1>Ordem de Serviço</h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Contrato</a></li>
                <li><a href="#">Ordem</a></li>
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
                      <?php if($acao=='baixar') echo 'Baixando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
      	  
      	  <div class="box-header">
                    <div class="row">
                     <div class="col-xs-6 col-md-7 pull-right">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="numero"  >
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
              </div><!-- /col-md-3-->   
              
             </div><!-- /box-header-->   
      	  
     <div class="box-body">   

	<?php 

		if(isset($_POST['realizar'])){

			$cad['hora_coleta'] = mysql_real_escape_string($_POST['hora_coleta']);
			 
		   	$cad['quantidade1'] = mysql_real_escape_string($_POST['quantidade1']);
		  
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
			$cad['nao_coletada'] = mysql_real_escape_string($_POST['nao_coletada']);
			
			$cad['status'] = '13';
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_ordem',$cad,"id = '$ordemId'");	
			$interacao='Ordem Realizado';
			interacao($interacao,$contratoId);
			
			if($edit['status']<>'13'){
				//ATUALIZADO SALDO CONTRATO;
				if($contrato['saldo_etiqueta_minimo']>0){
					$con['saldo_etiqueta'] = $contrato['saldo_etiqueta'] - $cad['quantidade1'];
					update('contrato',$con,"id = '$contratoId'");	
				}
				
			}
			
			$enviarAviso='SIM';
		
			//if(!empty($edit['mensagem_baixa'])){
//				$enviarAviso='NAO';
//			}
			
			if($cliente['nao_enviar_email']=='1'){
				$enviarAviso='NAO';
			}
			if($cad['nao_coletada']=='1'){
				$enviarAviso='NAO';
			}
	
			if($enviarAviso=='SIM'){
				
				$tipoColetaId = $edit['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				$assunto = "Clean - COLETA REALIZADA " . $cliente['nome'];
				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='https://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";

				$msg .= "Prezado Cliente, <br /><br />";
				$msg .= "A Clean Ambiental informa que a coleta foi realizada com sucesso <br /><br />";
	
				$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
	  
				$msg .= "Contrato N : " . substr($contrato['controle'],0,6) . "<br />";
				$msg .= "Data Inicio do Contrato : " . converteData($contrato['inicio']) . "<br />";
				$msg .= "Cliente : " . $cliente['nome'] . "<br />";
				$msg .= "Endereço : " . $endereco . "<br />";
				$msg .= "Ordem Número : " . $edit['id'] . "<br />";
				$msg .= "Data da Coleta : " . converteData($edit['data']) . "<br />";
				$msg .= "Tipo de Coleta : " . $coleta['nome'] . "<br />";
				$msg .= "Quantidade  : " . $cad['quantidade1'] . "<br /><br />";

				$msg .= "Estamos também disponíveis no telefone 3104-2992 | 99871-0334 WhatsApp <br /><br />";
				
				$msg .= "Caso haja divergência na quantidade coletada, estamos a disposição no prazo de 48 horas para contestação, caso não ocorra contato, será considerada a quantidade coletada correta para cobrança no próximo faturamento. <br /><br />";
				
				$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
				$msg .=  "</font>";

				//$cliente['nome']='Patricia';
				//$cliente['email']='wellington@wpcsistema.com.br';
				$administrativo='atendimento@cleanambiental.com.br';	
				enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email'], $cliente['nome']);
				
				$cad2['mensagem_baixa']= date('Y/m/d H:i:s');
				update('contrato_ordem',$cad2,"id = '$ordemId'");	
			}
			
			header("Location: ".$_SESSION['url']);

		}
		 
		 
		 if(isset($_POST['transferir'])){
			
			$cad['data'] = mysql_real_escape_string($_POST['data']);
			$cad['rota'] = mysql_real_escape_string($_POST['rota']);
			$cad['hora'] = mysql_real_escape_string($_POST['hora']);
			$cad['hora_coleta'] = mysql_real_escape_string($_POST['hora_coleta']);
			 
		    $cad['tipo_coleta1'] = mysql_real_escape_string($_POST['tipo_coleta1']);
	 
			$cad['quantidade1'] = mysql_real_escape_string($_POST['quantidade1']);
	
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
			$cad['nao_coletada'] = mysql_real_escape_string($_POST['nao_coletada']);
			$cad['status'] = '14';
			 
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_ordem',$cad,"id = '$ordemId'");	
			$interacao='Ordem Transferida';
			interacao($interacao,$contratoId);
			header("Location: ".$_SESSION['url2']);
		}
		 
		 
		if(isset($_POST['cancelar'])){
			
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = '15';
			update('contrato_ordem',$cad,"id = '$ordemId'");	
			
			$interacao='Ordem cancelado';
			interacao($interacao,$contratoId);
			
			header("Location: ".$_SESSION['url']);

		}
		 
		if(isset($_POST['deletar'])){
			delete('contrato_ordem',"id = '$ordemId'");
			
			$interacao='Ordem Excluida';
			interacao($interacao,$contratoId);
			
			header("Location: ".$_SESSION['url']);
		}

	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  	
  	   		 <div class="box-header with-border">
                  <h3 class="box-title">Ordem de Serviço</h3>
             </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
          
            	<div class="form-group col-xs-12 col-md-2"> 
               		<label>Id</label>
              		<input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled /> 
                 </div><!-- /.col-md-12 -->
                 
                 <div class="form-group col-xs-12 col-md-4"> 
              		<label>Interação</label>
   					<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control" readonly  /> 
                </div><!-- /.col-md-12 -->
                
                
                <div class="form-group col-xs-12 col-md-3">  
                       <label>Status</label>
                      <select name="status"  class="form-control"  disabled>
                            <option value="">Selecione Status</option>
                                <?php 
                                    $leitura = read('contrato_status',"WHERE id");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
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
           	
                 
                <div class="form-group col-xs-12 col-md-3"> 
					<label>Data</label>
						<input name="data" type="date" value="<?php echo $edit['data'];?>" class="form-control" /> 
			 	</div><!-- /.col-md-12 -->
               
 				<div class="form-group col-xs-12 col-md-2"> 
					<label>Hora</label>
						<input name="hora" type="text" value="<?php echo $edit['hora'];?>" class="form-control"  readonly /> 
			 	</div><!-- /.col-md-12 -->
   		         
         		 <div class="form-group col-xs-12 col-md-2">  
                       <label>Rota</label>
                      <select name="rota"  class="form-control"  disabled>
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['rota'] == $mae['id']){
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
						<label>Saldo Etiqueta</label>
						<input type="text" name="saldo_etiqueta" style="text-align:right" value="<?php echo $contrato['saldo_etiqueta'];?>" class="form-control"  readonly>
				   </div> 
		
          
           	</div><!-- /.row -->
		  </div><!-- /.box-body --> 
         
          <div class="box-body">
             <div class="row">
             
             	<div class="form-group col-xs-12 col-md-2"> 
					<label>Hora Coleta</label>
						<input name="hora_coleta" type="time" value="<?php echo $edit['hora_coleta'];?>" class="form-control" autofocus  /> 
			 	</div><!-- /.col-md-12 --> 
         
           		 <div class="form-group col-xs-12 col-md-3">  
                       <label>Tipo de Coleta</label>
                      <select name="tipo_coleta1" class="form-control"  disabled>
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta1'] == $mae['id']){
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
						<label>Quantidade</label>
						<input type="text" name="quantidade1" style="text-align:right" value="<?php echo $edit['quantidade1'];?>" class="form-control" >
				   </div> 
           		
            		 <!-- NAO COLETADO-->
				   <div class="form-group col-xs-12 col-md-2">
					   <input name="nao_coletada" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['nao_coletada']; ?>  class="minimal" >
						Não Coletada
				  </div> 
             
            	 <div class="form-group col-xs-12 col-md-12"> 
             		<label>Observaçao</label>
					<input type="text" name="observacao" value="<?php echo $edit['observacao'];?>" class="form-control" >
				 </div> 
				 
			
            </div><!-- /.row -->
		  </div><!-- /.box-body -->                   
               
		  <div class="box-body">
             <div class="row">
                                     
              <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>	
				 <?php 
				
					if($acao=="baixar"){
						echo '<input type="submit" name="realizar" value="Realizar" 
							class="btn btn-primary" />';
						echo '<input type="submit" name="transferir" value="Transferir" 
							class="btn btn-success" />';
						echo '<input type="submit" name="cancelar" value="Cancelar""
							class="btn btn-danger" />' ;
						echo '<input type="submit" name="deletar" value="Deletar"  
							class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';  
					}

					if($acao=="cadastrar"){
						echo '<input type="submit" name="cadastrar" value="Cadastrar" 
						class="btn btn-primary" />';
					}

					if($acao=="atualizar"){
						echo '<input type="submit" name="atualizar" value="Atualizar" 
						class="btn btn-primary" />';
					}
					
				  ?>
				  
				 </div> <!-- /.box-footer -->
				</div><!-- /.row -->
		      </div><!-- /.box-body --> 
		      
		  </form>
		
  	</div><!-- /.box-body -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

         
           
