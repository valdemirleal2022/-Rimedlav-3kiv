<head>
    <meta charset="iso-8859-1">
</head>

 <?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
	
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");
	$total = 0;
	$consultor = '';

	if(isset($_POST['relatorio-pdf'])){
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$consultor = $_POST['consultor'];
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$_SESSION['consultor']=$_POST['consultor'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-visita-negociacao-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$consultor = $_POST['consultor'];
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$_SESSION['consultor']=$_POST['consultor'];
		
	    header('Location: ../admin/suporte/relatorio/relatorio-visita-negociacao-excel.php');
	}

 

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$consultor = $_POST['consultor'];
 
		//$data1 = date( "Y-m-d", strtotime( "$data1 -1 days" ) );
		$dataFinal = date( "Y-m-d", strtotime( "$data2 +1 days" ) );
 
		$total = conta('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal'");
		$leitura = read('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal' 
		ORDER BY interacao DESC");
		 	
		if (!empty($consultor) ) {
			
			$total = conta('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal'");
			$leitura = read('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal' AND consultor='$consultor' ORDER BY interacao DESC");
			
		 
		}
	}
 
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
 
?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Negocia&ccedil;&otilde;es</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Usu&aacute;rio</li>
           <li>Negocia&ccedil;&otilde;es</li>
         </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

     	<div class="box-header">	 
  
                    <div class="col-xs-10 col-md-7 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
                       
                        <div class="form-group pull-left">
							 <select name="consultor" class="form-control input-sm">
									<option value="">Consultor</option>
									<?php 
										$readConta = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
										if(!$readConta){
											echo '<option value="">Não temos Registro no momento</option>';	
										}else{
											foreach($readConta as $mae):
											   if($consultor == $mae['id']){
													echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
												 }else{
													echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
												}
											endforeach;	
										}
									?> 
								</select>    
						</div><!-- /.form-group pull-left -->

                        
                       	 <div class="form-group pull-left">
								<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
								 <i class="fa fa-search"></i></button>
							 </div><!-- /.input-group -->

							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->                         
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
					<td>Id</td>
					<td>Nome</td>
					<td align="center">Consultor</td>
					<td align="center">Descrição</td>
					<td align="center">Interação</td>
					<td align="center">Retorno</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				
			echo '<td align="left">'.$mostra['id'].'</td>';
				$visitaId = $mostra['id_visita'];
				$visita = mostra('cadastro_visita',"WHERE id ='$visitaId'");
				echo '<td align="left">'.substr($visita['nome'],0,30).'</td>';
		
				$consultorId = $mostra['consultor'];
				$mostraConsultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				
				echo '<td align="left">'.$mostraConsultor['nome'].'</td>';
				echo '<td align="left">'.substr($mostra['descricao'],0,40).'</td>';
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['retorno'])).'</td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaEditar='.$mostra['id'].'">
			  				<img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaBaixar='.$mostra['id'].'">
			  				<img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaDeletar='.$mostra['id'].'">
			  				<img src="../admin/ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
              			</a>
				      </td>';	
					  
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-editar&visitaEditar='.$visitaId.'">
			  				<img src="../admin/ico/visualizar.png" alt="Editar" title="Editar Orçamento" class="tip" />
              			</a>
				      </td>';		
			echo '</tr>';
		endforeach;
		 
		echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
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