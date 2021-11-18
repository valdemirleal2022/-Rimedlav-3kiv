<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>

<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autConsultor']['id'])){
			header('Location: painel.php');	
		}	
	}

	$consultorId=$_SESSION['autConsultor']['id'];
	
	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];

		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/orcamento/relatorio-orcamentos-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
		header( 'Location: suporte/orcamento/relatorio-orcamentos-excel.php' );
	}

  

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];

	}

	$leitura = read( 'cadastro_visita', "WHERE id AND status<>'0' AND consultor='$consultorId' ORDER BY orc_data DESC" );

	 $leitura = read( 'cadastro_visita', "WHERE id AND status<>'0' AND consultor='$consultorId' AND orc_data>='$data1' AND orc_data<='$data2'" );

    $total = conta( 'cadastro_visita', "WHERE id AND status<>'0' AND consultor='$consultorId' AND orc_data>='$data1' AND orc_data<='$data2'" );


$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

		// 0 - visita
		// 1 - solicitacoes
		// 2 - orcamento

?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Orçamento</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Orçamento</a></li>
         </ol>
 </section>
 
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
		 <div class="box-header">
             <div class="row">

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
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
     	</div><!-- /col-xs-10 col-md-7 pull-right-->   
	     </div><!-- /row-->	 
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
					<td align="center">Orçamento</td>
					<td align="center">Indicação</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Vendedor</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			

				echo '<td>'.substr($mostra['nome'],0,22).'</td>';
				echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['data']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_data']).'</td>';
	 
				$indicacaoId = $mostra['indicacao'];
				$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
				echo '<td>'.substr($indicacao['nome'],0,10).'</td>';
				
				echo '<td>'.substr($mostra['orc_residuo'],0,15).'</td>';
				
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.$consultor['nome'].'</td>';
				
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>';
				
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
					<img src="../admin/ico/editar.png" alt="Editar" title="Editar" />
					</a>
					</td>';
		
//				echo '<td align="center">
//						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoDesfazer='.$mostra['id'].'">
//							<img src="../admin/ico/inicio.png"  title="Desfazer Cancelamento"  />
//              			</a>
//						</td>';
//		
//				echo '<td align="center">
//						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoBaixar='.$mostra['id'].'">
//							<img src="../admin/ico/baixar.png" alt="Cancelar" title="Cancelar"  />
//              			</a>
//						</td>';

				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
					echo '</tr>';
						   
					echo '<tr>';
					echo '<td colspan="11">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
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