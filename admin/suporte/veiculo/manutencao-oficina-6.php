 <?php 
		
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
 
		if(!empty($_GET['manutencaoIniciar'])){
			$manutencaoId = $_GET['manutencaoIniciar'];
			$acao = "iniciar";
		}
	 
		if(!empty($_GET['manutencaoBaixar'])){
			$manutencaoId = $_GET['manutencaoBaixar'];
			$acao = "baixar";
			
		}
		if(!empty($manutencaoId)){
			$readmanutencao= read('veiculo_manutencao',"WHERE id = '$manutencaoId'");
			if(!$readmanutencao){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readmanutencao as $edit);
		}

 ?>
 
<div class="content-wrapper">
  <section class="content-header">
	  
          <h1>Manutenções</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Veículo</a></li>
            <li><a href="painel.php?execute=suporte/veiculo/manutencao">Manutençoes</a></li>
             <li class="active">Editar</li>
          </ol>
	  
  </section>
	
  <section class="content">
      <div class="box box-default">
		  
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $edit['nome'];?></small></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='iniciar') echo 'Iniciar';?>
                     <?php if($acao=='baixar') echo 'Baixar';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
		  
     	 <div class="box-body">
      
	<?php 
			 
		if(isset($_POST['iniciar'])){
	
			$cad['inicio6'] = mysql_real_escape_string($_POST['inicio6']);
			$edit['inicio6'] = mysql_real_escape_string($_POST['inicio6']);
		
			$senha = mysql_real_escape_string($_POST['senha']);
			$cad['responsavel6'] = mysql_real_escape_string($_POST['responsavel6']);
			$responsavel = $cad['responsavel6'];
		 	$readResponsavel = read('veiculo_manutencao_responsavel',"WHERE id = '$responsavel' 
			AND senha = '$senha'");
			
			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Selecione Data e Hora de Inicio!</div>';
				
			}else if(!$readResponsavel){
				
				echo '<div class="alert alert-warning">Senha Invalida!</div>';
				
			}else{
				
				update('veiculo_manutencao',$cad,"id = '$manutencaoId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
			}
	 
		}
			 
			 
		if(isset($_POST['baixar'])){
			
			$senha = mysql_real_escape_string($_POST['senha']);
		
			$responsavel = $edit['responsavel6'];
		 	$readResponsavel = read('veiculo_manutencao_responsavel',"WHERE id = '$responsavel' AND senha = '$senha'");
	 
			$cad['reparo6'] = htmlspecialchars(mysql_real_escape_string($_POST['reparo6']));
			$cad['termino6'] = mysql_real_escape_string($_POST['termino6']);
			$cad['status6'] = 2;
			
			$edit['termino6'] = $cad['termino6'];
			$edit['reparo6'] = $cad['reparo6'];
			
			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Selecione Data e Hora de Inicio!</div>';
				
			}else if(!$readResponsavel){
			 
				echo '<div class="alert alert-warning">Senha Invalida!</div>';
				
			}else{
				
				update('veiculo_manutencao',$cad,"id = '$manutencaoId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
			}
		 
		}
 
	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 	
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Solicitação de Manutenção</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
		
  			<div class="form-group col-xs-12 col-md-4">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
	
			<div class="form-group col-xs-12 col-md-4">  
       		   <label>Usuário</label>
               <input type="text" name="usuario" value="<?php echo $edit['usuario'];?>" class="form-control" disabled />
            </div> 
			
			<div class="form-group col-xs-4">
                <label>Manutenção (*)</label>
                <select name="manutencao" class="form-control" disabled >
                  	<option value="">Selecione Preventiva/Corretiva</option>
                  	<option <?php if($edit['manutencao'] == '1') echo' selected="selected"';?> value="1">Preventiva </option>
                  	<option <?php if($edit['manutencao'] == '2') echo' selected="selected"';?> value="2">Corretiva</option>
					<option <?php if($edit['manutencao'] == '3') echo' selected="selected"';?> value="3">Socorro</option>
				 	<option <?php if($edit['manutencao'] == '4') echo' selected="selected"';?> value="4">Diversos</option>
					<option <?php if($edit['manutencao'] == '5') echo' selected="selected"';?> value="5">Acidente</option>
						
                 </select>
            </div>  
			
			
           <div class="form-group col-xs-12 col-md-3">  
                 <label>Data  (*)</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>"disabled/>
                  </div><!-- /.input group -->
           </div> 
           
      
           <div class="form-group col-xs-12 col-md-3">  
            <label>Veículo  (*) </label>
                <select name="id_veiculo" class="form-control"disabled >
                    <option value="">Veículo</option>
                    <?php 
                        $readConta = read('veiculo',"WHERE id ORDER BY placa ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_veiculo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['placa'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['placa'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
	
		  <div class="form-group col-xs-12 col-md-3">  
            <label>Motorista  (*)</label>
                <select name="id_motorista" class="form-control" disabled>
                    <option value="">Motorista</option>
                    <?php 
                        $readConta = read('veiculo_motorista',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_motorista'] == $mae['id']){
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
                 <label>Número do Pneu</label>
                <input type="text" name="numero_pneu" class="form-control"  value="<?php echo $edit['numero_pneu'];?>" disabled/>
              
         </div> 
 
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
	  
      <div class="box-body">
        <div class="row">
 	
		<div class="form-group col-xs-12 col-md-4">  
                 <label>Km  (*)</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="km" class="form-control"  value="<?php echo $edit['km'];?>" disabled/>
                  </div><!-- /.input group -->
            </div> 
			
		 <div class="form-group col-xs-12 col-md-4">   
                 <label>Hora Moto</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_moto" class="form-control"  value="<?php echo $edit['hora_moto'];?>" disabled/>
                  </div><!-- /.input group -->
           </div> 
			
			<div class="form-group col-xs-12 col-md-4">  
                <label>Turno  (*)</label>
                <select name="turno" class="form-control" disabled >
                  	<option value="">Selecione o Turno</option>
                  	<option <?php if($edit['turno'] == '1') echo' selected="selected"';?> value="1">1º</option>
                  	<option <?php if($edit['turno'] == '2') echo' selected="selected"';?> value="2">2º</option>
				 	<option <?php if($edit['turno'] == '3') echo' selected="selected"';?> value="3">3º</option>
                 </select>
            </div>  
      
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
          
	  <div class="box-header with-border">
             <h3 class="box-title">Manutenção [6]</h3>
      </div><!-- /.box-header -->
                
      <div class="box-body">
        <div class="row">
 
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Descrição (*)</label>
                <textarea name="descricao6" rows="2" cols="100" class="form-control"  <?php echo $readonly;?> disabled/><?php echo $edit['descricao6'];?></textarea>
         </div>  
		  
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Responsável pela excecução</label>
                <select name="responsavel6" class="form-control"  disabled>
                    <option value="">Selecione o Responsável </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['responsavel6'] == $mae['id']){
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
            <label>Tipo de Manutenção</label>
                <select name="tipo6" class="form-control" disabled>
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo6'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div> 
	 
		 	<div class="form-group col-xs-12 col-md-4" > 
			  <label>Status </label>
				<select name="status6" class="form-control" disabled>
				  <option value="">Selecione o status </option>

				  <option <?php if($edit['status6'] && $edit['status6'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['status6'] && $edit['status6'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
			</div>
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
	
	  <?php 
         if($acao=="iniciar"){
		?>					
	 <div class="box-header with-border">
             <h3 class="box-title">Iniciando</h3>
           </div><!-- /.box-header -->
                
      <div class="box-body">
        <div class="row">
 
		    <div class="form-group col-xs-12 col-md-4">  
               	<label>Início</label>
   				<input name="inicio6" type="datetime-local" value="<?php echo $edit['inicio6'];?>"  class="form-control" /> 
			</div> 
			
			
			 <div class="form-group col-xs-12 col-md-4">  
             <label>Responsável pela excecução</label>
                <select name="responsavel6" class="form-control"  >
                    <option value="">Selecione o Responsável </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['responsavel6'] == $mae['id']){
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
               	<label>Senha</label>
   				<input name="senha" type="password" value="<?php echo $edit['senha'];?>"  class="form-control" /> 
			</div> 
			
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		
	 <?php 
		}
	 ?>		
		
	 <?php 
         if($acao=="baixar"){
	 ?>	
		
	 <div class="box-header with-border">
          <h3 class="box-title">Termino</h3>
     </div><!-- /.box-header -->
                
      <div class="box-body">
        <div class="row"> 
			
		   <div class="form-group col-xs-12 col-md-4">  
               	<label>Termino</label>
   				<input name="termino6" type="datetime-local" value="<?php echo $edit['termino6'];?>"  class="form-control" /> 
			</div> 
			
			<div class="form-group col-xs-12 col-md-4">  
               	<label>Senha</label>
   				<input name="senha" type="password" value="<?php echo $edit['senha'];?>"  class="form-control" /> 
			</div> 
			  
		  	 
		 <div class="form-group col-xs-12 col-md-12"> 
              <label>Reparo executado</label>
                <textarea name="reparo6" rows="2" cols="100" class="form-control" /><?php echo $edit['reparo6'];?></textarea>
         </div>  
		  
   </div><!-- /.row -->
  </div><!-- /.box-body -->
			 
		 <?php 
		 }
		?>	

			 <div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                        if($acao=="iniciar"){
                            echo '<input type="submit" name="iniciar" value="Iniciar" class="btn btn-primary" />';	
                        }
                        
						 if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar"  class="btn btn-success" />';	
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
			 