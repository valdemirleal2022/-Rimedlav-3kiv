<?php 

	if ( function_exists( ProtUser ) ) {
			if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
				header( 'Location: painel.php?execute=suporte/403' );
			}
		}

	
	echo '<head>';
    echo '<meta charset="iso-8859-1">';
	echo '</head>';
	
	$_SESSION['banco']=$_SERVER['REQUEST_URI'];
     
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
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
        
     </div><!-- /.box-header -->
     <div class="box-body table-responsive">
<?php  
	 
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '20';
	$inicio = ($pag * $maximo) - $maximo;
	
	$leitura = read('empresa',"LIMIT $inicio,$maximo");
		if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Nome</td>
					<td align="center">Endereço</td>
					<td align="center">Telefone</td>
					<td align="center">Email</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['endereco'].'</td>';
				echo '<td>'.$mostra['telefone'].'</td>';
				echo '<td>'.$mostra['email'].'</td>';
				echo '<td align="center">
				<a href="painel.php?execute=suporte/cadastro/empresa-editar&empresaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
			echo '</tr>';
		 endforeach;
		echo '</table>';
		$link = 'painel.php?execute=empresa/cadastro/empresas&pag=';
	    pag('empresa',"", $maximo, $link, $pag);
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