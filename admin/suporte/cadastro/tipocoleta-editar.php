<?php
	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$acao = "cadastrar";
	if ( !empty( $_GET[ 'tipocoletaEditar' ] ) ) {
		$tipocoletaId = $_GET[ 'tipocoletaEditar' ];
		$acao = "atualizar";
	}
	if ( !empty( $_GET[ 'tipocoletaDeletar' ] ) ) {
		$tipocoletaId = $_GET[ 'tipocoletaDeletar' ];
		$acao = "deletar";
	}
	if ( !empty( $tipocoletaId ) ) {
		$readtipocoleta = read( 'contrato_tipo_coleta', "WHERE id = '$tipocoletaId'" );
		if ( !$readtipocoleta ) {
			header( 'Location: painel.php?execute=tipocoleta/error' );
		}
		foreach ( $readtipocoleta as $edit );
	}

?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Tipo Coleta</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a>
			</li>
			<li><a href="#">Cadastro</a>
			</li>
			<li><a href="painel.php?execute=suporte/cadastro/sistemas">tipocoleta<</a>
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
			$cad['residuo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['residuo'])));
			$cad['estado_fisico'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estado_fisico'])));
			$cad['volume_litros'] 	= strip_tags(trim(mysql_real_escape_string($_POST['volume_litros'])));
			$cad['peso_medio'] 	= strip_tags(trim(mysql_real_escape_string($_POST['peso_medio'])));
			$cad['peso_medio2'] 	= strip_tags(trim(mysql_real_escape_string($_POST['peso_medio2'])));
			$cad['peso_medio3'] 	= strip_tags(trim(mysql_real_escape_string($_POST['peso_medio3'])));
			$cad['valor_locacao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_locacao'])));
			$cad['valor_locacao'] = str_replace(",",".",str_replace(".","",$cad['valor_locacao']));
			$cad['valor_tratamento']= strip_tags(trim(mysql_real_escape_string($_POST['valor_tratamento'])));
			$cad['valor_tratamento'] = str_replace(",",".",str_replace(".","",$cad['valor_tratamento']));
			$cad['codigo_inea'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo_inea'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('contrato_tipo_coleta',$cad,"id = '$tipocoletaId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/cadastro/tipocoletas');
				unset($cad);
			}
		}
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['residuo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['residuo'])));
			$cad['estado_fisico'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estado_fisico'])));
			$cad['volume_litros'] 	= strip_tags(trim(mysql_real_escape_string($_POST['volume_litros'])));
			$cad['peso_medio'] 	= strip_tags(trim(mysql_real_escape_string($_POST['peso_medio'])));
			$cad['peso_medio2'] 	= strip_tags(trim(mysql_real_escape_string($_POST['peso_medio2'])));
			$cad['peso_medio3'] 	= strip_tags(trim(mysql_real_escape_string($_POST['peso_medio3'])));
			
			$cad['valor_locacao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_locacao'])));
			$cad['valor_locacao'] = str_replace(",",".",str_replace(".","",$cad['valor_locacao']));
			$cad['valor_tratamento']= strip_tags(trim(mysql_real_escape_string($_POST['valor_tratamento'])));
			$cad['valor_tratamento'] = str_replace(",",".",str_replace(".","",$cad['valor_tratamento']));	$cad['codigo_inea'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo_inea'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				create('contrato_tipo_coleta',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/cadastro/tipocoletas');
				unset($cad);
			}
		}
		if(isset($_POST['deletar'])){
			delete('contrato_tipo_coleta',"id = '$tipocoletaId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/cadastro/tipocoletas');
		}

	?>

				<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

					<div class="box-header with-border">
						<h3 class="box-title">Descriçao</h3>
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<div class="row">

							<div class="form-group col-xs-12 col-md-1">
								<label>Id</label>
								<input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled/>
							</div>

							<div class="form-group col-xs-12 col-md-3">
								<label>Nome</label>
								<input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control"/>
							</div>

							<div class="form-group col-xs-12 col-md-4">
								<label>Tipo de Resíduo</label>
								<select name="residuo" class="form-control">
									<option value="">Selecione o tipo residuo</option>
									<?php 
                                    $leitura = read('contrato_tipo_residuo',"WHERE id");
                                    if(!$leitura){
                                        echo '<option value="">Não temos tipo residuo no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['residuo'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?>
								</select>
							</div>


						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->

					<div class="box-header with-border">
						<h3 class="box-title">Valores</h3>
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<div class="row">
                            
                       <div class="form-group col-xs-12 col-md-3"> 
						<label>Valor Locaçao</label>
						<input type="text" name="valor_locacao" style="text-align:right" value="<?php echo converteValor($edit['valor_locacao']);?>" class="form-control" >
				       </div> 
				       
				             
                       <div class="form-group col-xs-12 col-md-3"> 
						<label>Valor Tratamento</label>
						<input type="text" name="valor_tratamento" style="text-align:right" value="<?php echo converteValor($edit['valor_tratamento']);?>" class="form-control" >
				       </div> 

						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->

					<div class="box-header with-border">
						<h3 class="box-title">Manifesto</h3>
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<div class="row">

							<div class="form-group col-xs-12 col-md-4">
								<label>Código  INEA</label>
								<input type="text" name="codigo_inea" value="<?php echo $edit['codigo_inea'];?>" class="form-control"/>
							</div>

							<div class="form-group col-xs-12 col-md-4">
								<label>Volume Litros</label>
								<input type="text" name="volume_litros" value="<?php echo $edit['volume_litros'];?>" class="form-control"/>
							</div>

							<div class="form-group col-xs-12 col-md-4">
								<label>Estado Físico</label>
								<select name="estado_fisico" class="form-control">
									<option value="">Selecione o Estado Físico</option>
									<?php 
                                    $leitura = read('contrato_tipo_estado_fisico',"WHERE id");
                                    if(!$leitura){
                                        echo '<option value="">Não temos Estado Físico no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['estado_fisico'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?>
								</select>
							</div>

						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->

					<div class="box-header with-border">
						<h3 class="box-title">Pesagem</h3>
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<div class="row">

							<div class="form-group col-xs-12 col-md-4">
								<label>Peso Médio (1)</label>
								<input type="text" name="peso_medio" value="<?php echo $edit['peso_medio'];?>" class="form-control"/>
							</div>
							
							<div class="form-group col-xs-12 col-md-4">
								<label>Peso Médio (2)</label>
								<input type="text" name="peso_medio2" value="<?php echo $edit['peso_medio2'];?>" class="form-control"/>
							</div>
							
							<div class="form-group col-xs-12 col-md-4">
								<label>Peso Médio (3)</label>
								<input type="text" name="peso_medio3" value="<?php echo $edit['peso_medio3'];?>" class="form-control"/>
							</div>


						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->

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