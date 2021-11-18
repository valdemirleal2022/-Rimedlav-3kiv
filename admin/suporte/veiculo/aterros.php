<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	echo '<head>';
    echo '<meta charset="iso-8859-1">';
	echo '</head>';
	
	$_SESSION['banco']=$_SERVER['REQUEST_URI'];
     
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Aterros</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li></a>
            <li class="active">Aterros</li>
          </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
          <div class="box-header">
            <a href="painel.php?execute=suporte/veiculo/aterro-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Novo Aterro</small>
			 </a>
    	</div><!-- /.box-header -->
    	
     <div class="box-body table-responsive">
<?php  
	 
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '20';
	$inicio = ($pag * $maximo) - $maximo;
	
	$leitura = read('aterro',"LIMIT $inicio,$maximo");
		if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Endereço</td>
					<td align="center">Bairro</td>
					<td align="center">Telefone</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['endereco'].'</td>';
				echo '<td>'.$mostra['bairro'].'</td>';
				echo '<td>'.$mostra['telefone'].'</td>';
			
				echo '<td align="center">
					<a href="painel.php?execute=suporte/veiculo/aterro-editar&aterroEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/aterro-editar&aterroDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir" />
              			</a>
						</td>';
			
			echo '</tr>';
			
		 endforeach;
		echo '</table>';
		$link = 'painel.php?execute=suporte/veiculo/aterro&pag=';
	    pag('aterro',"", $maximo, $link, $pag);
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