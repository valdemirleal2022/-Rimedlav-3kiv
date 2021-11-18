<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}

	
		
		if(!empty($_GET['manifestoBaixar'])){
			$manifestoId = $_GET['manifestoBaixar'];
		}

		if(!empty($_GET['manifestoEditar'])){
			$manifestoId = $_GET['manifestoEditar'];
			$acao = "atualizar";
		}

		
		if(!empty($manifestoId)){
			$readordem = read('contrato_ordem',"WHERE id = '$manifestoId'");
			if(!$readordem){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readordem as $edit);
			
			$clienteId = $edit['id_cliente'];
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readCliente as $cliente);
		}

		$readonly = "readonly";
		$disabled = 'disabled="disabled"';
		
		$_SESSION['url2']=$_SERVER['REQUEST_URI'];

 ?>

 <div class="content-wrapper">
     <section class="content-header">
              <h1>Manifesto </h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Ordem</a></li>
                <li><a href="#">Manifesto</a></li>
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
		
		if(isset($_POST['baixar'])){
			$cad['manifesto_status'] = 'Baixado';
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_ordem',$cad,"id = '$manifestoId'");	
			$interacao='Manifesto Baixado';
			interacao($interacao,$manifestoId);
			header("Location: ".$_SESSION['url']);
		}
		 
		 
		if(isset($_POST['atualizar'])){
			$cad['manifesto'] = mysql_real_escape_string($_POST['manifesto']);
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_ordem',$cad,"id = '$manifestoId'");	
			header("Location: ".$_SESSION['url']);
		}
		 
	
	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  	
  	   			<div class="box-header with-border">
                  <h3 class="box-title">Manifesto</h3>
                </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
          
            	<div class="form-group col-xs-12 col-md-1"> 
               		<label>Id</label>
              		<input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled /> 
                 </div><!-- /.col-md-12 -->
                 
                 <div class="form-group col-xs-12 col-md-2"> 
              		<label>Interação</label>
   					<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control" disabled  /> 
                </div><!-- /.col-md-12 -->
           	
                 
                <div class="form-group col-xs-12 col-md-2"> 
					<label>Data</label>
						<input name="data" type="date" value="<?php echo $edit['data'];?>" class="form-control" disabled/> 
			 	</div><!-- /.col-md-12 -->
              
              <div class="form-group col-xs-12 col-md-1"> 
					<label>Manifesto</label>
						<input name="manifesto" type="text" value="<?php echo $edit['manifesto'];?>" class="form-control" /> 
			 	</div><!-- /.col-md-12 -->
               
 				<div class="form-group col-xs-12 col-md-2"> 
					<label>Hora</label>
						<input name="hora" type="text" value="<?php echo $edit['hora'];?>" class="form-control"  disabled/> 
			 	</div><!-- /.col-md-12 -->
   		
				<div class="form-group col-xs-12 col-md-2"> 
					<label>Hora Coleta</label>
						<input name="hora_coleta" type="text" value="<?php echo $edit['hora_coleta'];?>" class="form-control" disabled/> 
			 	</div><!-- /.col-md-12 --> 
         
         		 <div class="form-group col-xs-12 col-md-2">  
                       <label>Rota</label>
                      <select name="rota"  class="form-control" disabled >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id");
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
          
          
            </div><!-- /.row -->
		  </div><!-- /.box-body -->                   
               
		  <div class="box-body">
             <div class="row">
                                     
              <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>	
				 <?php 
				  
			echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
				  
			  echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-success" />';

				  ?>
				  
				 </div> <!-- /.box-footer -->
				 
				</div><!-- /.row -->
		      </div><!-- /.box-body --> 
		      
		  </form>
		
  	</div><!-- /.box-body -->
  </section>
<!-- /.content -->
	
	
<section class="content">
  	<div class="box box-warning">
     	 <div class="box-body">
        		 	  <?php
                  	echo '<p align="center">'.$cliente['nome'].', '.$cliente['telefone'].' / '.$cliente['contato'].'</p>';
             	 $address = $cliente['endereco'].', '.$cliente['numero'].', '.$cliente['cidade'].', '.$cliente['cep'];
				echo '<p align="center">'.$cliente['endereco'].', '.$cliente['numero'].' / '.$cliente['complemento'].
				' - '.$cliente['bairro'].' - '.$cliente['cep'].'</p>';
           		?>
             <iframe width='100%' height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
         		</iframe>
  		 </div>
	 </div>
</section><!-- /.content -->
 

</div><!-- /.content-wrapper -->
           
