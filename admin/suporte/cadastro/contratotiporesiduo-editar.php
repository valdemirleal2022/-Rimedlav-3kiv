<?php 

		if ( function_exists( ProtUser ) ) {
			if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
				header( 'Location: painel.php?execute=suporte/403' );
			}
		}

		$acao = "cadastrar";
		if(!empty($_GET['contratotiporesiduoEditar'])){
			$contratotiporesiduoId = $_GET['contratotiporesiduoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['contratotiporesiduoDeletar'])){
			$contratotiporesiduoId = $_GET['contratotiporesiduoDeletar'];
			$acao = "deletar";
		}
		if(!empty($contratotiporesiduoId)){
			$readcontratotiporesiduo = read('contrato_tipo_residuo',"WHERE id = '$contratotiporesiduoId'");
			if(!$readcontratotiporesiduo){
				header('Location: painel.php?execute=contratotiporesiduo/error');	
			}
			foreach($readcontratotiporesiduo as $edit);
		}
		
 ?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Contrato Tipo Resíduo</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/cadastro/contratotiporesiduo">Contrato Tipo Resíduo</a></li>
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
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['processo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['processo'])));
			$cad['valor_minimo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_minimo'])));
			$cad['valor_minimo'] = str_replace(",",".",str_replace(".","",$cad['valor_minimo']));		
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('contrato_tipo_residuo',$cad,"id = '$contratotiporesiduoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/cadastro/contratotiporesiduos');
				unset($cad);
			}
		}
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['processo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['processo'])));
			$cad['valor_minimo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_minimo'])));
			$cad['valor_minimo'] = str_replace(",",".",str_replace(".","",$cad['valor_minimo']));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				create('contrato_tipo_residuo',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/cadastro/contratotiporesiduos');
				unset($cad);
			}
		}
		if(isset($_POST['deletar'])){
			delete('contrato_tipo_residuo',"id = '$contratotiporesiduoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/cadastro/contratotiporesiduos');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  		<div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-3"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
		
			<div class="form-group col-xs-12 col-md-3"> 
       		   <label>Processo</label>
               <input type="text" name="processo" value="<?php echo $edit['processo'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-3"> 

           <label>Valor</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-money"></i>
                          </div>
                         <input name="valor_minimo" class="form-control dinheiro" type="text" value="<?php echo converteValor($edit['valor_minimo']);?>" />
           </div><!-- /.input group -->
		</div> 
	      
           <div class="form-group col-xs-12 col-md-12">          
			 <div class="box-footer">
				<a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
				<?php 
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
		  </div>  
   </form>
   
		</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->



	
