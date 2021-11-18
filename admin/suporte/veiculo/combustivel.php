<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	
 	$leitura = read('veiculo_combustivel',"WHERE id ORDER BY nome ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Combustível</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veiculo</a></li>
         <li class="active">Combustível</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
     	 <div class="box-header">
				<a href="painel.php?execute=suporte/veiculo/combustivel-editar" class="btnnovo">
				  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				  <smal> Novo Combustível </smal>
				 </a>
			</div><!-- /.box-header -->
     
        
     <div class="box-body table-responsive">
     
       <div class="box-body table-responsive data-spy='scroll'">
     			<div class="col-md-12 scrool">  
            
	<?php 

	if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Combustível</td>
					<td align="center">Estoque</td>
					<td align="center">Estoque Mínimo</td>
					<td align="center">Valor Unitário</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td align="right">'.$mostra['estoque'].'</td>';
				echo '<td align="right">'.$mostra['estoque_minimo'].'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_unitario']).'</td>';

				echo '<td align="center">
					<a href="painel.php?execute=suporte/veiculo/combustivel-editar&combustivelEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar"  />
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
       

	      </div><!--/col-md-12 scrool-->   
			</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->