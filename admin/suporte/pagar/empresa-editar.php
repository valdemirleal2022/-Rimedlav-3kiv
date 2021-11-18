<?php

	if ( function_exists( ProtUser ) ) {
			if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
				header( 'Location: painel.php?execute=suporte/403' );
			}
		}

	$acao = "cadastrar";
	if ( !empty( $_GET[ 'empresaEditar' ] ) ) {
		$empresaId = $_GET[ 'empresaEditar' ];
		$acao = "atualizar";
	}
	if ( !empty( $_GET[ 'empresaDeletar' ] ) ) {
		$empresaId = $_GET[ 'empresaDeletar' ];
		$acao = "deletar";
	}
	if ( !empty( $empresaId ) ) {
		$readempresa = read( 'empresa_pagar', "WHERE id = '$empresaId'" );
		if ( !$readempresa ) {
			header( 'Location: painel.php?execute=suporte/error' );
		}
		foreach ( $readempresa as $edit );
	}
?>

<div class="content-wrapper">
	
    <section class="content-header">
		
        <h1>Empresa</h1>
		
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a href="#">Cadastro</a>
            </li>
            <li><a href="painel.php?execute=suporte/pagar/empresa-editar">Empresa</a>
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
			$cad['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
		
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('empresa_pagar',$cad,"id = '$empresaId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/pagar/empresas');
				unset($cad);
			}
			
		}
			
		if(isset($_POST['cadastrar'])){
			
			$edit['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$edit['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
		
			if(in_array('',$edit)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				create('empresa_pagar',$edit);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/pagar/empresas');
				unset($cad);
			}
			
		}
			
		if(isset($_POST['deletar'])){
			
			delete('empresa_pagar',"id = '$empresaId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/pagar/empresas');
		}

	?>

       <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
            
             <div class="box-header with-border">
                  <h3 class="box-title">Dados da Empresa</h3>
             </div>
             <!-- /.box-header -->

                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-xs-12 col-md-2">
                                <label>Id</label>
                                <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled/>
                            </div>

                            <div class="form-group col-xs-12 col-md-7">
                                <label>Empresa</label>
                                <input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control"/>
                            </div>

                          
                            <div class="form-group col-xs-12 col-md-3">
                                <label>CNPJ</label>
                                <input type="text" name="cnpj" class="form-control" value="<?php echo $edit['cnpj'];?>"/>
                            </div>
                            
                        </div>
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
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box box-default -->
    </section>
    <!-- /.content -->
</div> <!-- /.content-wrapper -->