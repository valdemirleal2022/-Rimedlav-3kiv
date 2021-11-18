<?php
	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$acao = "cadastrar";
	if ( !empty( $_GET[ 'movitodivergenciaEditar' ] ) ) {
		$movitodivergenciaId = $_GET[ 'movitodivergenciaEditar' ];
		$acao = "atualizar";
	}
	if ( !empty( $_GET[ 'movitodivergenciaDeletar' ] ) ) {
		$movitodivergenciaId = $_GET[ 'movitodivergenciaDeletar' ];
		$acao = "deletar";
	}
	if ( !empty( $movitodivergenciaId ) ) {
		$readmovitodivergencia = read( 'funcionario_divergencia_motivo', "WHERE id = '$movitodivergenciaId'" );
		if ( !$readmovitodivergencia ) {
			header( 'Location: painel.php?execute=movitodivergencia/error' );
		}
		foreach ($readmovitodivergencia as $edit );
	}

?>

<div class="content-wrapper">
   
    <section class="content-header">
        <h1>Motivo Divergencia</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a href="#">Cadastro</a>
            </li>
            <li><a href="#">Divergencia</a>
            </li>
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
                </div>
                <!-- /box-tools-->
            </div>
            <!-- /.box-header -->
            
     <div class="box-body">
		 
     <?php 
			 
		if(isset($_POST['atualizar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('funcionario_divergencia_motivo',$cad,"id = '$movitodivergenciaId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
			}
		}
		 
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				create('funcionario_divergencia_motivo',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
			}
		}
		 
		if(isset($_POST['deletar'])){
			delete('funcionario_divergencia_motivo',"id = '$movitodivergenciaId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
		}

	?>

    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
                   
                    <div class="form-group col-xs-12 col-md-2">
                        <label>Id</label>
                        <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled/>
                    </div>

                    <div class="form-group col-xs-12 col-md-10">
                        <label>Nome</label>
                        <input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control"/>
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

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box box-default -->
    </section>
    <!-- /.content -->
</div> <!-- /.content-wrapper -->