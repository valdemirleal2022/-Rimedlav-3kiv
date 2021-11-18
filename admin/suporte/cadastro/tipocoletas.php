<?php 

		 if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}

		$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Tipo de Coleta</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Cadastro</a>
            <li class="active">Tipo de Coleta</li>
          </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

		   <div class="box-header">
				<a href="painel.php?execute=suporte/cadastro/tipocoleta-editar" class="btnnovo">
				<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" /> Novo Tipo
				</a>
		   </div><!-- /.box-header -->

       
   <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
     
	<?php 
	
	$total = conta('contrato_tipo_coleta',"WHERE id");
	$leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY id ASC");
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td align="center">Tipo Resíduo</td>
					<td align="center">Estado Físico</td>
					<td align="center">Volume Litros</td>
					<td align="center">Valor Locação</td>
					<td align="center">P Médio (1)</td>
					<td align="center">P Médio (2)</td>
					<td align="center">P Médio (3)</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
				echo '<td align="left">'.$mostra['id'].'</td>';
				echo '<td align="left">'.$mostra['nome'].'</td>';
				
				$tipoResiduoId=$mostra['residuo'];
			    $tipoResiduo= mostra('contrato_tipo_residuo',"WHERE id ='$tipoResiduoId'");
				echo '<td align="left">'.$tipoResiduo['nome'].'</td>';
		
	        	$estadoFisicoId=$mostra['estado_fisico'];
			    $estadoFisico = mostra('contrato_tipo_estado_fisico',"WHERE id ='$estadoFisicoId'");
				echo '<td align="left">'.$estadoFisico['nome'].'</td>';
		
				echo '<td align="right">'.$mostra['volume_litros'].'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_locacao']).'</td>';
		
				echo '<td align="right">'.$mostra['peso_medio'].'</td>';
				echo '<td align="right">'.$mostra['peso_medio2'].'</td>';
				echo '<td align="right">'.$mostra['peso_medio3'].'</td>';

				echo '<td align="center">
				<a href="painel.php?execute=suporte/cadastro/tipocoleta-editar&tipocoletaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/cadastro/tipocoleta-editar&tipocoletaDeletar='.$mostra['id'].'">
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