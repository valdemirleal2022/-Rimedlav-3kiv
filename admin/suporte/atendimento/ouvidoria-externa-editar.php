<?php 
 
 
/* Habilita a exibição de erros */
//ini_set("display_errors", 1);

		if(function_exists("ProtUser")){
			if(!ProtUser($_SESSION["autUser"]["id"])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}

		 
		$acao = "cadastrar";
	
		$edit['status'] = "Aguardando";
		$edit['data_solicitacao']=   date("Y-m-d");
		$edit['hora_solicitacao'] = date("H:i");
		$edit['data_solucao']=   date("Y-m-d");
		$edit['hora_solucao'] = date("H:i");
		$edit['protocolo'] =  converteData(date("Y-m-d")).'|'.date("H:i");
		$edit['atendente_abertura']=$_SESSION['autUser']['nome'];
		$edit['data_limite']=  somarDia(date("Y-m-d"),3);
			
		if(!empty($_GET['suporteEditar'])){
			$suporteId = $_GET['suporteEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['suporteDeletar'])){
			$suporteId = $_GET['suporteDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['suporteBaixar'])){
			$suporteId = $_GET['suporteBaixar'];
			$acao = "baixar";
		}

		if(!empty($_GET['suporteBaixarOperacional'])){
			$suporteId = $_GET['suporteBaixarOperacional'];
			$acao = "baixar-operacional";
		}

		 

		if(!empty($suporteId)){
			$readsuporte = read('ouvidoria_externa',"WHERE id = '$suporteId'");
			if(!$readsuporte){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readsuporte as $edit);
			 
			 
			
		}
 
		if($acao=="baixar"){
			$edit['status'] = "OK";
			$edit['data_solucao']=   date("Y-m-d");
			$edit['hora_solucao'] = date("H:i");
		 
		}
		
 		
 ?>
 
 <div class="content-wrapper">
  <section class="content-header">
          <h1>Ouvidoria Externa</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=suporte/ouvidoria/ouvidorias">Ouvidoria</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
      <div class="box box-default">
           
            <div class="box-header with-border">
				 
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                     <?php if($acao=='baixar') echo 'Baixando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
	
     	 <div class="box-body">

		<?php 
		
			if(isset($_POST['atualizar'])){
				$cad['solicitacao'] = mysql_real_escape_string($_POST['solicitacao']);
				$cad['usuario'] = strip_tags(trim(mysql_real_escape_string($_POST['usuario'])));
				$cad['rota'] = strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
			$cad['id_suporte'] = strip_tags(trim(mysql_real_escape_string($_POST['id_suporte'])));
				$cad['id_origem'] = strip_tags(trim(mysql_real_escape_string($_POST['id_origem'])));
				$cad['solucao'] 	= mysql_real_escape_string($_POST['solucao']);
	 		
				$cad['tecnico']	=  $_SESSION['autUser']['nome'];
	$cad['atendente_fechamento']= mysql_real_escape_string($_POST['atendente_fechamento']);
	$cad['usuario'] = strip_tags(trim(mysql_real_escape_string($_POST['usuario'])));
				
	$cad['operacional_solicitacao'] = mysql_real_escape_string($_POST['operacional_solicitacao']);
	$cad['tratativa_ouvidoria'] = mysql_real_escape_string($_POST['tratativa_ouvidoria']);
		 	
				if(empty($cad['operacional_status'])){
					$cad['operacional_status'] = 'Aguardando';
				}
			 
				update('ouvidoria_externa',$cad,"id = '$suporteId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
			}
		
			if(isset($_POST['baixar'])){
				$cad['status'] = 'OK';
				$cad['solucao'] = mysql_real_escape_string($_POST['solucao']);
				$cad['tecnico']	=  $_SESSION['autUser']['nome'];
				$cad['atendente_fechamento']= strip_tags(trim(mysql_real_escape_string($_POST['atendente_fechamento'])));
				
				update('ouvidoria_externa',$cad,"id = '$suporteId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Baixado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
				
			}
			 
			 
			if(isset($_POST['baixar-operacional'])){
				$cad['operacional_status']	='OK';
				$cad['operacional_solucao']= mysql_real_escape_string($_POST['operacional_solucao']);
				if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					update('ouvidoria_externa',$cad,"id = '$suporteId'");	
					$_SESSION['ouvidoria_externa'] = '<div class="alert alert-success">Baixado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
				 }
			}
			
			if(isset($_POST['abrir'])){
				
				$edit['data_solicitacao']	= date('Y-m-d');
				$edit['hora_solicitacao'] 	= date("H:i");
				$edit['solicitacao'] = mysql_real_escape_string($_POST['solicitacao']);
				$edit['usuario'] = mysql_real_escape_string($_POST['usuario']);
			$edit['id_suporte']= strip_tags(trim(mysql_real_escape_string($_POST['id_suporte'])));
				$edit['id_origem'] = strip_tags(trim(mysql_real_escape_string($_POST['id_origem'])));
				$edit['id_motivo'] = strip_tags(trim(mysql_real_escape_string($_POST['id_motivo'])));
				
				$edit['anonimado'] = strip_tags(trim(mysql_real_escape_string($_POST['anonimado'])));
				$edit['telefone'] = strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
				$edit['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
				$edit['protocolo'] = strip_tags(trim(mysql_real_escape_string($_POST['protocolo'])));
				
				$edit['atendente_abertura'] =  $_SESSION['autUser']['nome'];
				$edit['status'] = "Aguardando";
				$edit['status_tratativa'] = "Aguardando";
				$edit['data_limite']=  somarDia(date("Y-m-d"),3);
			$edit['tratativa_ouvidoria'] = mysql_real_escape_string($_POST['tratativa_ouvidoria']);
				$edit['tecnico']= $_SESSION['autUser']['nome'];
				 
	$edit['operacional_solicitacao']  = mysql_real_escape_string($_POST['operacional_solicitacao']);
					$edit['operacional_status'] = "Aguardando";
					$edit['rota'] = strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
				    create('ouvidoria_externa',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
			}
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('ouvidoria_externa',"WHERE id = '$suporteId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('ouvidoria_externa',"id = '$suporteId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
					 header("Location: ".$_SESSION['url']);
					}
			 }
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Abertura</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-1">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
		 
		   <div class="form-group col-xs-12 col-md-2">  
       		   <label>Status</label>
               <input type="text" name="status" value="<?php echo $edit['status'];?>" class="form-control" disabled />
            </div> 
			
			<div class="form-group col-xs-12 col-md-3">  
            <label>Motivo do Atendimento</label>
                <select name="id_suporte" class="form-control" >
                    <option value="">Selecione um Motivo</option>
                    <?php 
                        $readSuporte = read('pedido_suporte',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_suporte'] == $mae['id']){
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
            <label>Motivo da Ouvidoria</label>
                <select name="id_motivo" class="form-control" >
                    <option value="">Selecione um Motivo</option>
                    <?php 
                        $readSuporte = read('ouvidoria_motivo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos motivo no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_motivo'] == $mae['id']){
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
            <label>Origem do Atendimento</label>
                <select name="id_origem" class="form-control" >
                    <option value="">Selecione uma Origem</option>
                    <?php 
                        $readSuporte = read('pedido_origem',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos origem no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_origem'] == $mae['id']){
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
                <label>Protocolo</label>
                 <input type="text" name="protocolo" value="<?php echo $edit['protocolo'];?>" class="form-control" readonly />
            </div>
			
			 <div class="form-group col-xs-2">
                <label>Contato Anonimo ?</label>
                <select name="anonimado" class="form-control" >
                  <option value="">...</option>
                  <option <?php if($edit['anonimado'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['anonimado'] == '0') echo' selected="selected"';?> value="0">Não</option>
                 </select>
           	 </label>
       		</div>
			
			
			 <div class="form-group col-xs-12 col-md-4">  
                <label>Telefone</label>
                 <input type="text" name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control"  />
            </div>
			
			 <div class="form-group col-xs-12 col-md-4">  
                <label>Email</label>
                 <input type="text" name="email" value="<?php echo $edit['email'];?>" class="form-control"  />
            </div>
             
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Hora</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_solicitacao" class="form-control" disabled value="<?php echo $edit['hora_solicitacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-3">  
       		   <label>Contato (Cliente)</label>
               <input type="text" name="usuario" value="<?php echo $edit['usuario'];?>" class="form-control" />
           </div> 
		 
		  <div class="form-group col-xs-12 col-md-2">  
       		   <label>Atendente Abertura</label>
              <input type="text" name="atendente_abertura" value="<?php echo $edit['atendente_abertura'];?>" class="form-control" disabled/>
           </div> 
			
		 <div class="form-group col-xs-12 col-md-2">  
                       <label>Rota</label>
                      <select name="rota"  class="form-control" >
                            <option value="">Rota da coleta</option>
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
              
          <div class="form-group col-xs-12 col-md-12"> 

              <label>Solicitação</label>
                <textarea name="solicitacao" rows="5" cols="100" class="form-control" /><?php echo $edit['solicitacao'];?></textarea>
         </div>  
		
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
		
		
		
  		<div class="box-header with-border">
                  <h3 class="box-title">Solicitação Operacional</h3>
        </div><!-- /.box-header -->
                
        <div class="box-body">
        <div class="row">
			
			 <div class="form-group col-xs-6">
                <label>Solicitar Retorno do Operacional ?</label>
                <select name="operacional_solicitacao" class="form-control" >
                  <option value="">...</option>
                  <option <?php if($edit['operacional_solicitacao'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['operacional_solicitacao'] == '0') echo' selected="selected"';?> value="0">Não</option>
                 </select>
           	 </label>
       		</div>
		
			  <div class="form-group col-xs-12 col-md-6">  
       		   <label>Status da Solicitação</label>
               <input type="text" name="operacional_status" value="<?php echo $edit['operacional_status'];?>" class="form-control" disabled />
              </div> 
			
			  <div class="form-group col-xs-12 col-md-12"> 
              <label>Resposta do Operacional</label>
                <textarea  name="operacional_solucao" rows="5" cols="100" class="form-control"  <?php echo $readonly;?>/><?php echo $edit['operacional_solucao'];?></textarea>
        	 </div>  
			 
			 
			 
            
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
		
		
          
		
  		<div class="box-header with-border">
                  <h3 class="box-title">Fechamento</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
            
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solucao" class="form-control"  value="<?php echo $edit['data_solucao'];?>"/>
	 
                  </div><!-- /.input group -->
				 
				  
           </div> 
           
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Hora</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_solucao" class="form-control" disabled value="<?php echo $edit['hora_solucao'];?>"  <?php echo $readonly;?>/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-3">  
       		   <label>Atendente Fechamento</label>
               <input type="text" name="atendente_fechamento" value="<?php echo $edit['atendente_fechamento'];?>"  class="form-control" />
             </div> 
		
            
             <div class="form-group col-xs-12 col-md-12"> 
              <label>Tratativa Ouvidoria</label>
                <textarea  name="tratativa_ouvidoria" rows="5" cols="100" class="form-control" /><?php echo $edit['tratativa_ouvidoria'];?></textarea>
        	 </div>  
			 
			  <div class="form-group col-xs-6">
                <label>Status Ouvidoria</label>
                <select name="status_tratativa" class="form-control" >
                  <option value="">...</option>
                  <option <?php if($edit['status_tratativa'] == '1') echo' selected="selected"';?> value="1">OK</option>
                  <option <?php if($edit['status_tratativa'] == '0') echo' selected="selected"';?> value="0">Aguardando</option>
                 </select>
           	 </label>
       		</div>
		  
		   
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data Limite</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_limite" class="form-control" disabled value="<?php echo $edit['data_limite'];?>"/>
                  </div><!-- /.input group -->
           </div> 
			 
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
          

			 <div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                        if($acao=="atualizar"){
                            echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                        }
                       
                  
						 if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar"  class="btn btn-success" />';	
							 
					 if($_SESSION['autUser']['nivel']==5){	//Gerencial 
				  
					    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
					 
					}
                        }
					
					    if($acao=="baixar-operacional"){
                            echo '<input type="submit" name="baixar-operacional" value="Baixar Operacional"  class="btn btn-success" />';	
                        }
                        if($acao=="cadastrar"){
							
                            echo '<input type="submit" name="abrir" value="Abrir Atendimento" class="btn btn-primary" />';
						 
                        }
					
                     ?>  
                 </div>
             </div>
			</div>
    </form>
   		
   	</div><!-- /.box-body -->
  </div><!-- /.box box-default -->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
			 