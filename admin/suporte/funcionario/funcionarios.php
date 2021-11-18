<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$funcao = $_POST[ 'funcao' ];
		$_SESSION['funcao']=$funcao;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-funcionarios-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$funcao = $_POST[ 'funcao' ];
		$_SESSION['funcao']=$funcao;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-funcionarios-excel.php' );
	}

	$leitura = read('funcionario',"WHERE id ORDER BY nome ASC");
	$total = conta('funcionario',"WHERE id ORDER BY nome ASC");

	if (isset( $_POST[ 'pesquisar' ] ) ) {
		$funcao = $_POST['funcao'];
		if(!empty($funcao)){
			$leitura = read('funcionario',"WHERE id AND id_funcao='$funcao' ORDER BY nome ASC");
			$total =  conta('funcionario',"WHERE id AND id_funcao='$funcao'");
		}

	}

	if (isset( $_POST[ 'pesquisaNome' ] ) ) {
		$pesquisa = strip_tags( trim( mysql_real_escape_string( $_POST[ 'nome' ] ) ) );
		if ( !empty( $pesquisa ) ) {
			$leitura = read( 'funcionario', "WHERE id AND (nome LIKE '%$pesquisa%')
								 ORDER BY nome ASC" );
			$total = conta( 'funcionario', "WHERE id AND (nome LIKE '%$pesquisa%')" );
		}

	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?><head>
    <meta charset="iso-8859-1">
</head>



<div class="content-wrapper">
    <section class="content-header">
        <h1>Funcionarios</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
            </li>
            <li><a href="#">Cadastro</a>
            </li>
            <li class="active">Funcionarios</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">

                    <div class="box-header">
                        <a href="painel.php?execute=suporte/funcionario/funcionario-editar" class="btnnovo">
						  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
					 </a>
						
			  		<div class="box-header">
                     
                  <div class="col-xs-10 col-md-2 pull-left">
                        <form name="form-pesquisa" method="post" class="form-inline " role="form">
                            <div class="input-group">
                                <input type="text" name="nome" class="form-control input-sm" placeholder="Nome">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisaNome" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                <!-- /.input-group -->
                            </div>
                            <!-- /input-group-->
                        </form>
                   </div>
                  <!-- /col-xs-10 col-md-3 pull-left-->

       
        		 <div class="col-xs-8 col-md-6 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                   
						   <div class="form-group pull-left">
							  <select name="funcao" class="form-control input-sm">
								<option value="">Função</option>
									<?php 
									$readEmpresa = read('funcionario_funcao',"WHERE id ORDER BY nome ASC");
									if(!$readEmpresa){
												echo '<option value="">Nao temos funcao no momento</option>';	
									}else{
										foreach($readEmpresa as $mae):
										if($funcao == $mae['id']){
											echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
										 }else{
											echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
										}
										endforeach;	
									}
									?> 
								 </select>
						 </div>
                  
                        <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                    
		 </div>
           <!-- /.box-header -->

     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">


    <?php 

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td>Foto</td>
					<td>Nome</td>
					<td>Função</td>
					<td>Férias</td>
					<td>RG</td>
					<td>CPF</td>
					<td>Status</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
		
				if($mostra['fotoperfil']!= '' && file_exists('../uploads/funcionarios/'.$mostra['fotoperfil'])){
                        echo '<td align="center">
                              <img class="img-circle img-thumbnail" width="30" height="30" src="'.URL.'/uploads/funcionarios/'
                                         .$mostra['fotoperfil'].'">';
                      }else{
                       echo '<td align="center">
                              <img class="img-circle img-thumbnail" width="30" height="30" src="'.URL.'/site/images/autor.png">';
                	}	
			 		
				echo '<td>'.substr($mostra['nome'],0,25).'</td>';
		
				$funcaoId = $mostra['id_funcao'];
				$funcao = mostra('funcionario_funcao',"WHERE id ='$funcaoId'");
				echo '<td>'.substr($funcao['nome'],0,25).'</td>';
		
				echo '<td>'.converteData($mostra['ferias']).'</td>';
		
				echo '<td>'.$mostra['rg'].'</td>';
				echo '<td>'.$mostra['cpf'].'</td>';
		
				$statusId = $mostra['status'];
				$status = mostra('funcionario_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>'; 
				
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/funcionario/funcionario-editar&funcionarioEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/funcionario/funcionario-editar&funcionarioDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir" />
              			</a>
						</td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/funcionario/funcionario-editar&funcionarioVisualizar='.$mostra['id'].'">
							<img src="ico/servico.png" title="Lancamentos" />
              			</a>
						</td>';
			echo '</tr>';
		
		 endforeach;
		
		   echo '<tfoot>';
     			echo '<tr>';
                	echo '<td colspan="16">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
         echo '</tfoot>';
		
		echo '</table>';
	
		
		}
	?>

     <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
        </div><!-- /.box-footer-->

	    </div><!--/box-body table-responsive-->   
	  </div><!-- /.col-md-12 scrool -->
 	  </div><!-- /.box-body table-responsive -->
 	  	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->