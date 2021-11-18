<?php

if ( function_exists( ProtUser ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>


<div class="content-wrapper">
    <section class="content-header">
        <h1>Coletores</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
            </li>
            <li><a href="#">Cadastro</a>
            </li>
            <li class="active">Coletores</li>
        </ol>
    </section>

  <section class="content">

      <div class="row">
         <div class="col-md-12">
          <div class="box box-default">

          <div class="box-header">
              <a href="painel.php?execute=suporte/cadastro/coletor-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
         </a>
         </div>
      <!-- /.box-header -->

    <div class="box-body table-responsive">

    <?php 
 
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '20';
	$inicio = ($pag * $maximo) - $maximo;
	
 	$leitura = read('contrato_coletor',"WHERE id ORDER BY id ASC LIMIT $inicio,$maximo");
	if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td>Foto</td>
					<td>Nome</td>
					<td>Nome Completo</td>
					<td>Telefone</td>
					<td>CPF</td>
					<td>RG</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				 
				
				if($mostra['fotoperfil']!= '' && file_exists('../uploads/coletores/'.$mostra['fotoperfil'])){
                        echo '<td align="center">
                              <img class="img-circle img-thumbnail" width="50" height="50" src="'.URL.'/uploads/coletores/'
                                         .$mostra['fotoperfil'].'">';
                      }else{
                        echo '<td align="center">
                             <i class="fa fa-picture-o"></i>
                         </td>';
                }	
							
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['nome_completo'].'</td>';
				echo '<td>'.$mostra['telefone'].'</td>';
				echo '<td>'.$mostra['cpf'].'</td>';
				echo '<td>'.$mostra['rg'].'</td>';
				
				echo '<td align="center">
				<a href="painel.php?execute=suporte/cadastro/coletor-editar&coletorEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/cadastro/coletor-editar&coletorDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		
		 endforeach;
		
		echo '</table>';
		
		$link = 'painel.php?execute=suporte/cadastro/coletor&pag=';
	     pag('contrato_coletor',"WHERE id ORDER BY id ASC", $maximo, $link, $pag);
		
		}
	?>

       <div class="box-footer">
          <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
		?>
                        </div>
                        <!-- /.box-footer-->

                    </div>
                    <!-- /.box-body table-responsive -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->

</div> <!-- /.content-wrapper -->