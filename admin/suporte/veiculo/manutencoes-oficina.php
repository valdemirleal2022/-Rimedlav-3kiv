<?php 


	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();
 	
	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		 
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		 
	}
 
	$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
	
	$total = conta('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
 

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Manutenção</h1>
       <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Manutenção</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
       
    	
    
    	 <div class="box-header">
               <div class="row">
                   
                 <div class="col-xs-12 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
						 
                             <div class="form-group pull-left">
                                  <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
						    </div>
                             <div class="form-group pull-left">
                                  <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
							</div>
                     
                          
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            
                    </form> 
                  </div><!-- /col-xs-10 col-md-12 pull-right-->
	        </div><!-- /row-->   
       </div><!-- /box-header-->   
                    

     <div class="box-body table-responsive data-spy='scroll'">
     <div class="col-md-12 scrool">  
		<div class="box-body table-responsive">

	<?php 
				
	$totalManutencao=0;

	if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Veiculo</td>
					<td align="center">Placa</td>
					<td align="center">Tipo</td>
					<td align="center">Data</td>
					<td align="center">Descrição</td>
					<td align="center">Responsavel</td>
					<td align="center">Inicio</td>
					<td align="center">Termino</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 
			if($mostra['status1']=='0' && !empty($mostra['descricao1']) ){
			 
		 		echo '<tr>';
		
					echo '<td>'.$mostra['id'].'</td>';
					$veiculoId = $mostra['id_veiculo'];
					$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
					if(!$veiculo){
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
						}else{
						echo '<td>'.$veiculo['modelo'].'</td>';
						echo '<td>'.$veiculo['placa'].'</td>';
					}
		
					if($mostra['manutencao']=='1'){
						echo '<td align="center">Preventiva</td>';
					}elseif($mostra['manutencao']=='2'){
						echo '<td align="center">Corretiva</td>';
					}elseif($mostra['manutencao']=='3'){
						echo '<td align="center">Socorro</td>';
					}elseif($mostra['manutencao']=='4'){
						echo '<td align="center">Diversos</td>';
					}else{
						echo '<td align="center">-</td>';
					}
 
					echo '<td>'.converteData($mostra['data_solicitacao']).' | '.$mostra['hora_solicitacao'].'</td>';
		 
					echo '<td>'.substr($mostra['descricao1'],0,25).'</td>';
				
					$responsavelId = $mostra['responsavel1'];
					$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
					echo '<td>'.substr($responsavel['nome'],0,15).'</td>';
			 		
					if(!empty($mostra['inicio1'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['inicio1'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				
					if(!empty($mostra['termino1'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['termino1'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				   
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-1&manutencaoIniciar='.$mostra['id'].'">
								<img src="ico/aprovar.png" title="Iniciar" />
							</a>
						  </td>';
				
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-1&manutencaoBaixar='.$mostra['id'].'">
								<img src="ico/baixar.png" title="Baixar" />
							</a>
						  </td>';
			echo '</tr>';
		
			}

		
	    	if($mostra['status2']=='0' && !empty($mostra['descricao2']) ){
			 
		 		echo '<tr>';
		
					echo '<td>'.$mostra['id'].'</td>';
					$veiculoId = $mostra['id_veiculo'];
					$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
					if(!$veiculo){
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
						}else{
						echo '<td>'.$veiculo['modelo'].'</td>';
						echo '<td>'.$veiculo['placa'].'</td>';
					}
		
					if($mostra['manutencao']=='1'){
						echo '<td align="center">Preventiva</td>';
					}elseif($mostra['manutencao']=='2'){
						echo '<td align="center">Corretiva</td>';
					}elseif($mostra['manutencao']=='3'){
						echo '<td align="center">Socorro</td>';
					}elseif($mostra['manutencao']=='4'){
						echo '<td align="center">Diversos</td>';
					}else{
						echo '<td align="center">-</td>';
					}
 
					echo '<td>'.converteData($mostra['data_solicitacao']).' | '.$mostra['hora_solicitacao'].'</td>';
		 
					echo '<td>'.substr($mostra['descricao2'],0,25).'</td>';
				
					$responsavelId = $mostra['responsavel2'];
					$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
					echo '<td>'.substr($responsavel['nome'],0,15).'</td>';
			 		
					if(!empty($mostra['inicio2'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['inicio2'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				
					if(!empty($mostra['termino2'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['termino2'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				   
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-2&manutencaoIniciar='.$mostra['id'].'">
								<img src="ico/aprovar.png" title="Iniciar" />
							</a>
						  </td>';
				
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-2&manutencaoBaixar='.$mostra['id'].'">
								<img src="ico/baixar.png" title="Baixar" />
							</a>
			
					</td>';

				
		 echo '</tr>';
		
		 }

		if($mostra['status3']=='0' && !empty($mostra['descricao3']) ){
			 
		 		echo '<tr>';
		
					echo '<td>'.$mostra['id'].'</td>';
					$veiculoId = $mostra['id_veiculo'];
					$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
					if(!$veiculo){
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
						}else{
						echo '<td>'.$veiculo['modelo'].'</td>';
						echo '<td>'.$veiculo['placa'].'</td>';
					}
		
					if($mostra['manutencao']=='1'){
						echo '<td align="center">Preventiva</td>';
					}elseif($mostra['manutencao']=='2'){
						echo '<td align="center">Corretiva</td>';
					}elseif($mostra['manutencao']=='3'){
						echo '<td align="center">Socorro</td>';
					}elseif($mostra['manutencao']=='4'){
						echo '<td align="center">Diversos</td>';
					}else{
						echo '<td align="center">-</td>';
					}
 
					echo '<td>'.converteData($mostra['data_solicitacao']).' | '.$mostra['hora_solicitacao'].'</td>';
		 
					echo '<td>'.substr($mostra['descricao3'],0,25).'</td>';
				
					$responsavelId = $mostra['responsavel3'];
					$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
					echo '<td>'.$responsavel['nome'].'</td>';
			 		
					if(!empty($mostra['inicio3'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['inicio3'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				
					if(!empty($mostra['termino3'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['termino3'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				   
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-3&manutencaoIniciar='.$mostra['id'].'">
								<img src="ico/aprovar.png" title="Iniciar" />
							</a>
						  </td>';
				
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-3&manutencaoBaixar='.$mostra['id'].'">
								<img src="ico/baixar.png" title="Baixar" />
							</a>
						  </td>';
			  
			  	echo '</tr>';
		
			}
		
		 if($mostra['status4']=='0' && !empty($mostra['descricao4']) ){
			 
		 		echo '<tr>';
		
					echo '<td>'.$mostra['id'].'</td>';
					$veiculoId = $mostra['id_veiculo'];
					$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
					if(!$veiculo){
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
						}else{
						echo '<td>'.$veiculo['modelo'].'</td>';
						echo '<td>'.$veiculo['placa'].'</td>';
					}
		
					if($mostra['manutencao']=='1'){
						echo '<td align="center">Preventiva</td>';
					}elseif($mostra['manutencao']=='2'){
						echo '<td align="center">Corretiva</td>';
					}elseif($mostra['manutencao']=='3'){
						echo '<td align="center">Socorro</td>';
					}elseif($mostra['manutencao']=='4'){
						echo '<td align="center">Diversos</td>';
					}else{
						echo '<td align="center">-</td>';
					}
 
					echo '<td>'.converteData($mostra['data_solicitacao']).' | '.$mostra['hora_solicitacao'].'</td>';
		 
					echo '<td>'.substr($mostra['descricao4'],0,25).'</td>';
				
					$responsavelId = $mostra['responsavel4'];
					$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
					echo '<td>'.$responsavel['nome'].'</td>';
			 		
					if(!empty($mostra['inicio4'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['inicio4'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				
					if(!empty($mostra['termino4'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['termino4'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				   
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-4&manutencaoIniciar='.$mostra['id'].'">
								<img src="ico/aprovar.png" title="Iniciar" />
							</a>
						  </td>';
				
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-4&manutencaoBaixar='.$mostra['id'].'">
								<img src="ico/baixar.png" title="Baixar" />
							</a>
						  </td>';
			  
			  	echo '</tr>';
		
			}
		
		 if($mostra['status5']=='0' && !empty($mostra['descricao5']) ){
			 
		 		echo '<tr>';
		
					echo '<td>'.$mostra['id'].'</td>';
					$veiculoId = $mostra['id_veiculo'];
					$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
					if(!$veiculo){
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
						}else{
						echo '<td>'.$veiculo['modelo'].'</td>';
						echo '<td>'.$veiculo['placa'].'</td>';
					}
		
					if($mostra['manutencao']=='1'){
						echo '<td align="center">Preventiva</td>';
					}elseif($mostra['manutencao']=='2'){
						echo '<td align="center">Corretiva</td>';
					}elseif($mostra['manutencao']=='3'){
						echo '<td align="center">Socorro</td>';
					}elseif($mostra['manutencao']=='4'){
						echo '<td align="center">Diversos</td>';
					}else{
						echo '<td align="center">-</td>';
					}
 
					echo '<td>'.converteData($mostra['data_solicitacao']).' | '.$mostra['hora_solicitacao'].'</td>';
		 
					echo '<td>'.substr($mostra['descricao5'],0,25).'</td>';
				
					$responsavelId = $mostra['responsavel5'];
					$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
					echo '<td>'.$responsavel['nome'].'</td>';
			 		
					if(!empty($mostra['inicio5'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['inicio5'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				
					if(!empty($mostra['termino5'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['termino5'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				   
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-5&manutencaoIniciar='.$mostra['id'].'">
								<img src="ico/aprovar.png" title="Iniciar" />
							</a>
						  </td>';
				
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-5&manutencaoBaixar='.$mostra['id'].'">
								<img src="ico/baixar.png" title="Baixar" />
							</a>
						  </td>';
			  
			  	echo '</tr>';
		
			}
		
		 if($mostra['status6']=='0' && !empty($mostra['descricao6']) ){
			 
		 		echo '<tr>';
		
					echo '<td>'.$mostra['id'].'</td>';
					$veiculoId = $mostra['id_veiculo'];
					$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
					if(!$veiculo){
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
						}else{
						echo '<td>'.$veiculo['modelo'].'</td>';
						echo '<td>'.$veiculo['placa'].'</td>';
					}
		
					if($mostra['manutencao']=='1'){
						echo '<td align="center">Preventiva</td>';
					}elseif($mostra['manutencao']=='2'){
						echo '<td align="center">Corretiva</td>';
					}elseif($mostra['manutencao']=='3'){
						echo '<td align="center">Socorro</td>';
					}elseif($mostra['manutencao']=='4'){
						echo '<td align="center">Diversos</td>';
					}else{
						echo '<td align="center">-</td>';
					}
 
					echo '<td>'.converteData($mostra['data_solicitacao']).' | '.$mostra['hora_solicitacao'].'</td>';
		 
					echo '<td>'.substr($mostra['descricao6'],0,25).'</td>';
				
					$responsavelId = $mostra['responsavel6'];
					$responsavel = mostra('veiculo_manutencao_responsavel',"WHERE id ='$responsavelId'");
					echo '<td>'.$responsavel['nome'].'</td>';
			 		
					if(!empty($mostra['inicio6'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['inicio6'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				
					if(!empty($mostra['termino6'])){
						echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['termino6'])).'</td>';	
					}else{
						echo '<td>-</td>';	
					}
				   
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-6&manutencaoIniciar='.$mostra['id'].'">
								<img src="ico/aprovar.png" title="Iniciar" />
							</a>
						  </td>';
				
					echo '<td align="center">
							<a href="painel.php?execute=suporte/veiculo/manutencao-oficina-6&manutencaoBaixar='.$mostra['id'].'">
								<img src="ico/baixar.png" title="Baixar" />
							</a>
						  </td>';
			  
			  	echo '</tr>';
		
			}



		
		 endforeach;
		
		 echo '<tfoot>';
         		 
				echo '<tr>';
                	echo '<td colspan="15">' . 'Total de Registros : ' .  $total . '</td>';
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