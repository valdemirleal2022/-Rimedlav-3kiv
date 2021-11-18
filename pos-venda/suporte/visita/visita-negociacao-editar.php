
	<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autConsultor']['id'])){
				header('Location: painel.php');	
			}	
		}

		$consultor=$_SESSION['autConsultor']['id'];
		
		$acao = "cadastrar";
		
		if(!empty($_GET['agendaEditar'])){
			$agendaId = $_GET['agendaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['agendaDeletar'])){
			$agendaId = $_GET['agendaDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['agendaBaixar'])){
			$agendaId = $_GET['agendaBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['visitaId'])){
			$visitaId = $_GET['visitaId'];
			$acao = "cadastrar";
		}
		
		if(!empty($agendaId)){
			$readagenda = read('cadastro_visita_negociacao',"WHERE id = '$agendaId'");
			if(!$readagenda){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readagenda as $edit);
			
		}

		$edit['consultor']=$consultor;

		
		$cliente = mostra('cadastro_visita',"WHERE id = '$visitaId'");

 ?>

 <div class="content-wrapper">
     <section class="content-header">
              <h1>Negociação </h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Cliente</a></li>
                <li><a href="#">Negociação</a></li>
                 <li class="active">Editar</li>
              </ol>
      </section>
	 <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $cliente['nome'].' || '.$cliente['email'];?></small></h3>
                 
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
	
		if(isset($_POST['cadastrar'])){
			$cad['consultor'] = mysql_real_escape_string($_POST['consultor']);
			$cad['interacao'] = mysql_real_escape_string($_POST['interacao']);
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['id_visita'] = $visitaId;
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] =1;
			$cad['consultor'] = $consultor;
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				$cad['retorno'] = mysql_real_escape_string($_POST['retorno']);
				if(empty($cad['retorno'])){
					$cad['status'] =2;
				}
			   create('cadastro_visita_negociacao',$cad);	
			   header("Location: ".$_SESSION['url2']);
			 }
			
		}
		
		if(isset($_POST['atualizar'])){
			$cad['consultor'] = mysql_real_escape_string($_POST['consultor']);
			$cad['retorno'] = mysql_real_escape_string($_POST['retorno']);
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['consultor'] = $consultor;
			update('cadastro_visita_negociacao',$cad,"id = '$agendaId'");	
			header("Location: ".$_SESSION['url2']);
		}
		
		if(isset($_POST['baixar'])){
			$cad['consultor'] = mysql_real_escape_string($_POST['consultor']);
			$cad['retorno'] = mysql_real_escape_string($_POST['retorno']);
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] =2;
			update('cadastro_visita_negociacao',$cad,"id = '$agendaId'");	
			header("Location: ".$_SESSION['url2']);
		}

		if(isset($_POST['deletar'])){
			delete('cadastro_visita_negociacao',"id = '$agendaId'");
			header("Location: ".$_SESSION['url2']);
		}
		
		
	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-interacao">
    
            	<div class="form-group col-xs-12 col-md-3"> 
               		<label>Id</label>
              		<input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled /> 
                 </div><!-- /.col-md-12 -->
                 
                 <div class="form-group col-xs-12 col-md-3"> 
              		<label>Interação</label>
   					<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control" readonly  /> 
                </div><!-- /.col-md-12 -->
           		
                <div class="form-group col-xs-12 col-md-3"> 
                    <label>Consultor </label>
                    <select name="consultor" class="form-control" readonly/>
                            <option value="">Selecione o Consultor</option>
                                <?php 
                                    $leitura = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Não temos consultor no momento</option>';	
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
                 </div><!-- /.col-md-12 -->
                 
                 <div class="form-group col-xs-12 col-md-3"> 
                <label>Retorno:</label>
              		<input name="retorno" type="date" value="<?php echo $edit['retorno'];?>" class="form-control" /> 
			 	</div><!-- /.col-md-12 -->
                 
           		<div class="form-group col-xs-12 col-md-12"> 
               		<label>Descrição</label>
              		<textarea name="descricao" cols="140" rows="5" class="form-control" /> <?php echo $edit['descricao'];?></textarea>
   				</div><!-- /.col-md-12 -->
   
            <div class="box-footer">
       		  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
           	  <?php 
                if($acao=="baixar"){
                    echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-success" />';	
                }
				 if($acao=="atualizar"){
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
					echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';		
                }
                if($acao=="deletar"){
                    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
                }
                if($acao=="cadastrar"){
                    echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                }
                if($acao=="enviar"){
                    echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
                }
             ?>  
          </div> 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
