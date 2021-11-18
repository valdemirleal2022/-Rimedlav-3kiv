<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	
	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$consultorId = $_POST['consultor'];
		$indicacaoId = $_POST['indicacao'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		$_SESSION['indicacao']=$indicacaoId;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-orcamento-cancelados-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$consultorId = $_POST['consultor'];
		$indicacaoId = $_POST['indicacao'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		$_SESSION['indicacao']=$indicacaoId;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-orcamento-cancelados-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	}

	 $leitura = read( 'cadastro_visita', "WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2' ORDER BY orc_solicitacao ASC" );

     $total = conta( 'cadastro_visita', "WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2'" );
	 $valor_total = soma('cadastro_visita',"WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2'",'orc_valor');

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

	
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Orçamento Cancelados</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Orçamento</li>
           <li>Cancelados</li>
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
                        <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>  
                        </div><!-- /.input-group -->
                          <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>  
                        </div><!-- /.input-group -->                              
                    </form> 
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
          </div><!-- /box-header-->   
       

    <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
                  
	<?php 
			

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Solicitação</td>
					<td align="center">Orçamento/Hora</td>
					<td align="center">Indicação</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Vendedor</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			

				echo '<td>'.substr($mostra['nome'],0,15).'</td>';
				echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_solicitacao']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_data']).'/'.$mostra['orc_hora'].'</td>';
	 
				$indicacaoId = $mostra['indicacao'];
				$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
				echo '<td>'.$indicacao['nome'].'</td>';
				
				echo '<td>'.substr($mostra['orc_residuo'],0,15).'</td>';
				
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.$consultor['nome'].'</td>';
				
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>';
				
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
					<img src="ico/editar.png" alt="Editar" title="Editar" />
					</a>
					</td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoDesfazer='.$mostra['id'].'">
							<img src="../admin/ico/inicio.png"  title="Desfazer Cancelamento"  />
              			</a>
						</td>';
				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
					echo '</tr>';
						   
					echo '<tr>';
					echo '<td colspan="10">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
					echo '</tr>';
			 echo '</tfoot>';

		 echo '</table>';
	 	
	  }   
									  
	?>
				
	</div><!-- /.box-body table-responsive -->
    
        <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?> 
       </div><!-- /.box-footer-->
       
    </div><!-- /.box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->