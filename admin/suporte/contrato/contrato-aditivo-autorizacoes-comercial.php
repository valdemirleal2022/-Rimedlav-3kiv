 <meta charset="iso-8859-1">

<?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
 
	
	$leitura = read('contrato_aditivo',"WHERE id AND aprovacao_comercial='3' ORDER BY id ASC");
	$total = conta('contrato_aditivo',"WHERE id AND aprovacao_comercial='3' ORDER BY id ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contrato Aditivos -Liberação Comercial</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contrato</li>
           <li>Aditivos</li>
         </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

           
	 <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
  
   
	<?php 
	
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					
					<td align="center">Consultor</td>
					
					<td align="center">Aprovação</td>
					<td align="center">Inicio</td>
					
					<td align="center">Motivo</td>
					<td align="center">Tipo Coleta</td>

					<td align="center">Quant</td>
					<td align="center">Valor</td>
					<td align="center">Hora</td>
					<td align="center">Rota</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
		
				if( $mostra['solicitacao_consultor']){
					$consultorId = $mostra['consultor'];
					$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
					echo '<td>'.$consultor['nome'].'</td>';
				}
		
				if( $mostra['solicitacao_pos_venda']){
					$consultorId = $mostra['pos_venda'];
					$consultor = mostra('contrato_pos_venda',"WHERE id ='$consultorId '");
					echo '<td>'.$consultor['nome'].'</td>';
				}
		
				echo '<td align="left">'.converteData($mostra['aprovacao']).'</td>';
				echo '<td align="left">'.converteData($mostra['inicio']).'</td>';
		
				$motivoId=$mostra['motivo'];		
				$motivo= mostra('contrato_aditivo_motivo',"WHERE id ='$motivoId'");
				echo '<td align="left">'.substr($motivo['nome'],0,22).'</td>';

				
				$tipoColetaId=$mostra['tipo_coleta_aditivo'];		
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					
				echo '<td align="left">'.substr($tipoColeta['nome'],0,10).'</td>';
				echo '<td align="center">'.$mostra['quantidade_aditivo'].'</td>';	
				echo '<td align="right">'.converteValor($mostra['valor_unitario_aditivo']).'</td>';
				echo '<td align="right">'. $mostra['hora'].'</td>';
				echo '<td align="right">'. $mostra['rota'].'</td>';

		
				echo '<td align="center">
                       <a href="painel.php?execute=suporte/contrato/contrato-aditivo-consultor&autorizarComercial='.$mostra['id'].'">
                            <img src="ico/baixar.png"  title="Autorizar"  />
                          </a>
                      </td>';
					
				 	
		
		
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="13">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                 
  
         echo '</tfoot>';
		 
		 echo '</table>';
		 
		
	    
		}
	?>
				
    	</div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
       
      <div class="box-footer">
		  
		<?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
		?> 
		  
	  </div><!-- /.box-footer-->
     
     </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->