<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}


	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data1'];
		$total = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro'" );
		$leitura = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' 
							ORDER BY rota ASC, hora ASC" );
	}


	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Emissão de Baixa</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviço</a></li>
            <li><a href="painel.php?execute=suporte/ordem/emissao-ordem">Emissão</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
     <?php

		$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro'" );
		$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND status='12'" );
		$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND status='13'" );
		$ordemCancelada = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND status='15'" );
		 
		$ordemBaixada = $ordemRealizada+$ordemCancelada;

	 ?>

 <div class="box-header"> 
         <div class="info-box bg-blue">
                <span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Ordem de Serviço</span>
                  <span class="info-box-number"><?php echo $ordemTotal;?></span>
                  <div class="progress">
                    <?php
					 $percentual=($ordemBaixada/$ordemTotal)*100;
					 $percentual=intval($percentual);
					?>	
                    <div class="progress-bar" style="width: <?php echo $percentual;?>%"></div>
                  </div>
                  <span class="progress-description">
                     <?php echo 'Coletadas '.$percentual. '%  || Realizadas : '. $ordemRealizada . '   || Em Aberto  : '. $ordemEmaberto;?>
                  </span>
           </div><!-- /.info-box bg-red -->
 </div> <!-- /.box-header -->

     
      <div class="box-header">
                              
                  <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        
                      
                        <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
       
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
             </div><!-- /row-->  
              
       </div><!-- /box-header-->   
   
  <div class="box-body table-responsive">
    
     <div class="box-body table-responsive data-spy='scroll'">
     			<div class="col-md-12 scrool">  
    
	<?php 
		 
//$dataOrdem='!'.converteData($dataroteiro);
//	$ordemRealizada = read('ordem_realizada',"WHERE contrato='!095439' 
//						AND data='$dataOrdem'");
//  
//	if(!$ordemRealizada){
//		echo 'NÃO ENCONTRADO!';
//		header('Location: painel.php?execute=suporte/403');
//	}else{
//		echo $ordemRealizada['nome'].'<br>';	
//	}
								   
	  
								   
	if($leitura){
					
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td align="center">Coleta</td>
					<td>Quant</td>
					<td>Controle</td>
					<td>Quant</td>
					<td align="center">Rota</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';

				$ordemId = $mostra['id'];	
				$clienteId = $mostra['id_cliente'];	
				$contratoId = $mostra['id_contrato'];
		
			$contrato = mostra('contrato',"WHERE id ='$contratoId'");
			if($contrato['contrato_tipo']>'7'){
					
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
			
		
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td align="center">'.substr($contrato['controle'],0,6).'</td>';
			
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		
				$tipoColeta[ 'tipo_coleta1' ] = $mostra['tipo_coleta1'];

				echo '<td>'.$coleta['nome'].'</td>';
		
				$contratoId = $mostra['id_contrato'];

				echo '<td align="center">'.$mostra['quantidade1'].'</td>';
		
				//baixa automatica
				$controleId='!'.substr($contrato['controle'],0,6);
				$dataOrdem='!'.converteData($dataroteiro);
				
				$ordemRealizada = mostra('ordem_realizada',"WHERE contrato='$controleId' AND data='$dataOrdem'");
		
						
				if($ordemRealizada){
					
					$quantidade=$ordemRealizada['quantidade'];
					$nao_coletada=0;
					
					if($ordemRealizada['situacao']=='N'){
						$nao_coletada=1;
					}
					if($ordemRealizada['situacao']=='T'){
						$nao_coletada=1;
					}
					
					$quantidade=$ordemRealizada['quantidade'];

					echo '<td align="center">'.$ordemRealizada['contrato'].'</td>';
					echo '<td align="center">'.$ordemRealizada['quantidade'].'</td>';
			
					if( $mostra[ 'tipo_coleta1' ]=='1'){
						$cad['tipo_coleta1']='19';
					}
					if( $mostra[ 'tipo_coleta1' ]=='2'){
						$cad['tipo_coleta1']='20';
					}
					if($mostra[ 'tipo_coleta1' ]=='8'){
						$cad['tipo_coleta1']='21';
					}
					if($mostra[ 'tipo_coleta1' ]=='9'){
						$cad['tipo_coleta1']='22';
					}

					$cad['quantidade1'] = $quantidade;
					$cad['nao_coletada'] = $nao_coletada;
					$cad['status'] = '13';
					$cad['interacao']= date('Y/m/d H:i:s');
					update('contrato_ordem',$cad,"id = '$ordemId'");
					
				}else{
		
					echo '<td align="center">-</td>';
					echo '<td align="center">-</td>';

				}

				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';
		
			    $statusId = $mostra['status'];
                $status = mostra('contrato_status',"WHERE id ='$statusId'");
		
				$coletaPrevisto = mostra('ordem_realizada',"WHERE contrato ='$contratoId'");
			
				if($statusId==12){
					echo '<td>'.$status['nome'].'</td>';
				}else if($statusId==15){
				   echo '<td align="center"><span class="badge bg-red">'.$status['nome'].'</span></td>';
			    }else if($statusId==13){
					echo '<td align="center"><span class="badge bg-light-blue">'.$status['nome'].'</span></td>';
				}

 
				 echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Realizado" title="Baixar Ordem" class="tip" />
              			</a>
				      </td>';
		
				 echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Realizado" title="Baixar Ordem" class="tip" />
              			</a>
				      </td>';

			echo '</tr>';
	
		  }
				
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
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