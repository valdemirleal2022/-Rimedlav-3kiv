<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data2' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		
		$rotaId = $_POST['rota'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-ordem-emaberto-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data2' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		
		$rotaId = $_POST['rota'];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-emaberto-excel.php' );
	}


	if(isset($_POST['pesquisar_numero'])){
		$ordemId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($ordemId)){
			echo '<div class="alert alert-warning">Número Inválido!</div><br />';
		}else{
			header('Location: painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$ordemId);
		}
		$rotaId = $_POST['rota'];
		$dataRoteiro=$_POST['data1'];
	}

	if(isset($_POST['pesquisar'])){
		$dataRoteiro=$_POST['data1'];
		$rotaRoteiro = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$_SESSION[ 'dataRoteiro' ] = $dataRoteiro;
		
		$total = conta( 'contrato_ordem', "WHERE id AND data='$dataRoteiro' AND status='12'" );
		$leitura = read( 'contrato_ordem', "WHERE id AND data='$dataRoteiro' AND status='12'
						ORDER BY rota ASC, hora ASC" );
	}

	if(!empty($rotaRoteiro)){
		$total = conta('contrato_ordem',"WHERE id AND data='$dataRoteiro' AND rota='$rotaRoteiro' AND status='12'");
		$leitura = read('contrato_ordem',"WHERE id AND data='$dataRoteiro'  AND rota='$rotaRoteiro' AND status='12' ORDER BY data ASC, hora ASC" );
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Ordem de Serviço Em Aberto</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviço</a></li>
            <li><a href="painel.php?execute=suporte/ordem/emissao-ordem">Em Aberto</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
      <?php

		$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$dataRoteiro'" );
		$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND data='$dataRoteiro' AND status='12'" );
		$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$dataRoteiro' AND status='13'" );
		$ordemCancelada = conta( 'contrato_ordem', "WHERE id AND data='$dataRoteiro' AND status='15'" );
		 
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
              
                <div class="row">
                     <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="numero">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
                   
                  <div class="col-xs-10 col-md-7 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataRoteiro)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
						 
                        <div class="form-group pull-left">
                            <input name="data2" type="date" value="<?php echo date('Y-m-d',strtotime($dataRoteiro)) ?>" class="form-control input-sm" >
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
             </div><!-- /row-->  
              
       </div><!-- /box-header-->   
   
     <div class="box-body table-responsive">
    
	<?php 
		 
			 
	if($leitura){
					
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Coleta</td>
					<td>Q Prev</td>
					<td>Q Col</td>
					<td>H Prev</td>
					<td>H Col</td>
					<td align="center">Rota</td>
					<td>M</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
		
				
				$ordemId = $mostra['id'];	
				$clienteId = $mostra['id_cliente'];	
				$contratoId = $mostra['id_contrato'];	
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				echo '<td align="center">'.$mostra['id'].'</td>';
				if($contrato){
					echo '<td align="center">'.substr($contrato['controle'],0,6).'</td>';
				}else{
					echo '<td align="center">!!!!!</td>';
				}
				
			
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
		
			    $statusId = $mostra['status'];
                $status = mostra('contrato_status',"WHERE id ='$statusId'");
		
				//baixa automatica
				$cad['status'] = '13';
				$cad['interacao']= date('Y/m/d H:i:s');
				$cad['quantidade1'] = 0;
		  		$cad['observacao'] = 'Sem Lixo (Covid-19)';
			 	//update('contrato_ordem',$cad,"id = '$ordemId'");	
				 
				
				echo '<td>'.$status['nome'].'</td>';
		
			
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
				
			echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$contrato['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
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

	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->