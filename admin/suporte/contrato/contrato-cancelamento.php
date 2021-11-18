	<?php 
		
		 // SUSPENDE, CANCELA E PROTESTA O CONTRATO GERARANDO DA A PARTIR DA AÇÃO PARA BLOQUEAR A GERAÇÃO
		//    DAS ORDENS DE SERVÇO

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}

			
		if(!empty($_GET['contratoId'])){
			
			$contratoId = $_GET['contratoId'];
			$acao = "cadastrar";
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			if(!$contrato){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			 
			$consultorId = $contrato['consultor'];
			$pos_vendaId = $contrato['pos_venda'];
			
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			if(!$cliente){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			$acao = "cadastrar";
			
			$edit['interacao']= date('Y/m/d H:i:s');
			$edit['data_solicitacao'] = date('Y-m-d');
			
			$_SESSION['aba']='8';
				
		}

		if(!empty($_GET['contratoCancelamento'])){
			$contratoBaixarId = $_GET['contratoCancelamento'];
			$acao = "atualizar";
			$edit = mostra('contrato_cancelamento',"WHERE id = '$contratoBaixarId'");
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
			
		}

				
		$_SESSION['aba']=1;
 ?>
 


<div class="content-wrapper">
     <section class="content-header">
         <h1>Contrato</h1>
         <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Contrato</a></li>
            <li><a href="painel.php?execute=suporte/contrato/contrato-cancelamento">Cancelamento</a></li>
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
			
		 
			$cad['status'] = 'Aguardando';
			$cad['id_contrato'] = $contratoId;
			$cad['id_cliente'] = $clienteId;
			$cad['id_consultor'] = $consultorId;
			$cad['pos_venda'] = $pos_vendaId;
			
			$cad['data_solicitacao'] = date('Y-m-d');
			$cad['data_encerramento'] = mysql_real_escape_string($_POST['data_encerramento']);
			$cad['tipo_contato'] = mysql_real_escape_string($_POST['tipo_contato']);
			
			$cad['valor_rescisorio']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_rescisorio'])));
			$cad['valor_rescisorio'] = str_replace(",",".",str_replace(".","",$cad['valor_rescisorio']));
			
			$cad['periodo_rescisorio'] = mysql_real_escape_string($_POST['periodo_rescisorio']);
			$cad['coleta'] = mysql_real_escape_string($_POST['coleta']);
			
			$cad['periodo_rescisorio_tempo'] = mysql_real_escape_string($_POST['periodo_rescisorio_tempo']);
			
		$cad['comodato_equipamento']=mysql_real_escape_string($_POST['comodato_equipamento']);
		$cad['comodato_quantidade'] = mysql_real_escape_string($_POST['comodato_quantidade']);
		$cad['comodato_retirada'] = mysql_real_escape_string($_POST['comodato_retirada']);
			
			$cad['motivo'] = mysql_real_escape_string($_POST['motivo']);
			$cad['reclamacao'] = mysql_real_escape_string($_POST['reclamacao']);
			
			$cad['reclamacao_descricao'] = mysql_real_escape_string($_POST['reclamacao_descricao']);
			$cad['observacao_comercial'] = mysql_real_escape_string($_POST['observacao_comercial']);
			
			$cad['recuperada'] = mysql_real_escape_string($_POST['recuperada']);
			
			$cad['interacao']= date('Y/m/d H:i:s');
			
			if(empty($cad['motivo'])){
				echo '<div class="alert alert-warning">O motivo é obrigatório!</div>'.'<br>';
			}else{
				
				create('contrato_cancelamento',$cad);
			
				$con['interacao']= date('Y/m/d H:i:s');
				$interacao='Solicitação de Cancelamento - Aguardando';

				// atualiza status do contrato
				update('contrato',$con,"id = '$contratoId'");	
				interacao($interacao,$contratoId);

				header("Location: ".$_SESSION['contrato-editar']);

				$_SESSION['aba']='8';
			}
			
		}
		
		if(isset($_POST['atualizar'])){

			//$cad['data_solicitacao'] = mysql_real_escape_string($_POST['data_solicitacao']);
			$cad['data_encerramento'] = mysql_real_escape_string($_POST['data_encerramento']);
			$cad['tipo_contato'] = mysql_real_escape_string($_POST['tipo_contato']);
			
			$cad['periodo_rescisorio'] = mysql_real_escape_string($_POST['periodo_rescisorio']);
			$cad['coleta'] = mysql_real_escape_string($_POST['coleta']);
			
			$cad['periodo_rescisorio_tempo'] = mysql_real_escape_string($_POST['periodo_rescisorio_tempo']);
			
			
			$cad['valor_rescisorio']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_rescisorio'])));
			$cad['valor_rescisorio'] = str_replace(",",".",str_replace(".","",$cad['valor_rescisorio']));
			
		$cad['comodato_equipamento']=mysql_real_escape_string($_POST['comodato_equipamento']);
		$cad['comodato_quantidade'] = mysql_real_escape_string($_POST['comodato_quantidade']);
		$cad['comodato_retirada'] = mysql_real_escape_string($_POST['comodato_retirada']);
			
			$cad['motivo'] = mysql_real_escape_string($_POST['motivo']);
			$cad['reclamacao'] = mysql_real_escape_string($_POST['reclamacao']);
			
			$cad['reclamacao_descricao'] = mysql_real_escape_string($_POST['reclamacao_descricao']);
			$cad['observacao_comercial'] = mysql_real_escape_string($_POST['observacao_comercial']);
			
			$cad['recuperada'] = mysql_real_escape_string($_POST['recuperada']);
			
			$cad['interacao']= date('Y/m/d H:i:s');
			
			if( !empty($cad['recuperada']) ){
				$cad['status'] = 'OK';
			}
			
			if(empty($cad['motivo'])){
				echo '<div class="alert alert-warning">O motivo é obrigatório!</div>'.'<br>';
			}else{
				update('contrato_cancelamento',$cad,"id = '$contratoBaixarId'");	
				header("Location: ".$_SESSION['contrato-editar']);
				header("Location: ".$_SESSION['url']);
				$_SESSION['aba']='8';
			}
		}
		
		
		if(isset($_POST['enviar'])){
		
				$consultorId = $contrato['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");

				$assunto  = "Solicitação de Cancelamento : " . $contrato['id'];

				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

				$msg .= "Solicitação de Cancelamento<br/><br />";
			
				$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

				$msg .= "Contrato N&deg; : " . $contrato['id'] . "<br />";
				$msg .= "Nome : " . $cliente['nome'] . "<br />";
				$msg .= "Endereço : " . $endereco . "<br />";
				$msg .= "Bairro : " . $cliente['bairro'] . "<br />";
				
				$msg .= "Email : " . $cliente['email'] . "<br />";
				$msg .= "Telefone : " . $cliente['telefone'] . "<br />";

				$msg .= "Data do Solicitação : " . converteData($edit['data_solicitacao']) . "<br />";

				$msg .= "Descrição da solicitação : " . nl2br($edit['reclamacao_descricao']) . "<br /><br />";

				$msg .= SITENOME . "<br />";
				$msg .= "Consultor : " . $consultor['nome'] . "<br />";
				$msg .= "Email : " . $consultor['email'] . "<br />";
				$msg .= "Telefone : " . $consultor['telefone'] . "<br />";
				$msg .= "Data : " . date('d/m/Y'). "<br /><br />";
		
				$msg .=  "</font>";

			enviaEmail($assunto,$msg,MAILUSER,SITENOME,$consultor['email'],$consultor['nome']);
			header("Location: ".$_SESSION['url']);
		}
		
		if(isset($_POST['deletar'])){
			delete('contrato_cancelamento',"id = '$contratoBaixarId'");
			
			$interacao='Exclusão Solicitação de Cancelamento';
			interacao($interacao,$contratoId);	
			
			header("Location: ".$_SESSION['url']);
		}

	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 				
                <div class="box-header with-border">
                  <h3 class="box-title">Cancelamento do Contrato</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                
                <div class="row">
                    
                <div class="form-group col-xs-12 col-md-2">  
                     <label>Id</label>
                      <input name="id"  class="form-control" type="text" value="<?php echo $edit['id'];?>" disabled/>
                  </div>
                     
                  <div class="form-group col-xs-12 col-md-2">  
                     <label>Status</label>
                      <input name="status"  class="form-control" type="text" value="<?php echo $edit['status'];?>" disabled/>
                  </div>
                     
                 <div class="form-group col-xs-12 col-md-2">  
                   		 <label>Interaçao</label>
                  	 	 <input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control"  disabled /> 
                 </div>
        
                     
              <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Solicitação</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
              			 <input type="date" name="data_solicitacao" class="form-control pull-right" value="<?php echo $edit['data_solicitacao'];?>" readonly />
                </div><!-- /.input group -->
           	 </div> 
           	 
           	 <div class="form-group col-xs-12 col-md-3">  
                 <label>Data de Encerramento</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
              			 <input type="date" name="data_encerramento" class="form-control pull-right" value="<?php echo $edit['data_encerramento'];?>"/>
                </div><!-- /.input group -->
           	 </div> 
           	 
           	 <div class="form-group col-xs-12 col-md-2"> 
                <label>Tipo Contato</label>
                <select name="tipo_contato" class="form-control"> 
                  <option value="">Selecione</option>
                  <option <?php if($edit['tipo_contato'] == '1') echo' selected="selected"';?> value="1">Telefone</option>
                  <option <?php if($edit['tipo_contato'] == '2') echo' selected="selected"';?> value="2">Visita</option>
                   <option <?php if($edit['tipo_contato'] == '3') echo' selected="selected"';?> value="3">E-mail</option>
                 </select>
            </div><!-- /.row --> 
            
             <div class="form-group col-xs-12 col-md-2"> 
                <label>Período Rescisório</label>
                <select name="periodo_rescisorio" class="form-control"> 
                  <option value="">Selecione</option>
                  <option <?php if($edit['periodo_rescisorio'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['periodo_rescisorio'] == '2') echo' selected="selected"';?> value="2">Não</option>
                 </select>
            </div><!-- /.row --> 
					
			 <div class="form-group col-xs-12 col-md-2"> 
                <label>Coleta</label>
                <select name="coleta" class="form-control"> 
                  <option value="">Selecione</option>
                  <option <?php if($edit['coleta'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['coleta'] == '2') echo' selected="selected"';?> value="2">Não</option>
                 </select>
            </div><!-- /.row --> 
            
              <div class="form-group col-xs-12 col-md-2"> 
                <label>Período</label>
                <select name="periodo_rescisorio_tempo" class="form-control"> 
                  <option value="">Selecione</option>
                  <option <?php if($edit['periodo_rescisorio_tempo'] == '1') echo' selected="selected"';?> value="1">30</option>
                  <option <?php if($edit['periodo_rescisorio_tempo'] == '2') echo' selected="selected"';?> value="2">60</option>
                  <option <?php if($edit['periodo_rescisorio_tempo'] == '3') echo' selected="selected"';?> value="3">90</option>
                 </select>
            </div><!-- /.row --> 
					
				<div class="form-group col-xs-12 col-md-4"> 
						<label>Valor Rescisório</label>
						<input type="text" name="valor_rescisorio" style="text-align:right" value="<?php echo converteValor($edit['valor_rescisorio']);?>" class="form-control" >
				   </div> 
            
            
            <div class="form-group col-xs-12 col-md-5"> 
                <label>Container em Comodato</label>
                <select name="comodato_equipamento" class="form-control"> 
                  <option value="">Selecione</option>
                  <option <?php if($edit['comodato_equipamento'] == '1') echo' selected="selected"';?> value="1">Container 1.2m³</option>
                  <option <?php if($edit['comodato_equipamento'] == '2') echo' selected="selected"';?> value="2">Container 1.0m³</option>
                   <option <?php if($edit['comodato_equipamento'] == '3') echo' selected="selected"';?> value="3">Container 240L</option>
                 </select>
            </div><!-- /.row --> 
            
            
            <div class="form-group col-xs-12 col-md-4"> 
					<label>Comodato Quantidade </label>
					<input type="text" name="comodato_quantidade" value="<?php echo $edit['comodato_quantidade'];?>"  class="form-control" >
			 </div> 
          	 
          	 <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Retirada</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
              			 <input type="date" name="comodato_retirada" class="form-control pull-right" value="<?php echo $edit['comodato_retirada'];?>"/>
                </div><!-- /.input group -->
           	 </div> 
           	 
           	 
           	  <div class="form-group col-xs-12 col-md-12">  
                       <label>Motivo</label>
                      <select name="motivo"  class="form-control" >
                            <option value="">Selecione motivo</option>
                                <?php 
                                    $leitura = read('contrato_cancelamento_motivo',"WHERE id ORDER BY id ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de baixar no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['motivo'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
             </div>
                  
             <div class="form-group col-xs-12 col-md-12"> 
                <label>Alguma vez foi feita alguma reclamação sobre o problema via telefone, e-mail ou diretamente com o representante comercial?</label>
                <select name="reclamacao" class="form-control" > 
                  <option value="">Selecione</option>
                  <option <?php if($edit['reclamacao'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['reclamacao'] == '2') echo' selected="selected"';?> value="2">Não</option>
                </select>
            </div><!-- /.row --> 
            
            
            <div class="form-group col-xs-12 col-md-12"> 
              <label>Descrição da solicitação</label>
                <textarea name="reclamacao_descricao" rows="5" cols="100" class="form-control" /><?php echo $edit['reclamacao_descricao'];?></textarea>
         	</div>  
        
			<div class="form-group col-xs-12 col-md-2"> 
                <label>Recuperada</label>
                <select name="recuperada" class="form-control" > 
                  <option value="">Selecione</option>
                  <option <?php if($edit['recuperada'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['recuperada'] == '2') echo' selected="selected"';?> value="2">Não</option>
                </select>
            </div><!-- /.row --> 
            
             <div class="form-group col-xs-12 col-md-12"> 
              <label>Observações do Comercial </label>
                <textarea name="observacao_comercial" rows="5" cols="100" class="form-control" /><?php echo $edit['observacao_comercial'];?></textarea>
         	</div>  
     
			 
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
 
       	  <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>	
				 <?php 
		
					if($acao=="atualizar"){
						
						 echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
						
						 echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
						
						 echo '<input type="submit" name="enviar" value="Enviar Mensagem Vendedor" class="btn btn-success" />';
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
  

