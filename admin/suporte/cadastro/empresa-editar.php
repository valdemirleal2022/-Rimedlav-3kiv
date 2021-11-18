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
		$readempresa = read( 'empresa', "WHERE id = '$empresaId'" );
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
            <li><a href="painel.php?execute=suporte/pagar/bancos">Empresa</a>
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
			$cad['endereco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['bairro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$cad['cep'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['uf'] 	= strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
			$cad['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$cad['inscricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
			$cad['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['celular'] 	= strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
			$cad['email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
            $cad['responsavel'] 	= strip_tags(trim(mysql_real_escape_string($_POST['responsavel'])));
            $cad['inea'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inea'])));
            $cad['inmetro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inmetro'])));
            $cad['responsavel'] 	= strip_tags(trim(mysql_real_escape_string($_POST['responsavel'])));
            $cad['codigo_servico_federal'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo_servico_federal'])));
            $cad['codigo_servico_municipal'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo_servico_municipal'])));
            $cad['iss']= strip_tags(trim(mysql_real_escape_string($_POST['iss'])));
            $cad['optante_simples'] = strip_tags(trim(mysql_real_escape_string($_POST['optante_simples'])));
			$cad['nota_descricao'] 	= $_POST['nota_descricao'];
            
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				$cad['contrato'] = mysql_real_escape_string($_POST['contrato']);
				update('empresa',$cad,"id = '$empresaId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/cadastro/empresa');
				unset($cad);
			}
		}
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				create('empresa',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/cadastro/empresa');
				unset($cad);
			}
		}
		if(isset($_POST['deletar'])){
			update('empresa',$cad,"id = '$empresaId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/cadastro/empresa');
		}

	?>

       <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
            
             <div class="box-header with-border">
                  <h3 class="box-title">Dadosda Empresa</h3>
             </div>
             <!-- /.box-header -->

                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-xs-12 col-md-2">
                                <label>Id</label>
                                <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled/>
                            </div>

                            <div class="form-group col-xs-12 col-md-10">
                                <label>Empresa</label>
                                <input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Endereço</label>
                                <input type="text" name="endereco" value="<?php echo $edit['endereco'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-2">
                                <label>Bairro</label>
                                <input type="text" name="bairro" value="<?php echo $edit['bairro'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-2">
                                <label>Cidade</label>
                                <input type="text" name="cidade" value="<?php echo $edit['cidade'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-2">
                                <label>Cep</label>
                                <input type="text" name="cep" value="<?php echo $edit['cep'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-2">
                                <label>UF</label>
                                <select name="uf" class="form-control">
                    	    <option value="<?php echo $edit['uf'];?>"><?php echo $edit['uf'];?></option>>
                        	<option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AM">AM</option>
                            <option value="AP">AP</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MG">MG</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="PR">PR</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="RS">RS</option>
                            <option value="SC">SC</option>
                            <option value="SE">SE</option>
                            <option value="SP">SP</option>
                            <option value="TO">TO</option>
                        </select>
                     </div>


                            <div class="form-group col-xs-12 col-md-3">
                                <label>CNPJ</label>
                                <input type="text" name="cnpj" class="form-control" value="<?php echo $edit['cnpj'];?>"/>
                            </div>
                            
                            <div class="form-group col-xs-12 col-md-3">
                                <label>Inscrição</label>
                                <input type="text" name="inscricao" class="form-control" value="<?php echo $edit['inscricao'];?>"/>
                            </div>


                            <div class="form-group col-xs-12 col-md-3">
                                <label>Telefone</label>
                                <input name="telefone" class="form-control" type="text" value="<?php echo $edit['telefone'];?>"/>
                            </div>
							
							  <div class="form-group col-xs-12 col-md-3">
                                <label>Celular</label>
                                <input name="celular" class="form-control" type="text" value="<?php echo $edit['celular'];?>"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Email</label>
                                <input name="email" class="form-control" type="text" value="<?php echo $edit['email'];?>"/>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->

                    <div class="box-header with-border">
                        <h3 class="box-title">Dados dos Manifesto</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <div class="row">

                            <div class="form-group col-xs-12 col-md-4">
                                <label>INEA</label>
                                <input name="inea" class="form-control" type="text" value="<?php echo $edit['inea'];?>"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Inmetro</label>
                                <input name="inmetro" class="form-control" type="text" value="<?php echo $edit['inmetro'];?>"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Responsável pela Empresa</label>
                                <input name="responsavel" class="form-control" type="text" value="<?php echo $edit['responsavel'];?>"/>
                            </div>

                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->


                    <div class="box-header with-border">
                        <h3 class="box-title">Dados da Nota Fiscal Eletrônica (Arquivo Remessa) </h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <div class="row">
                           
                            <div class="form-group col-xs-12 col-md-3">
                                <label>Código do Serviço Federal</label>
                                <input name="codigo_servico_federal" class="form-control" type="text" value="<?php echo $edit['codigo_servico_federal'];?>"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Código do Serviço Municipal</label>
                                <input name="codigo_servico_municipal" class="form-control" type="text" value="<?php echo $edit['codigo_servico_municipal'];?>"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>ISS (Aliquota)</label>
                                <input name="iss" class="form-control" type="text" value="<?php echo $edit['iss'];?>"/>
                            </div>
                            
                              <div class="form-group col-xs-3">
                                <label>Optante pelo Simples</label>
                                <select name="optante_simples" class="form-control" >
                                  <option value="">Selecione</option>
                                  <option <?php if($edit['optante_simples'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                                  <option <?php if($edit['optante_simples'] == '0') echo' selected="selected"';?> value="0">Nao</option>
                                 </select>
                            </div><!-- /.row -->

                              	
							<div class="form-group col-xs-12 col-md-12"> 
									<label>Texto Nota Fiscal </label>
									<textarea  name="nota_descricao" rows="5" cols="120" class="form-control" ><?php echo htmlspecialchars($edit['nota_descricao']);?>
									</textarea>
							 </div>  
                        
                       <!-- /.row -->
                   </div>
                    <!-- /.box-body --> 
                    
      
                   <div class="box-body">
                        <div class="row">
                        
							<div class="form-group col-xs-12 col-md-12"> 
								<label>Contrato</label>
									<textarea id="editor-texto" name="contrato" rows="8" cols="80">
										<?php echo htmlspecialchars($edit['contrato']);?>
									</textarea>
							 </div>  
                        
                       <!-- /.row -->
                   </div>
                    <!-- /.box-body -->

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