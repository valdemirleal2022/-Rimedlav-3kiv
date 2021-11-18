<?php 

	if(function_exists(ProtUser)){
		
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
		
	}

	$dataInicio = date( "Y/m/d" );
		$dataFinal = date( "Y/m/d" );

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$dataInicio=$_POST['data1'];
		$dataFinal=$_POST['data2'];
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data2' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		
		$rotaId = $_POST['rota'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-conferencia-pdf");';
		echo '</script>';
		
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$dataInicio=$_POST['data1'];
		$dataFinal=$_POST['data2'];
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data2' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		
		$rotaId = $_POST['rota'];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-conferencia-excel.php' );
		
	}

	if(isset($_POST['pesquisar_numero'])){
		
		$ordemId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($ordemId)){
			echo '<div class="alert alert-warning">Número Inválido!</div><br />';
		}else{
			header('Location: painel.php?execute=suporte/ordem/ordem-baixar&ordemBaixar='.$ordemId);
		}
		$rotaId = $_POST['rota'];
		$dataInicio=$_POST['data1'];
		
	}

	//if (!isset( $_SESSION[ 'dataInicio' ] ) ) {
//		$dataInicio = date( "Y/m/d" );
//		$dataFinal = date( "Y/m/d" );
//		$_SESSION[ 'dataInicio' ] = $dataInicio;
//		$_SESSION[ 'dataFinal' ] = $dataFinal ;
//	} else {
//		$dataInicio = $_SESSION[ 'dataInicio' ];
//		$dataFinal = $_SESSION[ 'dataFinal' ];
//	}

	if(isset($_POST['pesquisar'])){
		
		$dataInicio=$_POST['data1'];
		$dataFinal=$_POST['data2'];
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data2' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];

	}

	$pag = ( empty( $_GET[ 'pag' ] ) ? '1' : $_GET[ 'pag' ] );
	$maximo = '50';
	$inicio = ( $pag * $maximo ) - $maximo;
		
	$total = conta( 'contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal'" );
	$leitura = read( 'contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal' 
						ORDER BY rota ASC, hora ASC LIMIT $inicio,$maximo" );

	if(!empty($rotaId)){
		
		$total = conta('contrato_ordem',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' AND rota='$rotaId'");
		$leitura = read('contrato_ordem',"WHERE id  AND data>='$dataInicio' AND data<='$dataFinal' AND rota='$rotaId' ORDER BY data DESC, hora ASC");

	}
		

	$ordemTotal=conta('contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal'");
	$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal' AND status='12'" );
	$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal'  AND status='13'" );
	$ordemTransferida = conta( 'contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal'AND status='14'" );
	$ordemCancelada = conta( 'contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal' AND status='15'" );

	$ordemFoto = conta( 'contrato_ordem', "WHERE id AND data>='$dataInicio' AND data<='$dataFinal' AND foto<>''" );
	
	$ordemBaixada = $ordemRealizada+$ordemCancelada;

	if(!empty($rotaId)){
		
		$ordemTotal=conta('contrato_ordem', "WHERE rota='$rotaId' AND data>='$dataInicio' AND data<='$dataFinal'");
		$ordemEmaberto = conta( 'contrato_ordem', "WHERE rota='$rotaId' AND data>='$dataInicio' AND data<='$dataFinal' AND status='12'" );
		$ordemRealizada = conta( 'contrato_ordem', "WHERE rota='$rotaId' AND data>='$dataInicio' AND data<='$dataFinal'  AND status='13'" );
		$ordemTransferida = conta( 'contrato_ordem', "WHERE rota='$rotaId' AND data>='$dataInicio' AND data<='$dataFinal'AND status='14'" );
		$ordemCancelada = conta( 'contrato_ordem', "WHERE rota='$rotaId' AND data>='$dataInicio' AND data<='$dataFinal' AND status='15'" );

		$ordemFoto = conta( 'contrato_ordem', "WHERE rota='$rotaId' AND data>='$dataInicio' AND data<='$dataFinal' AND foto<>''" );

	$ordemBaixada = $ordemRealizada+$ordemCancelada;

	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Emissão de Ordem de Serviço</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviçoo</a></li>
            <li><a href="painel.php?execute=suporte/ordem/emissao-ordem">Emissão</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
 

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
                     <?php
					  echo 'Coletadas '.$percentual.
							'%  || Realizadas : '. $ordemRealizada .
							'   || Transferidas  : '. $ordemTransferida. 
							'   || Em Aberto  : '. $ordemEmaberto.
					  		'   || Fotos  : '. $ordemFoto;
					  
					  ?>
                  </span>
           </div><!-- /.info-box bg-red -->
 </div> <!-- /.box-header -->
          
          
        <div class="box-header">
               
               <a href="painel.php?execute=suporte/ordem/ordem-servico" target="_blank">
                    <img src="ico/imprimir.png"  title="Imprimir Ordem de Serviço" />
                   	<small>Imprimir Ordem  </small>
               </a> 
               
               <a href="painel.php?execute=suporte/ordem/ordem-servico-protocolo" target="_blank">
                    <img src="ico/imprimir.png" title="Imprimir Protocolo Ordem de Serviço" />
                   	<small>Imprimir Protocolo  </small>
               </a> 
               
               <!--<a href="painel.php?execute=suporte/ordem/ordem-contrato-aviso" >
                    <!--<img src="ico/email.png" alt="Aviso" title="Confirmaçao de Serviços" />
                   	<small>  Confirmaçao de Coleta  </small>-->
               <!--</a>--> 
               
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
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataInicio)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
						 
                        <div class="form-group pull-left">
                            <input name="data2" type="date" value="<?php echo date('Y-m-d',strtotime($dataFinal)) ?>" class="form-control input-sm" >
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
                                           if($rotaId == $mae['id']){
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

					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Coleta</td>
					<td>Prev</td>
					<td>Col</td>
					<td>Prev</td>
					<td>Col</td>
					<td align="center">Rota</td>
					<td>M</td>
					<td align="center">Tipo</td>
					<td align="center">Status</td>
					
						
					<td>Foto</td>
					<td>Assi</td>
					<td>Qr</td>
	
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
		
				$clienteId = $mostra['id_cliente'];	
				$contratoId = $mostra['id_contrato'];	
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				echo '<td align="center">'.$mostra['id'].'</td>';
			
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,10).'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
				echo '<td>'.substr($coleta['nome'],0,10).'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				
				echo '<td align="center">'.$coletaPrevisto['quantidade'].'</td>';
				echo '<td align="center">'.$mostra['quantidade1'].'</td>';
						
				echo '<td>'.$mostra['hora'].'</td>';
				echo '<td>'.$mostra['hora_coleta'].'</td>';
								
				$rota = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rota'");
				echo '<td>'. substr($rota['nome'],0,3).'</td>';
		
				echo '<td align="center">'.$mostra['manifesto'].'</td>';
		
				$ordemId = $mostra['id'];	
				echo '<td>'.$contrato['contrato_tipo'].'</td>';
	
			    $statusId = $mostra['status'];
                $status = mostra('contrato_status',"WHERE id ='$statusId'");
		
				if($statusId==12){
					echo '<td>'.$status['nome'].'</td>';
				}else if($statusId==15){
				   echo '<td align="center"><span class="badge bg-red">'.$status['nome'].'</span></td>';
				}else if($statusId==14){
				   echo '<td align="center"><span class="badge bg-green">'.$status['nome'].'</span></td>';
					
			    }else if($statusId==13){
					
					if( $mostra['nao_coletada']<>1){
								echo '<td align="center"><span class="badge bg-light-blue">'.$status['nome'].'</span></td>';
							}else{
								echo '<td align="center"><span class="badge bg-green">Não Coletado</span></td>';
							}
					}
		
					if($mostra['foto']!= '' && file_exists('../uploads/fotos/'.$mostra['foto'])){
                        
						echo '<td align="center">
                              <img class="img-thumbnail imagem-tabela zoom" src="'.URL.'/uploads/fotos/'
                                         .$mostra['foto'].'">';
                      }else{
						
                        echo '<td align="center">
                             <i class="fa fa-picture-o"></i>
                         </td>';
                    }	
		
				if($mostra['assinatura']!= '' && file_exists('../uploads/assinaturas-ordem/'.$mostra['assinatura'])){
                        echo '<td align="center">
                              <img class="img-thumbnail imagem-tabela" src="'.URL.'/uploads/assinaturas-ordem/'.$mostra['assinatura'].'">';
                      }else{
                        echo '<td align="center">
                             <i class="fa fa-picture-o"></i>
                         </td>';
                    }	
		
					if($mostra['qrcode']=='1'){
                       	echo '<td align="center"><span class="badge bg-green">*</span></td>';

                      }else{
                        	echo '<td align="center">-</td>';

                    }	
				
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
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$cliente['id'].'">
								<img src="ico/editar-cliente.png" alt="Editar Cliente" title="Editar Cliente" />
							</a>
						  </td>';
		  
				// imprimir ordem
				echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-servico&ordemImprimir='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png" alt="Imprimir" title="Imprimir"  />
						</a>
					 </td>';	
	
			echo '</tr>';
		
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="19">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
         echo '</tfoot>';
		 
		 echo '</table>';
		
		 $link = 'painel.php?execute=suporte/ordem/emissao-ordem&pag=';
	 		if(empty($rotaId)){

				pag('contrato_ordem',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' 
		 				ORDER BY rota ASC, hora ASC", $maximo, $link, $pag);
			}
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