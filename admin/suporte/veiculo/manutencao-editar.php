 <?php 
		
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		$edit['status'] = "Aguardando";
		$edit['usuario']	=  $_SESSION['autUser']['nome'];
		$edit['data_solicitacao']=   date("Y-m-d");
		$edit['hora_solicitacao'] = date("H:i");

		if(!empty($_GET['manutencaoEditar'])){
			$manutencaoId = $_GET['manutencaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['manutencaoDeletar'])){
			$manutencaoId = $_GET['manutencaoDeletar'];
			$acao = "deletar";
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

		$readonly = "";
		$_SESSION['manunutencao-editar']=$_SERVER['REQUEST_URI'];

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
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
		  
     	 <div class="box-body">
      
	<?php 
			 
		if(isset($_POST['atualizar'])){
			
			$edit['id_veiculo'] = strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$edit['manutencao'] = strip_tags(trim(mysql_real_escape_string($_POST['manutencao'])));
			$edit['id_motorista'] = strip_tags(trim(mysql_real_escape_string($_POST['id_motorista'])));
			$edit['km'] = strip_tags(trim(mysql_real_escape_string($_POST['km'])));
			$edit['turno'] = strip_tags(trim(mysql_real_escape_string($_POST['turno'])));
			$edit['numero_pneu']  = mysql_real_escape_string($_POST['numero_pneu']);
 
			$edit['descricao1'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao1']));
			$edit['reparo1'] 	= htmlspecialchars(mysql_real_escape_string($_POST['reparo1']));
			$edit['responsavel1'] = mysql_real_escape_string($_POST['responsavel1']);
			
			$edit['hora_moto'] 	= htmlspecialchars(mysql_real_escape_string($_POST['hora_moto']));
			 
			$edit['descricao2'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao2']));
			$edit['reparo2'] 	= htmlspecialchars(mysql_real_escape_string($_POST['reparo2']));
			$edit['responsavel2'] = mysql_real_escape_string($_POST['responsavel2']);
		
			$edit['descricao3'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao3']));
			$edit['reparo3']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo3'])));
			$edit['responsavel3'] = mysql_real_escape_string($_POST['responsavel3']);

			$edit['descricao4'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao4']));
			$edit['reparo4']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo4'])));
			$edit['responsavel4'] = mysql_real_escape_string($_POST['responsavel4']);

			$edit['descricao5'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao5']));
			$edit['reparo5']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo5'])));
			$edit['responsavel5'] = mysql_real_escape_string($_POST['responsavel5']);

			$edit['descricao6'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao6']));
			$edit['reparo6']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo6'])));
			$edit['responsavel6'] = mysql_real_escape_string($_POST['responsavel6']);

			$edit['inicio1'] = mysql_real_escape_string($_POST['inicio1']);
			$edit['inicio2'] = mysql_real_escape_string($_POST['inicio2']);
			$edit['inicio3'] = mysql_real_escape_string($_POST['inicio3']);
			$edit['inicio4'] = mysql_real_escape_string($_POST['inicio4']);
			$edit['inicio5'] = mysql_real_escape_string($_POST['inicio5']);
			$edit['inicio6'] = mysql_real_escape_string($_POST['inicio6']);

			$edit['termino1'] = mysql_real_escape_string($_POST['termino1']);
			$edit['termino2'] = mysql_real_escape_string($_POST['termino2']);
			$edit['termino3'] = mysql_real_escape_string($_POST['termino3']);
			$edit['termino4'] = mysql_real_escape_string($_POST['termino4']);
			$edit['termino5'] = mysql_real_escape_string($_POST['termino5']);
			$edit['termino6'] = mysql_real_escape_string($_POST['termino6']);

			$edit['status1'] = mysql_real_escape_string($_POST['status1']);
			$edit['status2'] = mysql_real_escape_string($_POST['status2']);
			$edit['status3'] = mysql_real_escape_string($_POST['status3']);
			$edit['status4'] = mysql_real_escape_string($_POST['status4']);
			$edit['status5'] = mysql_real_escape_string($_POST['status4']);
			$edit['status6'] = mysql_real_escape_string($_POST['status5']);
			
			$edit['tipo1'] = mysql_real_escape_string($_POST['tipo1']);
			$edit['tipo2'] = mysql_real_escape_string($_POST['tipo2']);
			$edit['tipo3'] = mysql_real_escape_string($_POST['tipo3']);
			$edit['tipo4'] = mysql_real_escape_string($_POST['tipo4']);
			$edit['tipo5'] = mysql_real_escape_string($_POST['tipo4']);
			$edit['tipo6'] = mysql_real_escape_string($_POST['tipo5']);
			
			
			update('veiculo_manutencao',$edit,"id = '$manutencaoId'");	
				
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
		 
		}
		
		if(isset($_POST['solicitar'])){
		 
			$edit['usuario']	=  $_SESSION['autUser']['nome'];
			$edit['data_solicitacao']	= date('Y-m-d');
		 
			$edit['id_veiculo'] = mysql_real_escape_string($_POST['id_veiculo']);
			$edit['manutencao'] = mysql_real_escape_string($_POST['manutencao']);
		
			$edit['km'] = mysql_real_escape_string($_POST['km']);
			
			$edit['descricao1'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao1']));
				
			if(in_array('',$edit)){
				 //print_r($edit);
				echo '<div class="alert alert-warning">Todos os campos com * são obrigatórios!</div>';	
			  }else{

				$edit['numero_pneu']  = mysql_real_escape_string($_POST['numero_pneu']);
 				$edit['id_motorista'] = mysql_real_escape_string($_POST['id_motorista']);
				$edit['turno'] = strip_tags(trim(mysql_real_escape_string($_POST['turno'])));
				
			$edit['reparo1'] 	= htmlspecialchars(mysql_real_escape_string($_POST['reparo1']));
			$edit['responsavel1'] = mysql_real_escape_string($_POST['responsavel1']);
			
			$edit['hora_moto'] 	= htmlspecialchars(mysql_real_escape_string($_POST['hora_moto']));
			 
			$edit['descricao2'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao2']));
			$edit['reparo2'] 	= htmlspecialchars(mysql_real_escape_string($_POST['reparo2']));
			$edit['responsavel2'] = mysql_real_escape_string($_POST['responsavel2']);
		
			$edit['descricao3'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao3']));
			$edit['reparo3']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo3'])));
			$edit['responsavel3'] = mysql_real_escape_string($_POST['responsavel3']);

			$edit['descricao4'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao4']));
			$edit['reparo4']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo4'])));
			$edit['responsavel4'] = mysql_real_escape_string($_POST['responsavel4']);

			$edit['descricao5'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao5']));
			$edit['reparo5']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo5'])));
			$edit['responsavel5'] = mysql_real_escape_string($_POST['responsavel5']);

			$edit['descricao6'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao6']));
			$edit['reparo6']  	= strip_tags(trim(mysql_real_escape_string($_POST['reparo6'])));
			$edit['responsavel6'] = mysql_real_escape_string($_POST['responsavel6']);

			$edit['inicio1'] = mysql_real_escape_string($_POST['inicio1']);
			$edit['inicio2'] = mysql_real_escape_string($_POST['inicio2']);
			$edit['inicio3'] = mysql_real_escape_string($_POST['inicio3']);
			$edit['inicio4'] = mysql_real_escape_string($_POST['inicio4']);
			$edit['inicio5'] = mysql_real_escape_string($_POST['inicio5']);
			$edit['inicio6'] = mysql_real_escape_string($_POST['inicio6']);

			$edit['termino1'] = mysql_real_escape_string($_POST['termino1']);
			$edit['termino2'] = mysql_real_escape_string($_POST['termino2']);
			$edit['termino3'] = mysql_real_escape_string($_POST['termino3']);
			$edit['termino4'] = mysql_real_escape_string($_POST['termino4']);
			$edit['termino5'] = mysql_real_escape_string($_POST['termino5']);
			$edit['termino6'] = mysql_real_escape_string($_POST['termino6']);

			$edit['status1'] = mysql_real_escape_string($_POST['status1']);
			$edit['status2'] = mysql_real_escape_string($_POST['status2']);
			$edit['status3'] = mysql_real_escape_string($_POST['status3']);
			$edit['status4'] = mysql_real_escape_string($_POST['status4']);
			$edit['status5'] = mysql_real_escape_string($_POST['status4']);
			$edit['status6'] = mysql_real_escape_string($_POST['status5']);
				
			$edit['tipo1'] = mysql_real_escape_string($_POST['tipo1']);
			$edit['tipo2'] = mysql_real_escape_string($_POST['tipo2']);
			$edit['tipo3'] = mysql_real_escape_string($_POST['tipo3']);
			$edit['tipo4'] = mysql_real_escape_string($_POST['tipo4']);
			$edit['tipo5'] = mysql_real_escape_string($_POST['tipo4']);
			$edit['tipo6'] = mysql_real_escape_string($_POST['tipo5']);
					
			create('veiculo_manutencao',$edit);	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
				
			}
			
		}
			 

		if(isset($_POST['deletar'])){
			
			delete('veiculo_manutencao',"id = '$manutencaoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
			
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
                <select name="manutencao" class="form-control" <?php echo $readonly;?> >
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
                  <input type="date" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>" <?php echo $readonly;?>/>
                  </div><!-- /.input group -->
           </div> 
           
      
           <div class="form-group col-xs-12 col-md-3">  
            <label>Veículo  (*) </label>
                <select name="id_veiculo" class="form-control" <?php echo $readonly;?> >
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
                <select name="id_motorista" class="form-control" <?php echo $readonly;?>>
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
                <input type="text" name="numero_pneu" class="form-control"  value="<?php echo $edit['numero_pneu'];?>"/>
              
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
                  <input type="text" name="km" class="form-control"  value="<?php echo $edit['km'];?>" <?php echo $readonly;?>/>
                  </div><!-- /.input group -->
            </div> 
			
		 <div class="form-group col-xs-12 col-md-4">   
                 <label>Hora Moto</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_moto" class="form-control"  value="<?php echo $edit['hora_moto'];?>" <?php echo $readonly;?>/>
                  </div><!-- /.input group -->
           </div> 
			
		<div class="form-group col-xs-12 col-md-4">  
                <label>Turno  (*)</label>
                <select name="turno" class="form-control" <?php echo $readonly;?> >
                  	<option value="">Selecione o Turno</option>
                  	<option <?php if($edit['turno'] == '1') echo' selected="selected"';?> value="1">1º</option>
                  	<option <?php if($edit['turno'] == '2') echo' selected="selected"';?> value="2">2º</option>
				 	<option <?php if($edit['turno'] == '3') echo' selected="selected"';?> value="3">3º</option>
                 </select>
            </div>  
			
	 
       
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
          
	  <div class="box-header with-border">
             <h3 class="box-title">Manutenção [1]</h3>
           </div><!-- /.box-header -->
                
      <div class="box-body">
        <div class="row">
 
          <div class="form-group col-xs-12 col-md-6"> 
              <label>Descrição (*)</label>
                <textarea name="descricao1" rows="2" cols="100" class="form-control"  <?php echo $readonly;?>/><?php echo $edit['descricao1'];?></textarea>
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Reparo executado</label>
                <textarea name="reparo1" rows="2" cols="100" class="form-control" /><?php echo $edit['reparo1'];?></textarea>
         </div>  
		 
		   <div class="form-group col-xs-12 col-md-3">  
            <label>Responsável pela excecução</label>
                <select name="responsavel1" class="form-control">
                    <option value="">Selecione o Responsável </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['responsavel1'] == $mae['id']){
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
               	<label>Início</label>
   				<input name="inicio1" type="datetime-local" value="<?php echo $edit['inicio1'];?>"  class="form-control" /> 
			</div> 
		 
		   <div class="form-group col-xs-12 col-md-2">  
               	<label>Termino</label>
   				<input name="termino1" type="datetime-local" value="<?php echo $edit['termino1'];?>"  class="form-control" /> 
			</div> 
			
		  
		 	<div class="form-group col-xs-12 col-md-3"> 
			  <label>Status </label>
				<select name="status1" class="form-control">
				  <option value="">Selecione o status </option>

				  <option <?php if($edit['status1'] && $edit['status1'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['status1'] && $edit['status1'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
			</div>
		 
		 <div class="form-group col-xs-12 col-md-2">  
            <label>Tipo de Manutenção</label>
                <select name="tipo1" class="form-control">
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo1'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div> 
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		 
		<div class="box-header with-border">
             <h3 class="box-title">Manutenção [2]</h3>
           </div><!-- /.box-header --> 
         <div class="box-body">
        <div class="row">
 		
		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Descrição</label>
                <textarea name="descricao2" rows="2" cols="100" class="form-control" <?php echo $readonly;?>  /><?php echo $edit['descricao2'];?></textarea>
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Reparo executado</label>
                <textarea name="reparo2" rows="2" cols="100" class="form-control" /><?php echo $edit['reparo2'];?></textarea>
         </div>  
		 
		  <div class="form-group col-xs-12 col-md-3">  
            <label>Responsável pela excecução</label>
                <select name="responsavel2" class="form-control">
                    <option value="">...</option>
                    <?php 
                        $readConta = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['responsavel2'] == $mae['id']){
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
               	<label>Início</label>
   				<input name="inicio2" type="datetime-local" value="<?php echo $edit['inicio2'];?>"  class="form-control" /> 
			</div> 
		 
		   <div class="form-group col-xs-12 col-md-2">  
               	<label>Termino</label>
   				<input name="termino2" type="datetime-local" value="<?php echo $edit['termino2'];?>"  class="form-control" /> 
			</div> 
		 
		 	<div class="form-group col-xs-12 col-md-3"> 
			  <label>Status </label>
				<select name="status2" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status2'] && $edit['status2'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['status2'] && $edit['status2'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
			</div>
			 
		    <div class="form-group col-xs-12 col-md-2">  
               <label>Tipo de Manutenção</label>
                <select name="tipo2" class="form-control">
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos tipo no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo2'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div> 
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		 
	
	 <div class="box-header with-border">
        <h3 class="box-title">Manutenção [3]</h3>
     </div><!-- /.box-header -->
	  
     <div class="box-body">
       <div class="row">
		   
	   <div class="form-group col-xs-12 col-md-6"> 
              <label>Descrição</label>
                <textarea name="descricao3" rows="2" cols="100" class="form-control" <?php echo $readonly;?> /><?php echo $edit['descricao3'];?></textarea>
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Reparo executado</label>
                <textarea name="reparo3" rows="2" cols="100" class="form-control" /><?php echo $edit['reparo3'];?></textarea>
         </div>  
		 
		   <div class="form-group col-xs-12 col-md-3">  
            <label>Responsável pela excecução</label>
                <select name="responsavel3" class="form-control">
                    <option value="">...</option>
                    <?php 
                        $readConta = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['responsavel3'] == $mae['id']){
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
               	<label>Início</label>
   				<input name="inicio3" type="datetime-local" value="<?php echo $edit['inicio3'];?>"  class="form-control" /> 
			</div> 
		 
		   <div class="form-group col-xs-12 col-md-2">  
               	<label>Termino</label>
   				<input name="termino3" type="datetime-local" value="<?php echo $edit['termino3'];?>"  class="form-control" /> 
			</div> 
		 
		 	<div class="form-group col-xs-12 col-md-3"> 
			  <label>Status </label>
				<select name="status3" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status3'] && $edit['status3'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['status3'] && $edit['status3'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
			</div>
	  
	 		 <div class="form-group col-xs-12 col-md-2">  
               <label>Tipo de Manutenção</label>
                <select name="tipo3" class="form-control">
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos tipo no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo3'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div> 
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		 
      <div class="box-header with-border">
             <h3 class="box-title">Manutenção [4]</h3>
      </div><!-- /.box-header -->

      <div class="box-body">
        <div class="row">

 		<div class="form-group col-xs-12 col-md-6"> 
              <label>Descrição </label>
                <textarea name="descricao4" rows="2" cols="100" class="form-control" <?php echo $readonly;?> /><?php echo $edit['descricao4'];?></textarea>
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Reparo executado</label>
                <textarea name="reparo4" rows="2" cols="100" class="form-control" /><?php echo $edit['reparo4'];?></textarea>
         </div>  
		 
		   <div class="form-group col-xs-12 col-md-3">  
            <label>Responsável pela excecução</label>
                <select name="responsavel4" class="form-control">
                    <option value="">...</option>
                    <?php 
                        $readConta = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['responsavel4'] == $mae['id']){
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
               	<label>Início</label>
   				<input name="inicio4" type="datetime-local" value="<?php echo $edit['inicio4'];?>"  class="form-control" /> 
			</div> 
		 
		   <div class="form-group col-xs-12 col-md-2">  
               	<label>Termino</label>
   				<input name="termino4" type="datetime-local" value="<?php echo $edit['termino4'];?>"  class="form-control" /> 
			</div> 
		 
		 	<div class="form-group col-xs-12 col-md-3"> 
			  <label>Status </label>
				<select name="status4" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status4'] && $edit['status4'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['status4'] && $edit['status4'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
			</div>

			<div class="form-group col-xs-12 col-md-2">  
               <label>Tipo de Manutenção</label>
                <select name="tipo4" class="form-control">
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos tipo no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo4'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div> 
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		 
		<div class="box-header with-border">
             <h3 class="box-title">Manutenção [5]</h3>
        </div><!-- /.box-header -->	 

         <div class="box-body">
        <div class="row">

		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Descrição </label>
                <textarea name="descricao5" rows="2" cols="100" class="form-control" <?php echo $readonly;?> /><?php echo $edit['descricao5'];?></textarea>
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Reparo executado</label>
                <textarea name="reparo5" rows="2" cols="100" class="form-control" /><?php echo $edit['reparo5'];?></textarea>
         </div>  
		 
	   <div class="form-group col-xs-12 col-md-3">  
            <label>Responsável pela excecução</label>
                <select name="responsavel5" class="form-control">
                    <option value="">...</option>
                    <?php 
                        $readConta = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['responsavel5'] == $mae['id']){
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
               	<label>Início</label>
   				<input name="inicio5" type="datetime-local" value="<?php echo $edit['inicio5'];?>"  class="form-control" /> 
			</div> 
		 
		   <div class="form-group col-xs-12 col-md-2">  
               	<label>Termino</label>
   				<input name="termino5" type="datetime-local" value="<?php echo $edit['termino5'];?>"  class="form-control" /> 
			</div> 
		 
		 	<div class="form-group col-xs-12 col-md-3"> 
			  <label>Status </label>
				<select name="status5" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status5'] && $edit['status5'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['status5'] && $edit['status5'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
			</div>

			<div class="form-group col-xs-12 col-md-2">  
               <label>Tipo de Manutenção</label>
                <select name="tipo5" class="form-control">
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos tipo no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo5'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div> 
		
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		 
		<div class="box-header with-border">
             <h3 class="box-title">Manutenção</h3>
        </div><!-- /.box-header -->	 

         <div class="box-body">
        <div class="row">

		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Descrição </label>
                <textarea name="descricao6" rows="2" cols="100" class="form-control" <?php echo $readonly;?>/><?php echo $edit['descricao6'];?></textarea>
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-6"> 
              <label>Reparo executado</label>
                <textarea name="reparo6" rows="2" cols="100" class="form-control" /><?php echo $edit['reparo6'];?></textarea>
         </div>  
		 
	   <div class="form-group col-xs-12 col-md-3">  
            <label>Responsável pela excecução</label>
                <select name="responsavel6" class="form-control">
                    <option value="">Selecione o Responsável</option>
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
		
		   <div class="form-group col-xs-12 col-md-2">  
               	<label>Início</label>
   				<input name="inicio6" type="time" value="<?php echo $edit['inicio6'];?>"  class="form-control" /> 
			 
		   </div> 
			
		   <div class="form-group col-xs-12 col-md-2">  
               	<label>Termino</label>
   				<input name="termino6" type="time" value="<?php echo $edit['termino6'];?>"  class="form-control" /> 
			</div> 
		 
		 	<div class="form-group col-xs-12 col-md-3"> 
			  <label>Status </label>
				<select name="status6" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status6'] && $edit['status6'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['status6'] && $edit['status6'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
			</div>


			<div class="form-group col-xs-12 col-md-2">  
               <label>Tipo de Manutenção</label>
                <select name="tipo6" class="form-control">
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos tipo no momento</option>';	
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
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		 
		 
         <div class="box-body">
        <div class="row">

		
	 
		  </div><!-- /.row -->
       </div><!-- /.box-body -->

			 <div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                        if($acao=="atualizar"){
                            echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                        }
					
						
					   if($_SESSION['autUser']['nivel']==5){	//Gerencial 

							echo '<input type="submit" name="desfazer" value="Desfazer Baixa" class="btn btn-success" />';	

							echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';

						} 
					
						 if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar"  class="btn btn-success" />';	
                        }
                        if($acao=="cadastrar"){
                            echo '<input type="submit" name="solicitar" value="Solicitar Manutenção" class="btn btn-primary" />';
	
                        }
                     ?>  
                 </div>
             </div>
			</div>
    </form>
   		
   	</div><!-- /.box-body -->
  </div><!-- /.box box-default -->
 </section><!-- /.content -->


<section class="content">
	
<div class="row">
  <div class="col-md-12">
	<div class="box box-default">
					
			<div class="box-header">
				
				 <a href="painel.php?execute=suporte/estoque/material-retirada-manutencao-editar&manutencaoId=<?PHP echo $manutencaoId; ?>" class="btnnovo">
				  <img src="ico/novo.png" title="Criar Novo" />
					<small>Retirada de Material</small>
					 </a>
		
				
			</div><!-- /.box-header -->

			<div class="box-body table-responsive">

			<?php 
			$totalManutencao=0;
			$leitura = read(' estoque_material_retirada',"WHERE id AND id_manutencao='$manutencaoId' ORDER BY id ASC");
			 
			if($leitura){
						
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Codigo</td>
					<td align="center">MAterial</td>
					<td align="center">Quantidade</td>
					<td align="center">Unidade</td>
					<td align="center">Vl Unitário</td>
					<td align="center">Total</td>
					<td align="center">Observação</td>
					<td align="center" colspan="2">Gerenciar</td>
				</tr>';
				
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				
				echo '<td>'.$material['codigo'].'</td>';
				echo '<td>'.$material['nome'].'</td>';
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.$material['unidade'].'</td>';
				
				$totalManutencao=$totalManutencao+$mostra['quantidade']*$material['valor_unitario'];
				
				echo '<td align="right">'.converteValor($material['valor_unitario']).'</td>';
				echo '<td align="right">'.converteValor($mostra['quantidade']*$material['valor_unitario']).'</td>';
				
				echo '<td align="right">'.$mostra['observacao'].'</td>';
			
				 
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-retirada-editar&retiradaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
              			</a>
						</td>';

			echo '</tr>';
		
		 endforeach;
		 
		
			echo '<tfoot>';
         			echo '<tr>';
                	echo '<td colspan="14">' . 'Total da  Manutenção : ' . converteValor($totalManutencao) . '</td>';
                echo '</tr>';

          echo '</tfoot>';
		
		echo '</table>';
		
		
		}
	?>

				<div class="box-footer">
					<?php echo $_SESSION['cadastro'];
					unset($_SESSION['cadastro']);
					?>
				</div>
						<!-- /.box-footer-->

					</div>
					<!-- /.box-body table-responsive -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col-md-12 -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
	
	
	
	
</div><!-- /.content-wrapper -->
			 