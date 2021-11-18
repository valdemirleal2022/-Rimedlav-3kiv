<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-rota-semanal-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-rota-semanal-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		$rotaId = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
	}


	$leitura =read('contrato',"WHERE id AND status='5' AND (
	domingo_rota1 = '$rotaId' AND domingo='1' OR 
	segunda_rota1 = '$rotaId' AND segunda='1' OR 
	terca_rota1 = '$rotaId' AND terca ='1' OR 
	quarta_rota1 = '$rotaId' AND quarta ='1' OR 
	quinta_rota1 = '$rotaId' AND quinta ='1' OR 
	quinta_rota1 = '$rotaId' AND quinta ='1' OR 
	sabado_rota1 = '$rotaId' AND sabado ='1') ORDER BY segunda_hora1 ASC "); 


$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];


?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Rota Semanal</h1>
        <ol class="breadcrumb">
            <li><a href="../contrato/painel.php?execute=painel"><i class="fa fa-home"></i>Contrato</a>
            </li>
            <li>Rota Semanal</a>
            </li>
        </ol>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">

                    <div class="box-header">
           
                         <div class="col-xs-6 col-md-4 pull-right">
        
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
                                
                             
                                <div class="form-group pull-left">
								  <select name="rota"  class="form-control input-sm" >
									<option value="">Selecione tipo de coleta</option>
									<?php 
									$rotaRead = read('contrato_rota',"WHERE id ORDER BY nome ASC");
										if(!$rotaRead){
										echo '<option value="">Nao temos tipo de coleta no momento</option>';	
									}else{
										foreach($rotaRead as $mae):
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
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->

                              </form>
                         </div>  <!-- /col-xs-6 col-md-5 pull-right-->
                    </div>   <!-- /box-header-->

          <div class="box-body table-responsive">

	<?php 
			  
	$total=0;
		
	if($leitura){
		
		echo '<table class="table table-hover">	
					<tr class="set">
					<td>Controle</td>
					<td>Nome</td>
					<td>Bairro</td>
					<td align="center">Seg</td>
					<td align="center">Ter</td>
					<td align="center">Qua</td>
					<td align="center">Qui</td>
					<td align="center">Sex</td>
					<td align="center">Sab</td>
					<td align="center">Dom</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
	
				$total++;
		
				$clienteId = $mostra['id_cliente'];	
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				
				echo '<td>'.substr($mostra['controle'],0,6).'</td>';
		
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td>'.substr($cliente['bairro'],0,12).'</td>';
		
				if($mostra['segunda_rota1']==$rotaId){
					echo '<td align="center">'.$mostra['segunda_hora1'].'</td>';
				}else{
					echo '<td align="center">---</td>';
				}	
		
				if($mostra['terca_rota1']==$rotaId){
					echo '<td align="center">'.$mostra['terca_hora1'].'</td>';
				}else{
					echo '<td align="center">---</td>';
				}	
		
				if($mostra['quarta_rota1']==$rotaId){
					echo '<td align="center">'.$mostra['quarta_hora1'].'</td>';
				}else{
					echo '<td align="center">---</td>';
				}	
		
				if($mostra['quinta_rota1']==$rotaId){
					echo '<td align="center">'.$mostra['quinta_hora1'].'</td>';
				}else{
					echo '<td align="center">---</td>';
				}	
		
				if($mostra['sexta_rota1']==$rotaId){
					echo '<td align="center">'.$mostra['sexta_hora1'].'</td>';
				}else{
					echo '<td align="center">---</td>';
				}	
		
				if($mostra['sabado_rota1']==$rotaId){
					echo '<td align="center">'.$mostra['sabado_hora1'].'</td>';
				}else{
					echo '<td align="center">---</td>';
				}	
		
				if($mostra['domingo_rota1']==$rotaId){
					echo '<td align="center">'.$mostra['domingo_hora1'].'</td>';
				}else{
					echo '<td align="center">---</td>';
				}

				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar Contrato" class="tip" />
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

