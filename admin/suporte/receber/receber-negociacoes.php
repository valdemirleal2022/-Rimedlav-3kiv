<?php 

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}
	
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio'];
		$data2 = $_POST[ 'fim'];
		$motivo = $_POST[ 'motivo' ];
		$usuarioPesquisa = $_POST[ 'usuarioPesquisa' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['motivo']=$motivo;
		$_SESSION['usuarioPesquisa']=$usuarioPesquisa;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receber-negociacao-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$motivo = $_POST[ 'motivo' ];
		$usuario = $_POST[ 'usuarioPesquisa' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['motivo']=$motivo;
		$_SESSION['usuarioPesquisa']=$usuarioPesquisa;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-receber-negociacao-excel.php' );
	}


	$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2'");
	$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC, id_cliente ASC, id_usuario ASC, peso DESC");
	
	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$motivo = $_POST[ 'motivo' ];
		$usuarioPesquisa = $_POST[ 'usuarioPesquisa' ];

		$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2'");
		$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC, id_usuario ASC, id_usuario ASC, peso DESC");
		
	}


	if(!empty($usuarioPesquisa)){
		$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_usuario='$usuarioPesquisa'");
		$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_usuario='$usuarioPesquisa' ORDER BY data ASC");
	}
		

	if(!empty($motivo)){
		$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motivo='$motivo'");
		$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motivo='$motivo' ORDER BY data ASC");
	}
	 

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?><head>
    <meta charset="iso-8859-1">
</head>




<div class="content-wrapper">
  <section class="content-header">
       <h1>Negocia&ccedil;&otilde;es</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Receber</a>
            <li class="active">Negocia&ccedil;&otilde;es</li>
          </ol>
 </section>
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		 	<div class="box-header">       
                  <div class="col-xs-10 col-md-9 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
						 
						  <div class="form-group pull-left">
								<select name="motivo" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('recebe_negociacao_motivo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($motivo == $mae['id']){
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
								<select name="usuarioPesquisa" class="form-control input-sm">
									<option value="">Selecione o Usuário</option>
									<?php 
										$readContrato = read('usuarios',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($usuarioPesquisa == $mae['id']){
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
                  
        </div> <!-- /.box-header -->
       
     
     <div class="box-body table-responsive">
     
 <?php
	
	$datamovimento='';
	$totalmovimento=0;
	$totalregistros=0;
	$totalclientes=0;
 	 
	if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Contrato</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Vencimento</td>
							
					<td align="center">Status</td>
					
					<td align="center">Data</td>
					<td align="center">Hora</td>
	
					<td align="center">Prev Pag</td>
					
					<td align="center">Motivo</td>
					<td align="center">Solução</td>
					<td align="center">Peso</td>
					<td align="center">Usuario</td>
			
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				if($clienteId<>$mostra['id_cliente']){
					$totalclientes+=1;
			    }
		
		    	$clienteId = $mostra['id_cliente'];
				$receberId = $mostra['id_receber'];
				$receber = mostra('receber',"WHERE id ='$receberId'");
		
				$contratoId = $receber['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId '");

			    if(empty($datamovimento)){
					$datamovimento=$mostra['id_usuario'];
			    }
				
			    if($datamovimento<>$mostra['id_usuario']){
							echo '<tr>';
				   
							echo '<td></td>';
							echo '<td></td>';
						 
							echo '<td align="center">Total</td>';
							echo '<td align="right">'.converteValor($totalmovimento).'</td>';
				   		
				   			echo '<td align="center">Registros</td>';
							echo '<td align="right">'.$totalregistros.'</td>';
				   			echo '<td align="center">Clientes</td>';
							echo '<td align="right">'.$totalclientes.'</td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
							echo '</tr>';
					        $datamovimento=$mostra['id_usuario'];
					        $totalregistros=0;
							$totalclientes=0;
				   			$totalmovimento=$receber['valor'];
					}else{
				   
					 $totalregistros +=1;
				     $totalmovimento +=$receber['valor'];
				}
		
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$contrato['id'].'</td>';
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		
				echo '<td>'.substr($cliente['nome'],0,15).'</td>';
					
				echo '<td align="right">'.converteValor($receber['valor']).'</td>';
				echo '<td>'.converteData($receber['vencimento']).'</td>';
				echo '<td>'.$receber['status'].'</td>';

				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'.$mostra['hora'].'</td>';
				echo '<td>'.converteData($mostra['previsao_pagamento']).'</td>';
		
				$motivoId = $mostra['id_motivo'];
				$motivo = mostra('recebe_negociacao_motivo',"WHERE id ='$motivoId '");
				echo '<td>'.$motivo['nome'].'</td>';
		
				$solucaoId = $mostra['id_solucao'];
				$solucao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId '");
				echo '<td>'.substr($solucao['nome'],0,15).'</td>';
				
				$negociacaoId= $mostra['id'];
			  	$cad['peso'] = $solucao['peso'];
			 	update('receber_negociacao',$cad,"id = '$negociacaoId'");	
		
				echo '<td>'.$mostra['peso'].'</td>';
		
				$usuarioId = $mostra['id_usuario'];
				$usuarioMostra = mostra('usuarios',"WHERE id ='$usuarioId '");
				echo '<td>'.substr($usuarioMostra['nome'],0,15).'</td>';
 
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$receber['id_contrato'].'">
			  				<img src="ico/visualizar.png"  title="Visualizar Contrato" />
              			</a>
				      </td>';
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id_receber'].'">
			  				<img src="ico/visualizar.png"   title="Visualizar Boleto" />
              			</a>
				      </td>';
					
			echo '</tr>';
		 endforeach;
		echo '<tfoot>';
                        echo '<tr>';
                            echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
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
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
