
 <?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
	
	$usuario=$_SESSION['autUser']['nome'];

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-interacoes-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-interacoes-excel.php');
	}

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '20';
	$inicio = ($pag * $maximo) - $maximo;
	
	$total = conta('interacao',"WHERE id");

	$leitura = read('interacao',"WHERE id AND usuario='$usuario' ORDER BY data DESC LIMIT $inicio,$maximo");

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$usuario = $_POST['usuario'];
		
		$total = conta('interacao',"WHERE id AND data>='$data1' AND data<='$data2' 
												AND usuario='$usuario'");

		$leitura = read('interacao',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2' 
		AND usuario='$usuario' ORDER BY data DESC");

	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

	$data1= date('Y/m/d H:i:s');

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Interações do Usuário</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Usuário</li>
           <li>Interações</li>
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
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d') ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                       <div class="form-group pull-left">
                            <input type="date" name="fim" value="<?php echo date('Y-m-d') ?>" class="form-control input-sm" >
                       </div><!-- /.input-group -->
                       
                        <div class="form-group pull-left">
							 <select name="usuario" class="form-control input-sm" disabled>
									<option value="">Usuário</option>
									<?php 
										$readConta = read('usuarios',"WHERE id ORDER BY nome ASC");
										if(!$readConta){
											echo '<option value="">Não temos Bancos no momento</option>';	
										}else{
											foreach($readConta as $mae):
											   if($usuario == $mae['nome']){
													echo '<option value="'.$mae['nome'].'"selected="selected">'.$mae['nome'].'</option>';
												 }else{
													echo '<option value="'.$mae['nome'].'">'.$mae['nome'].'</option>';
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
       

    <div class="box-body table-responsive">
   
	<?php 
		


	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Id</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td align="center">Tipo de Interação</td>
					<td align="center">Usuário</td>
					<td align="center">Data/Horário</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';

				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['id_contrato'].'</td>';
				
				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				echo '<td>'.substr($contrato['controle'],0,6).'</td>';
			
				$clienteId = $contrato['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,40).'</td>';

				echo '<td>'.$mostra['interacao'].'</td>';
				echo '<td>'.$mostra['usuario'].'</td>';
				echo '<td align="center">'. date('d/m/Y H:i:s',strtotime($mostra['data'])).'</td>';

			echo '</tr>';
		 endforeach;
		 
			 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="12">' . 'Total de registros : ' .  $total . '</td>';
			 echo '</tfoot>';
			
		 echo '</table>';
		
		 $link = 'painel.php?execute=suporte/interacao/interacoes&pag=';
		
			if (!isset( $_POST[ 'pesquisar' ] ) ) {
				pag('interacao',"WHERE id AND usuario='$usuario'ORDER BY data DESC", $maximo, $link, $pag);
			}else{
				pag('interacao',"WHERE id AND usuario='$usuario' AND DATE(data)>='$data1' AND DATE(data)<='$data2' 
		AND usuario='$usuario ' ORDER BY data DESC", $maximo, $link, $pag);
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
