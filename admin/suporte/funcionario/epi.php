<head>
    <meta charset="iso-8859-1">
</head>

<?php 

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}
	
	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$diaria = $_POST[ 'diaria' ];
		$status = $_POST[ 'status' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['diaria']=$diaria;
		$_SESSION['status']=$status;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-funcionario-epi-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$diaria = $_POST[ 'diaria' ];
		$status = $_POST[ 'status' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['diaria']=$diaria;
		$_SESSION['status']=$status;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-funcionario-epi-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	}

	$total = conta('funcionario_epi',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC ");
	$leitura = read('funcionario_epi',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];


?>

<head>
    <meta charset="iso-8859-1">
</head>

<div class="content-wrapper">
  <section class="content-header">
       <h1>EPIs</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Funcionario</a>
            <li class="active">EPIs</li>
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
                            <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
                        <div class="form-group pull-left">
                            <input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
			          
                         <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relat�rio PDF"></i></button>
                         </div>  <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Rela�rio Excel"></i></button>
                          </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
        </div> <!-- /.box-header -->
       
     
     <div class="box-body table-responsive">
     
 <?php
	
	 
	if($leitura){
		
				echo '<table class="table table-hover">
					<tr class="set">
					
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Tipo</td>
					<td align="center">Motivo</td>
					
					<td align="center">Data</td>
					<td align="center">Quantidade</td>
					
					<td align="center">Observa��o</td>
						
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
	 
				$funcionarioId = $mostra['id_funcionario'];
			 
				$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId'");
				echo '<td>'.substr($funcionario['nome'],0,15).'</td>';
		 
				$epiId = $mostra['epi'];
				$epi = mostra('funcionario_epi_tipo',"WHERE id ='$epiId'");
				echo '<td>'. $epi['nome'] .'</td>';
		
				
				if($mostra['id_motivo']=='1'){
					echo '<td>Novo</td>';
				} else 	if($mostra['id_motivo']=='2'){
					echo '<td>Troca</td>';
				} else{
					echo '<td>-</td>';
				}
		 		echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'.$mostra['quantidade'].'</td>';
		
				echo '<td>'.$mostra['observacao'].'</td>';
			 
				echo '<td align="center">
				<a href="painel.php?execute=suporte/funcionario/epi-editar&epiEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
		
				echo '<td align="center">
				<a href="painel.php?execute=suporte/funcionario/epi-editar&epiVisualizar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png" title="Visualizar" />
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
