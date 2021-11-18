<?php

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}else{
		header('Location: painel.php?execute=index');
	}

 
/* Habilita a exibição de erros */
ini_set("display_errors", 1);


	
	$data1 = converteData1();
	$data2 = converteData2();

	if (isset($_POST['grafico'])) {
			$_SESSION['grafico']='1';
		}else{
			$_SESSION['grafico']='0';
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/prestacao-conta-php");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		header( 'Location: ../admin/suporte/relatorio/prestacao-conta-excel.php' );
	}


$data1 = date("Y-m-d", strtotime("-10 day"));
$data2 = date("Y-m-d", strtotime("+30 day"));

$leitura = read( 'receber', "WHERE emissao='$hoje'ORDER BY emissao" );
$valor_total = soma( 'receber', "WHERE emissao='$hoje'", 'valor' );
$total = conta( 'receber', "WHERE emissao>='$hoje'" );

if ( isset( $_POST[ 'pesquisar' ] ) ) {
    $data1 = $_POST[ 'inicio' ];
    $data2 = $_POST[ 'fim' ];
    $_SESSION[ 'inicio' ] = $_POST[ 'inicio' ];
    $_SESSION[ 'fim' ] = $_POST[ 'fim' ];
    $valor_total = soma( 'receber', "WHERE emissao>='$data1' AND emissao<='$data2'", 'valor' );
    $total = conta( 'receber', "WHERE emissao>='$data1' AND emissao<='$data2'" );
    $leitura = read( 'receber', "WHERE emissao>='$data1' AND emissao<='$data2' ORDER BY emissao" );
}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

$mes = date( 'm/Y' );
$mesano = explode( '/', $mes );

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Tabela</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Tabela</li>
           <li><a href="#">Tabela</a></li>
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                    </form> 
                  </div><!-- /col-xs-4-->

            </div><!-- /box-header-->   
    
   
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
   
	<?php 
	
	
		 
	
		if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Descrição</td>
					<td align="center">Tags</td>
					<td align="center">Url</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
				echo '<tr>';
					echo '<td>'.$mostra['id'].'</td>';
					echo '<td>'.$mostra['nome'].'</td>';
					echo '<td>'.resumos(ucfirst($mostra['descricao']),$palavras = '80').'</td>';
					echo '<td>'.resumos($mostra['tags'],$palavras = '20').'</td>';
					echo '<td>'.resumos($mostra['url'],$palavras = '20').'</td>';
					echo '<td align="center">
						<a href="painel.php?execute=site/categoria-editar&categoriaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				    </td>';
					echo '<td align="center">
			  			<a href="painel.php?execute=site/categoria-editar&categoriaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
					</td>';
			 endforeach;
			 
			 echo '<tfoot>';
				echo '<tr>';
                	echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
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

	    </div><!--/box-body table-responsive-->   
	  </div><!-- /.col-md-12 scrool -->
 	  </div><!-- /.box-body table-responsive -->
 	  	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->