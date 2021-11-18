<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'],'1')){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		$reademail = read('email_mkt',"WHERE id");
		if(!$reademail){
			header('Location: painel.php?execute=suporte/error');	
		}
		foreach($reademail as $edit);
		$IdMensagem=$edit['id'];
		$acao = "atualizar";
?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Empresa</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/pagar/bancos">Empresa</a></li>
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
			$cad['titulo']    = htmlspecialchars(mysql_real_escape_string($_POST['titulo']));
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['data']= date('Y/m/d H:i:s');
			update('email_mkt', $cad, "id = '$IdMensagem'");
			$_SESSION['atualiza'] = '<div class="msgAcerto">Atualizado com sucesso</div><br />';
			header('Location: painel.php?execute=suporte/email/emails');
			unset($cad);
		}
	?>
    
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			
        
           <div class="form-group col-xs-12 col-md-8"> 
               <label>Título</label>
               <input name="titulo" type="text" value="<?php echo $edit['titulo'];?>" class="form-control"/>
            </div> 
            
              <div class="form-group col-xs-12 col-md-4">  
                 <label>Data</label>
                  <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                  </div><!-- /.input group -->
           </div> 
          
             <div class="form-group">
              <label>Descrição</label>
                <textarea id="editor-texto" name="descricao" rows="8" cols="80">
                    <?php echo htmlspecialchars($edit['descricao']);?>
                </textarea>
         </div>  
          
            
          <div class="box-footer">
         <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
           	  <?php 
                if($acao=="baixar"){
                    echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-primary" />';	
                }
				 if($acao=="atualizar"){
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
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