<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data1' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		
		$rotaId = $_POST['rota'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-manifesto-gerados-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data1' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		
		$rotaId = $_POST['rota'];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-manifesto-gerados-excel.php' );
	}


	if(isset($_POST['pesquisar_numero'])){
		$ordemId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($ordemId)){
			echo '<div class="alert alert-warning">Número Inválido!</div><br />';
		}else{
			header('Location: painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$ordemId);
		}
		$rotaId = $_POST['rota'];
		$dataroteiro=$_POST['data1'];
	}

	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data1'];
		$rotaId = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$_SESSION[ 'dataroteiro' ] = $dataroteiro;
	}


	if (!isset( $_SESSION[ 'dataroteiro' ] ) ) {
		$dataroteiro = date( "Y/m/d" );
		$_SESSION[ 'dataroteiro' ] = $dataroteiro;
	} else {
		$dataroteiro = $_SESSION[ 'dataroteiro' ];
	}

	if (isset($_SESSION[ 'rotaColeta' ] ) ) {
		$rotaId = $_SESSION[ 'rotaColeta' ];
	}


	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '30';
	$inicio = ($pag * $maximo) - $maximo;

	$total = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND manifesto='M'" );
	$leitura = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND manifesto='M'
						ORDER BY rota ASC, hora ASC LIMIT $inicio,$maximo" );

	if(!empty($rotaId)){
		$total = conta('contrato_ordem',"WHERE id AND data='$dataroteiro' AND manifesto='M' AND rota='$rotaId'");
		$leitura = read('contrato_ordem',"WHERE id  AND data='$dataroteiro'  AND manifesto='M' AND rota='$rotaId' ORDER BY rota ASC, hora ASC");
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

	
?>


<div class="content-wrapper">
  <section class="content-header">
       <h1>Manifestos</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</a></li>
            <li><a href="painel.php?execute=suporte/manifesto/manifesto">Manifestos</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
           <div class="box-header">
               <a href="painel.php?execute=suporte/manifesto/manifesto" class="btnnovo" target="_blank">
                    <img src="ico/imprimir.png" alt="Aviso" title="Imprimir Manifesto" class="imagem" />
                     Imprimir Manifesto
               </a> 
             </div>
 
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
                   
                  <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
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
    
	<?php 

	
	if($leitura){
					
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Resíduo</td>
					<td align="center">Coleta</td>
					<td>Data</td>
					<td>Hora</td>
					<td align="center">Rota</td>
					<td>Status</td>
					<td align="center">Interaçao</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
		
				echo '<td align="center">'.$mostra['id'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,10).'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		
                $residuoId = $coleta['residuo'];
                $residuo = mostra('contrato_tipo_residuo',"WHERE id ='$residuoId'");
		
                echo '<td align="left">'.$residuo['nome'].'</td>';
		 		echo '<td align="left">'.$coleta['nome'].'</td>';
				
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'.$mostra['hora'].'</td>';
		
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';
				
				echo '<td align="center">'.$mostra['manifesto_status'].'</td>';
			
			    echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				
 				 echo '<td align="center">
						<a href="painel.php?execute=suporte/manifesto/manifesto-editar&manifestoBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Manifesto Baixar" class="tip" />
              			</a>
				      </td>';
		
		 		echo '<td align="center">
						<a href="painel.php?execute=suporte/manifesto/manifesto-editar&manifestoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="EditarManifesto" class="tip" />
              			</a>
				      </td>';
		
				 echo '<td align="center">
						<a href="painel.php?execute=suporte/manifesto/manifesto&manifestoImprimir='.$mostra['id'].'" target="_blank">
			  				<img src="ico/imprimir.png" alt="Baixar" title="Manifesto Imprimir" />
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
		 
		 $link = 'painel.php?execute=suporte/manifesto/manifesto-agendados&pag=';
		 
	     if(empty($rotaId)){
		  pag('contrato_ordem',"WHERE id AND manifesto='M' AND data='$dataroteiro' ORDER BY rota ASC, hora ASC", $maximo, $link, $pag);
		 }else{
		  pag('contrato_ordem',"WHERE id AND manifesto='M' AND data='$dataroteiro' AND rota='$rotaId' ORDER BY rota ASC, hora ASC", $maximo, $link, $pag);
		}
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