<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
	
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	}

	$total = conta('agenda',"WHERE id AND status='1' AND retorno>='$data1' 
											  AND retorno<='$data2'");
	$leitura = read('agenda',"WHERE id AND status='1' AND retorno>='$data1' 
											  AND retorno<='$data2' ORDER BY retorno ASC");

	
	$_SESSION['contrato-editar']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Agenda</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li class="active">Agenda</li>
          </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

          	<div class="box-header">	    
                    <div class="col-xs-10 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                       <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                        
                        
                       <div class="form-group pull-left">
                        	 <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>   
                       </div><!-- /.input-group -->  
                                
                    </form> 
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
          </div><!-- /box-header-->   
 
      <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
    
      
	<?php 


	
	if($leitura){
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td>Nome</td>
					<td align="center">Atendente</td>
					<td align="center">Observação</td>
					<td align="center">Interação</td>
					<td align="center">Retorno</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				echo '<td>'.$contrato['id'].'</td>';
				echo '<td>'.substr($contrato['controle'],0,6).'</td>';
		
				echo '<td align="left">'.substr($cliente['nome'],0,35).'</td>';
	
				$atendenteId = $mostra['atendente'];
				$atendente = mostra('contrato_atendente',"WHERE id ='$atendenteId '");
				echo '<td align="left">'.$atendente['nome'].'</td>';
				echo '<td align="left">'.substr($mostra['descricao'],0,50).'</td>';
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['retorno'])).'</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/agenda/agenda-editar&agendaEditar='.$mostra['id'].'">
			  				<img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/agenda/agenda-editar&agendaBaixar='.$mostra['id'].'">
			  				<img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/agenda/agenda-editar&agendaDeletar='.$mostra['id'].'">
			  				<img src="../admin/ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
              			</a>
				      </td>';	
		
					echo '<td align="center">
                                <a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contrato['id'].'">
                                    <img src="../admin/ico/visualizar.png" title="Visualizar"  />
                                  </a>
                                 </td>';
		
			echo '</tr>';
		endforeach;
		 
		echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
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

	    </div><!--/col-md-12 scrool-->   
	  </div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
 	  	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->