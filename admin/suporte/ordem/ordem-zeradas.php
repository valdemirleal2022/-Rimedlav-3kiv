<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if (!isset( $_SESSION[ 'dataInicio' ] ) ) {
		
		$data1 = date( "Y/m/d" );
		$data2 = date( "Y/m/d" );
		$_SESSION[ 'dataInicio' ] = $data1;
		$_SESSION[ 'dataFinal' ] = $data2;
		
	} else {
		
		$data1 = $_SESSION[ 'dataInicio' ];
		$data2 = $_SESSION[ 'dataFinal' ];
		
	}


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio'];
		$data2 = $_POST[ 'fim'];
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-ordem-zerada-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {

		$data1 = $_POST[ 'inicio'];
		$data2 = $_POST[ 'fim' ];
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-zerada-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		
		$rotaRoteiro = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];

	}

 

	$total = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND quantidade1='0'" );
	$leitura = read( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND quantidade1='0' ORDER BY data ASC" );

	if(!empty($rotaRoteiro)){
		$total = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND rota<='$rotaRoteiro' AND quantidade1='0'" );
		$leitura = read( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND rota<='$rotaRoteiro' AND quantidade1='0' ORDER BY data ASC" );
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Ordem de Serviço Zerada</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviço</a></li>
            <li><a href="#"> Zerada</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
     
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
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
					<td align="center">Tipo Contrato</td>
					<td align="center">Coleta</td>

					<td align="center">Rota</td>
					<td align="center">Observacao</td>
					<td align="center">Motivo</td>
					<td align="center">Data</td>
						
					<td>Foto</td>
					<td>Assi</td>
 
					
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
		
				$contratoTipoId = $contrato['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.substr($coleta['nome'],0,18).'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
	
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';

				echo '<td align="left">'.substr($mostra['observacao'],0,10).'</td>';
					
				$motivoId = $mostra['motivo_nao_coletado'];
                $motivo = mostra('ordem_motivo_naocoletado',"WHERE id ='$motivoId'");
				echo '<td>'.$motivo['nome'].'</td>';
				
				echo '<td>'.converteData($mostra['data']).'</td>';
		
				
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
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contrato['id'].'">
			  				<img src="ico/visualizar.png" alt="Editar" title="Visualizar Contrato" class="tip" />
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