<?php 

	if ( function_exists( 'ProtUser' ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
     
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contas</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Contas a Pagar</a>
            <li class="active">Contas</li>
          </ol>
 </section>
 <section class="content">
      <div class="box box-default">
      	<div class="box-header">
            <a href="painel.php?execute=suporte/pagar/pagar-conta-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
     </div><!-- /.box-header -->
		  
		  
     
       <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
     
	<?php 

	$leitura = read('pagar_conta',"WHERE id ORDER BY id_grupo ASC, codigo ASC");
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Conta</td>
					<td>Nome</td>
					<td>Previsão</td>
					<td>Despesas</td>
					<td>Grupo</td>
					<td>Status</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
				
		$total_previsao=0;
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				echo '<td>'.$mostra['codigo'].'</td>';
		
				echo '<td>'.$mostra['nome'].'</td>';
		
				echo '<td align="right">'.converteValor($mostra['previsao']).'</td>';
				$contaId = $mostra['id'];
				$mes = date('m/Y');
				$mesano = explode('/',$mes);
				$despesa = soma('pagar',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND id_conta ='$contaId'",'valor');
				echo '<td align="right">'.converteValor($despesa).'</td>';
				
				$grupoId = $mostra['id_grupo'];
				$grupo = read('pagar_grupo',"WHERE id ='$grupoId'");
				if(!$grupo){
					echo '<td align="center">Grupo Nao Encontrado</td>';
				}else{
					foreach($grupo as $mostraGrupo);
					echo '<td>'.$mostraGrupo['nome'].'</td>';
				}
		
				if($mostra['status']==1){
                        echo '<td align="center">Ativo</td>';
                    }else{
                        echo '<td align="center">Inativo</td>';
                }
				 
				echo '<td align="center">
				<a href="painel.php?execute=suporte/pagar/pagar-conta-editar&contaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/pagar/pagar-conta-editar&contaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
				
			echo '</tr>';
			$valor_total1=$valor_total1+$mostra['previsao'];
			$valor_total2=$valor_total2+$despesa;
		 endforeach;
		  echo '<tfoot>';
                       echo '<tr>';
                        echo '<td colspan="8">' . 'Total Previsto R$ : ' . number_format($valor_total1,2,',','.') . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="8">' . 'Total Gasto R$ : ' . number_format($valor_total2,2,',','.') . '</td>';
                        echo '</tr>';
						
						$valor_total3=$valor_total2-$valor_total1;
						echo '<tr>';
                        echo '<td colspan="8">' . 'Difereça R$ : ' . number_format($valor_total3,2,',','.') . '</td>';
                        echo '</tr>';
  
           echo '</tfoot>';
		 echo '</table>';
		 
		?>
        </div><!-- /.box-body -->
            
        <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
       </div><!-- /.box-footer-->
            
      </div><!-- /.box-body table-responsive -->
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 
 </section><!-- /.content -->

        
        <?php
		
		$grupo1='';
		$grupo2='';
		$grupo3='';
		$grupo4='';
		$grupo5='';
		$grupo6='';
		$grupo7='';
		$grupo8='';
		$grupo9='';
		$grupo10='';
		$total1=0;
		$total2=0;
		$total3=0;
		$total4=0;
		$total5=0;
		$total6=0;
		$total7=0;
		$total8=0;
		$total9=0;
		$total10=0;
		 
		$grupo = read('pagar_grupo',"WHERE id ORDER BY id ASC");
		if(!$grupo){
			echo '<div class="msgError">Não temos registros de Grupo no momento !</div><br />';
		 }else{
			foreach($grupo as $mostraGrupo):
			
				$nomegrupo = $mostraGrupo['nome'];
				$grupoId = $mostraGrupo['id'];
				
				$conta = read('pagar_conta',"WHERE id_grupo ='$grupoId'");
				$total=0;
				
				foreach($conta as $mostraConta):
					$total = $total+$mostraConta['previsao'];
				endforeach;
				
				if($grupo1 == ''){
				  $grupo1=$nomegrupo;
				  $total1=$total;
				}elseif($grupo2 == ''){ 
				  $grupo2=$nomegrupo;
				  $total2=$total;
				 }elseif($grupo3 == ''){ 
				  $grupo3=$nomegrupo;
				  $total3=$total;
				 }elseif($grupo4 == ''){ 
				  $grupo4=$nomegrupo;	
				  $total4=$total;
				  }elseif($grupo5 == ''){ 
				  $grupo5=$nomegrupo;
				  $total5=$total;
				  }elseif($grupo6 == ''){ 
				  $grupo6=$nomegrupo;
				  $total6=$total;
				   }elseif($grupo7 == ''){ 
				  $grupo7=$nomegrupo;
				  $total7=$total;
				   }elseif($grupo8 == ''){ 
				  $grupo8=$nomegrupo;
				  $total8=$total;
				   }elseif($grupo9 == ''){ 
				  $grupo9=$nomegrupo;
				  $total9=$total;
				   }elseif($grupo10 == ''){ 
				  $grupo10=$nomegrupo;
				  $total10=$total;
				}
				
			endforeach;
			}
		}
	?>
    
      <!--Grafico de pizza-->
     <div class="grafico2">        
          <script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Despesas', 'Mes Atual'],
				  [ '<?php echo $grupo1;?>',  <?php echo $total1;?>],
				  [ '<?php echo $grupo2;?>',  <?php echo $total2;?>],
				  [ '<?php echo $grupo3;?>',  <?php echo $total3;?>],
				  [ '<?php echo $grupo4;?>',  <?php echo $total4;?>],
				  [ '<?php echo $grupo5;?>',  <?php echo $total5;?>],
				  [ '<?php echo $grupo6;?>',  <?php echo $total6;?>],
				  [ '<?php echo $grupo7;?>',  <?php echo $total7;?>],
				  [ '<?php echo $grupo8;?>',  <?php echo $total8;?>],
				  [ '<?php echo $grupo9;?>',  <?php echo $total9;?>],
				  [ '<?php echo $grupo10;?>',  <?php echo $total10;?>],
				]);
		
				var options = {
				  title: 'Despesas por Grupo'
				};
		
				var chart = new google.visualization.PieChart(document.getElementById('grupo'));
				chart.draw(data, options);
			  }
			</script>
 <section class="content">
<div class="col-md-8">   
          <div class="box">
         	<div class="box-header">
        		  <div id="grupo"></div><!--/chat do google-->
          	</div><!-- /.box-header -->
    	</div><!-- /.box  -->
     </div><!-- /.box  -->

   </div>
         
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

