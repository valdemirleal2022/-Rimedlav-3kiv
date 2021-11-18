<?php 

	if ( function_exists( ProtUser ) ) {
			if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
				header( 'Location: painel.php?execute=suporte/403' );
			}
		}

	
	echo '<head>';
    echo '<meta charset="iso-8859-1">';
	echo '</head>';
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
     
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Empresa</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Cadastro</a>
            <li class="active">Empresa</li>
          </ol>
 </section>
 
<section class="content">
      <div class="box box-default">
		  
      	<div class="box-header">
            <a href="painel.php?execute=suporte/pagar/empresa-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
     </div><!-- /.box-header -->
		  
     <div class="box-body table-responsive">
<?php  
	 
 
	$leitura = read('empresa_pagar',"WHERE id");
		if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Nome</td>
					<td align="center">CNPJ</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['cnpj'].'</td>';
			
				echo '<td align="center">
					<a href="painel.php?execute=suporte/pagar/empresa-editar&empresaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
			
				echo '<td align="center">
							<a href="painel.php?execute=suporte/pagar/empresa-editar&empresaDeletar='.$mostra['id'].'">
								<img src="ico/excluir.png" title="Excluir" />
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
       </div><!-- /.box-footer-->

    </div><!-- /.box-body table-responsive -->
    
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->