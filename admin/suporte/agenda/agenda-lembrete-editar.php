
	<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['agendaEditar'])){
			$agendaId = $_GET['agendaEditar'];
			$acao = "atualizar";
		}
		
		if(!empty($_GET['agendaDeletar'])){
			$agendaId = $_GET['agendaDeletar'];
			$acao = "deletar";
		}
		
		
		if(!empty($agendaId)){
			$readagenda = read('agenda_lembrete',"WHERE id = '$agendaId'");
			if(!$readagenda){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readagenda as $edit);
		}
 ?>

 <div class="content-wrapper">
     <section class="content-header">
              <h1>Post-it </h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Agenda</a></li>
                <li><a href="#">Post-it</a></li>
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
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
     <div class="box-body"> 
       
	<?php 
	
		if(isset($_POST['cadastrar'])){
			$cad['titulo'] = mysql_real_escape_string($_POST['titulo']);
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['usuario'] = $_SESSION['autUser']['id'];
			$cad['interacao']= date('Y/m/d H:i:s');
			create('agenda_lembrete',$cad);	
			header('Location: painel.php?execute=suporte/agenda/agenda-lembrete');
		}
		
		if(isset($_POST['atualizar'])){
			$cad['titulo'] = mysql_real_escape_string($_POST['titulo']);
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['usuario'] = $_SESSION['autUser']['id'];
			$cad['interacao']= date('Y/m/d H:i:s');
			update('agenda_lembrete',$cad,"id = '$agendaId'");	
			header('Location: painel.php?execute=suporte/agenda/agenda-lembrete');
		}

		if(isset($_POST['deletar'])){
			delete('agenda_lembrete',"id = '$agendaId'");
			header('Location: painel.php?execute=suporte/agenda/agenda-lembrete');
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
           		
                 
           		<div class="form-group col-xs-12 col-md-12"> 
               		<label>Título</label>
              		<textarea name="titulo" cols="60" rows="1" class="form-control" /> <?php echo $edit['titulo'];?></textarea>
   				</div><!-- /.col-md-12 -->
                
               <div class="form-group col-xs-12 col-md-12"> 
               		<label>Descrição</label>
              		<textarea name="descricao" cols="140" rows="5" class="form-control" /> <?php echo $edit['descricao'];?></textarea>
   				</div><!-- /.col-md-12 -->
   			
            <div class="form-group col-xs-12 col-md-12"> 
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
          </div><!-- /.col-md-12 -->
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
