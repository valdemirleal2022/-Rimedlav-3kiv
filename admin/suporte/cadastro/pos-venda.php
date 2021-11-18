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
        <h1>Pos-Venda</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
            </li>
            <li><a href="#">Cadastro</a>
            </li>
            <li class="active">Pos-Venda</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">

                    <div class="box-header">
                        <a href="painel.php?execute=suporte/cadastro/pos-venda-editar" class="btnnovo">
						  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
					 </a>
                    

                    </div>
                    <!-- /.box-header -->

    <div class="box-body table-responsive">


    <?php 
	 
	
 	$leitura = read('contrato_pos_venda',"WHERE id ORDER BY id ASC");
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td>Foto</td>
					<td>Nome</td>
					<td>Telefone</td>
					<td>Email</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
		
				if($mostra['fotoperfil']!= '' && file_exists('../uploads/pos_vendas/'.$mostra['fotoperfil'])){
                        echo '<td align="center">
                              <img class="img-circle img-thumbnail" width="20" height="20" src="'.URL.'/uploads/pos_vendas/'
                                         .$mostra['fotoperfil'].'">';
                      }else{
                       echo '<td align="center">
                              <img class="img-circle img-thumbnail" width="20" height="20" src="'.URL.'/site/images/autor.png">';
                	}	
			 		
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['telefone'].'</td>';
				echo '<td>'.$mostra['email'].'</td>';
				
				echo '<td align="center">
				<a href="painel.php?execute=suporte/cadastro/pos-venda-editar&pos-vendaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/cadastro/pos-venda-editar&pos-vendaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png"  title="Excluir" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		
		 endforeach;
		
		echo '</table>';
		
	
		
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