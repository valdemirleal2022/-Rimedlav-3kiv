<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];
?><head>
	<meta charset="iso-8859-1">
</head>




<div class="content-wrapper">
    <section class="content-header">
        <h1>Tipo de Resíduos</h1>
        <ol class="breadcrumb">
            <li>Home</a>
                <li>Cadastro</a>
                    <li class="active">Tipo de Resíduos</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                        <a href="painel.php?execute=suporte/cadastro/contratotiporesiduo-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
                    
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        <?php 
	
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '20';
	$inicio = ($pag * $maximo) - $maximo;
	$total = conta('contrato_tipo_residuo',"WHERE id");
	
	$leitura = read('contrato_tipo_residuo',"WHERE id ORDER BY id ASC LIMIT $inicio,$maximo");
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td align="center">Valor Mínimo</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td align="left">'.$mostra['id'].'</td>';
				echo '<td align="left">'.$mostra['nome'].'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_minimo']).'</td>';
	
				echo '<td align="center">
				<a href="painel.php?execute=suporte/cadastro/contratotiporesiduo-editar&contratotiporesiduoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/cadastro/contratotiporesiduo-editar&contratotiporesiduoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
		          echo '<tr>';
                      echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
                      echo '</tr>';
                  echo '</tfoot>';
          echo '</table>'; 
                
          $link = 'painel.php?execute=site/cadastro/contratotiporesiduos&pag=';
           pag('contrato_tipo_residuo',"WHERE id ORDER BY id DESC", $maximo, $link, $pag);
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
                <!-- /.col-md-12 -->
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->