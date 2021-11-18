<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}

		$acao = "cadastrar";
		$edit['data']=   date("Y-m-d");
		$edit['hora'] = date("H:i");
			
		if(!empty($_GET['ocorrenciaEditar'])){
			$ocorrenciaId = $_GET['ocorrenciaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['ocorrenciaDeletar'])){
			$ocorrernciaId = $_GET['ocorrenciaDeletar'];
			$acao = "deletar";
		}
		

		if(!empty($ocorrenciaId)){
			$rotaOcorrencia = read('rota_ocorrencia',"WHERE id = '$ocorrenciaId'");
			if(!$rotaOcorrencia){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($rotaOcorrencia as $edit);
		}

 		
 ?>
 
 <div class="content-wrapper">
  <section class="content-header">
          <h1>Ocorrência</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Rota</a></li>
            <li><a href="painel.php?execute=suporte/veiculo/ocorrencias">Ocorrências</a></li>
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
				$edit['data']= htmlspecialchars(mysql_real_escape_string($_POST['data']));
				$edit['hora']= htmlspecialchars(mysql_real_escape_string($_POST['hora']));
				$edit['id_rota']= htmlspecialchars(mysql_real_escape_string($_POST['id_rota']));
				$edit['id_veiculo']= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
				$edit['id_ocorrencia']= strip_tags(trim(mysql_real_escape_string($_POST['id_ocorrencia'])));
				$edit['ocorrencia'] 		= htmlspecialchars(mysql_real_escape_string($_POST['ocorrencia']));

				$edit['usuario']	=  $_SESSION['autUser']['nome'];
				$edit['interacao']= date('Y/m/d H:i:s');
				
				if(in_array('',$edit)){
 
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					update('rota_ocorrencia',$edit,"id = '$ocorrenciaId'");	
					$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
					header("Location: ".$_SESSION['url']);
				}
			}

			if(isset($_POST['cadastrar'])){
				
				$edit['data']= mysql_real_escape_string($_POST['data']);
				$edit['hora']= mysql_real_escape_string($_POST['hora']);
				
				$edit['id_rota']= mysql_real_escape_string($_POST['id_rota']);
				$edit['id_veiculo']= mysql_real_escape_string($_POST['id_veiculo']);
				$edit['id_ocorrencia']= mysql_real_escape_string($_POST['id_ocorrencia']);
				
				$edit['ocorrencia']=htmlspecialchars(mysql_real_escape_string($_POST['ocorrencia']));

				$edit['usuario']	=  $_SESSION['autUser']['nome'];
				$edit['interacao']= date('Y/m/d H:i:s');
				
				if(in_array('',$edit)){
					print_r($edit);
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('rota_ocorrencia',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('rota_ocorrencia',"WHERE id = '$ocorrenciaId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('rota_ocorrencia',"id = '$ocorrenciaId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						 header("Location: ".$_SESSION['url']);
					}
			 }
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
      <div class="box-body">
   
      		<div class="form-group col-xs-12 col-md-1">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>

            <div class="form-group col-xs-12 col-md-6">  
           <label>Ocorrências</label>
                <select name="id_ocorrencia" class="form-control">
                    <option value="">Selecione um Ocorrência</option>
                    <?php 
                        $readSuporte = read('rota_ocorrencia_tipo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_ocorrencia'] == $mae['id']){
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
            <label>Rota</label>
                <select name="id_rota" class="form-control">
                    <option value="">Rota</option>
                    <?php 
                        $readRota = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                        if(!$readRota){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readRota as $mae):
							   if($edit['id_rota'] == $mae['id']){
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
            <label>Veículo</label>
                <select name="id_veiculo" class="form-control">
                    <option value="">Veículo</option>
                    <?php 
                        $readVeiculo = read('veiculo',"WHERE id ORDER BY placa ASC");
                        if(!$readVeiculo){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readVeiculo as $mae):
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
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                </div><!-- /.input group -->
           </div>  
           
           <div class="form-group col-xs-12 col-md-3">  
          		<label>Hora </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" name="hora" class="form-control pull-right"  value="<?php echo $edit['hora'];?>"/>
                 </div><!-- /.input group -->
           </div>
   
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Ocorrência</label>
                <textarea name="ocorrencia" rows="5" cols="100" class="form-control" /><?php echo $edit['ocorrencia'];?></textarea>
         </div>  

			 <div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                        if($acao=="atualizar"){
                            echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                        }
                        if($acao=="deletar"){
                            echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
                        }
						 if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar"  class="btn btn-success" />';	
                        }
                      if($acao=="cadastrar"){
						echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
					}
                     ?>  
                 </div>
             </div>
    </form>
    
      </div><!-- /.row -->
   </div><!-- /.box-body -->
  
  </div><!-- /.box box-default -->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
			 