<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
 
	$data1 = date( "Y/m/d" );
	$data2 = date( "Y/m/d" );
	 
	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		$rotaRoteiro = $_POST['rota'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-ordem-realizadas-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaRoteiro = $_POST['rota'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-realizadas-excel.php' );
	}

	if ( isset( $_POST[ 'relatorio-rentabilidade-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-rentabilidade-excel.php' );
	}

	if ( isset( $_POST[ 'relatorio-pesagem-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
	header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-rentabilidade-pesagem-excel.php' );
		
	}

	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$rotaRoteiro = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];

	}

	$total = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND status='13'" );
	$leitura = read( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND status='13' ORDER BY rota ASC, hora ASC" );

	if(!empty($rotaRoteiro)){
		$total = conta('contrato_ordem',"WHERE id AND data>='$data1' AND data<='$data2' AND rota='$rotaRoteiro' AND status='13'");
		$leitura = read('contrato_ordem',"WHERE id AND data>='$data1' AND data<='$data2' AND rota='$rotaRoteiro' AND status='13' ORDER BY data ASC, hora ASC" );
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Rentabilidade</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviço</a></li>
            <li><a href="#">Rentabilidade</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
      <?php

		$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2'" );
		$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND status='12'" );
		$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND status='13'" );
		$ordemCancelada = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND status='15'" );
		$ordemNaoColetada = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND nao_coletada='1'" );
		$ordemZerada = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND quantidade1='0'" );
		 
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
						 <?php echo 'Coletadas '.$percentual. '%  || Realizadas : '. $ordemRealizada . '   || Em Aberto  : '. $ordemEmaberto . '   || Não Coletada : '. $ordemNaoColetada . '   || Zerada : '. $ordemZerada;?>
					  </span>
			   </div><!-- /.info-box bg-red -->
	 </div> <!-- /.box-header -->
		 
		 
		   <div class="box-header">
               <div class="row">
                   
                 <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
						 
                              <div class="form-group pull-left">
								<input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
							</div><!-- /.input-group -->


							 <div class="form-group pull-left">
								<input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
							</div><!-- /.input-group -->
 
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pesagem-excel" title="Relatório Pesagem Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                    </form> 
                  </div><!-- /col-xs-10 col-md-12 pull-right-->
	        </div><!-- /row-->   
       </div><!-- /box-header-->   

     
      <div class="box-header">
                             
                  <div class="col-xs-10 col-md-7 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
						
						 <div class="form-group pull-left">
                            <input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        
                         <div class="form-group pull-left">
                            <select name="rota" class="form-control input-sm">
                                <option value="">Selecione Rota</option>
                                <?php 
                                    $readBanco = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$readBanco){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readBanco as $mae):
                                           if($rotaRoteiro == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>
            			</div>
                        
                        <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                         </div>  <!-- /.input-group -->
                            
                          <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                          </div>   <!-- /.input-group -->
						 
						  <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-rentabilidade-excel"><i class="fa fa-file-excel-o" title="Relatório Rentabilidade Excel"></i></button>
                          </div>   <!-- /.input-group -->
                            
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
             </div><!-- /row-->  
              
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
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Coleta</td>
					<td>Prev</td>
					<td>Col</td>
					<td>Prev</td>
					<td>Col</td>
					<td align="center">Rota</td>
					<td>M</td>
					
					<td align="center" colspan="5">Gerenciar</td>
					
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
		
				
				$clienteId = $mostra['id_cliente'];	
				$contratoId = $mostra['id_contrato'];	
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td align="center">'.substr($contrato['controle'],0,6).'</td>';
			
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,10).'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
				echo '<td>'.$coleta['nome'].'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				
				echo '<td align="center">'.$coletaPrevisto['quantidade'].'</td>';
				echo '<td align="center">'.$mostra['quantidade1'].'</td>';
						
				echo '<td>'.$mostra['hora'].'</td>';
				echo '<td>'.$mostra['hora_coleta'].'</td>';
								
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';
		
				echo '<td align="center">'.$mostra['manifesto'].'</td>';
		
			
				echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Ordem" class="tip" />
              			</a>
				      </td>';
			   		  
 
				 echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Realizado" title="Baixar Ordem" class="tip" />
              			</a>
				      </td>';
				
				// imprimir ordem
				echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-contrato&ordemImprimir='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png" alt="Imprimir" title="Imprimir"  />
						</a>
					 </td>';	
	
			echo '</tr>';
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