<?php

if ( function_exists( ProtUser ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}

$acao = "cadastrar";
if ( !empty( $_GET[ 'coletorEditar' ] ) ) {
    $coletorId = $_GET[ 'coletorEditar' ];
    $acao = "atualizar";
}
if ( !empty( $_GET[ 'coletorDeletar' ] ) ) {
    $coletorId = $_GET[ 'coletorDeletar' ];
    $acao = "deletar";
}
if ( !empty( $coletorId ) ) {
    $readcoletor = read( 'contrato_coletor', "WHERE id = '$coletorId'" );
    if ( !$readcoletor ) {
        header( 'Location: painel.php?execute=suporte/error' );
    }
    foreach ( $readcoletor as $edit );
}


if ( isset( $_POST[ 'atualizar' ] ) ) {
    $cad[ 'nome' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'nome' ] ) ) );
    $cad[ 'nome_completo' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'nome_completo' ] ) ) );
    if ( in_array( '', $cad ) ) {
        echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
    } else {
        if ( $_FILES[ 'fotoperfil' ][ 'name' ] ) {
            $imagem = $_FILES[ 'fotoperfil' ];
            $pasta = '../uploads/coletores/';
            if ( file_exists( $pasta . $edit[ 'fotoperfil' ] ) &&
                !is_dir( $pasta . $edit[ 'fotoperfil' ] ) ) {
                unlink( $pasta . $edit[ 'fotoperfil' ] );
            }
            $tmp = $imagem[ 'tmp_name' ];
            $ext = substr( $imagem[ 'name' ], -3 );
            $nome = md5( time() ) . '.' . $ext;
            $cad[ 'fotoperfil' ] = $nome;
            uploadImg( $tmp, $nome, '160', $pasta );
        }
        $cad[ 'data_nascimento' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'data_nascimento' ] ) ) );
        $cad[ 'email' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'email' ] ) ) );
        $cad[ 'telefone' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'telefone' ] ) ) );
        $cad[ 'senha' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'senha' ] ) ) );
        $cad[ 'rg' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'rg' ] ) ) );
        $cad[ 'cpf' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'cpf' ] ) ) );
        update( 'contrato_coletor', $cad, "id = '$coletorId'" );
        header( 'Location: painel.php?execute=suporte/cadastro/coletores' );
        unset( $cad );
    }
}

if ( isset( $_POST[ 'cadastrar' ] ) ) {
    $cad[ 'nome' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'nome' ] ) ) );
    $cad[ 'nome_completo' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'nome_completo' ] ) ) );

    if ( in_array( '', $cad ) ) {
        echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
    } else {
        if ( !empty( $_FILES[ 'fotoperfil' ] ) ) {
            $imagem = $_FILES[ 'fotoperfil' ];
            $pasta = '../uploads/coletores/';
            if ( file_exists( $pasta . $edit[ 'fotoperfil' ] ) &&
                !is_dir( $pasta . $edit[ 'fotoperfil' ] ) ) {
                unlink( $pasta . $edit[ 'fotoperfil' ] );
            }
            $tmp = $imagem[ 'tmp_name' ];
            $ext = substr( $imagem[ 'name' ], -3 );
            $nome = md5( time() ) . '.' . $ext;
            $cad[ 'fotoperfil' ] = $nome;
            uploadImg( $tmp, $nome, '160', $pasta );
        }
        $cad[ 'data_nascimento' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'data_nascimento' ] ) ) );
        $cad[ 'email' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'email' ] ) ) );
        $cad[ 'telefone' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'telefone' ] ) ) );
        $cad[ 'senha' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'senha' ] ) ) );
        $cad[ 'rg' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'rg' ] ) ) );
        $cad[ 'cpf' ] = strip_tags( trim( mysql_real_escape_string( $_POST[ 'cpf' ] ) ) );
        create( 'contrato_coletor', $cad );
        header( 'Location: painel.php?execute=suporte/cadastro/coletores' );
        unset( $cad );
    }
}

if ( isset( $_POST[ 'deletar' ] ) ) {
    delete( 'contrato_coletor', "id = '$coletorId'" );
    header( 'Location: painel.php?execute=suporte/cadastro/coletores' );
}

?>

<div class="content-wrapper">
   
    <section class="content-header">
        <h1>Coletores</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a href="#">Cadastro</a>
            </li>
            <li><a href="painel.php?execute=suporte/pagar/bancos">Coletores</a>
            </li>
            <li class="active">Editar</li>
        </ol>
    </section>
    
    <section class="content">
        <div class="box box-default">
           
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo $edit['nome_completo'];?>
                </h3>
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

            <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

              <div class="form-group">
               <?php 
				if($edit['fotoperfil'] != '' && file_exists('../uploads/coletores/'.$edit['fotoperfil'])){
					echo '<img src="../uploads/coletores/'.$edit['fotoperfil'].'"/>';
				}else{
					echo '<img src="../../../site/images/autor.png"';
				}
		       ?>
              </div>

                    <div class="form-group">
                        <input type="file" name="fotoperfil"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-2">
                        <label>Id</label>
                        <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled/>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label>Nome</label>
                        <input name="nome" type="text" value="<?php echo $edit['nome'];?>" class="form-control"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label>Nome Completo</label>
                        <input name="nome_completo" type="text" value="<?php echo $edit['nome_completo'];?>" class="form-control"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label>Telefone</label>
                        <input name="telefone" type="text" value="<?php echo $edit['telefone'];?>" class="form-control"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label>Email</label>
                        <input name="email" type="text" value="<?php echo $edit['email'];?>" class="form-control"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label>Senha</label>
                        <input name="senha" type="password" value="<?php echo $edit['senha'];?>" class="form-control"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label>CPF</label>
                        <input name="cpf" type="text" value="<?php echo $edit['cpf'];?>" class="form-control"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label>RG</label>
                        <input name="rg" type="text" value="<?php echo $edit['rg'];?>" class="form-control"/>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label>Data de Nascimento</label>
                        <input name="data_nascimento" type="date" value="<?php echo $edit['data_nascimento'];?>" class="form-control"/>
                    </div>


                    <div class="box-footer">
                       <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> 
                        </a>
                        <?php 
                        if($acao=="atualizar"){
                            echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                        }
                        if($acao=="deletar"){
                            echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-primary" />';	
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
        <!-- /.col-md-12 -->

</div>
<!-- /.row -->

</section>

</div><!-- / . content - wrapper-- >