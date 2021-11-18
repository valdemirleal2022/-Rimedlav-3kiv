<?php

if ( function_exists( ProtUser ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}


$consultorId=$_SESSION['autConsultor']['id'];

if ( !isset( $_SESSION[ 'enderecoPesquisa' ] ) ) {
    $pag = ( empty( $_GET[ 'pag' ] ) ? '1' : $_GET[ 'pag' ] );
    $maximo = '30';
    $inicio = ( $pag * $maximo ) - $maximo;
    $leitura = read( 'cadastro_visita', "WHERE id AND status='0' AND consultor='$consultorId' ORDER BY orc_solicitacao DESC LIMIT $inicio,$maximo" );
    $total = conta( 'cadastro_visita', "WHERE id AND status='0' AND consultor='$consultorId'" );
    $pesquisaAtiva = 0;
	
} else {
    $leitura = read( 'cadastro_visita', "WHERE  id AND status='0' AND consultor='$consultorId' AND  (endereco LIKE '%$enderecoPesquisa%') ORDER BY endereco ASC,numero ASC" );
    $total = conta( 'cadastro_visita', "WHERE  id AND status='0' AND consultor='$consultorId' AND (endereco LIKE '%$enderecoPesquisa%')" );
    $pesquisaAtiva = 1;
}

if ( isset( $_POST[ 'pesquisaNome' ] ) ) {
    $pesquisa = strip_tags( trim( mysql_real_escape_string( $_POST[ 'nome' ] ) ) );
    if ( !empty( $pesquisa ) ) {
        $leitura = read( 'cadastro_visita', "WHERE  id AND status='0' AND consultor='$consultorId' AND (nome LIKE '%$pesquisa%')  ORDER BY nome ASC" );
        $total = conta( 'cadastro_visita', "WHERE  id AND status='0' AND consultor='$consultorId' AND (nome LIKE '%$pesquisa%')" );
        $pesquisaAtiva = 1;
    }

}
if ( isset( $_POST[ 'pesquisaEndereco' ] ) ) {
    $enderecoPesquisa = strip_tags( trim( mysql_real_escape_string( $_POST[ 'endereco' ] ) ) );
    if ( !empty( $enderecoPesquisa ) ) {
        $leitura = read( 'cadastro_visita', "WHERE id AND status='0' AND consultor='$consultorId' AND (endereco LIKE '%$enderecoPesquisa%') ORDER BY endereco ASC, numero ASC" );
        $total = conta( 'cadastro_visita', "WHERE id AND status='0'  AND consultor='$consultorId' AND (endereco LIKE '%$enderecoPesquisa%')" );
        $pesquisaAtiva = 1;
    }
    $_SESSION[ 'enderecoPesquisa' ] = $enderecoPesquisa;
}

if(isset($_POST['nome'])){
		$pesquisa=strip_tags(trim(mysql_real_escape_string($_POST['pesquisa'])));
		if(!empty($pesquisa)){
			$leitura =read('cadastro_visita',"WHERE id AND (
													nome LIKE '%$pesquisa%' OR
													nome_fantasia LIKE '%$pesquisa%' OR
													endereco LIKE '%$pesquisa%' OR 
													email LIKE '%$pesquisa%' OR 
													telefone LIKE '%$pesquisa%' OR
													celular LIKE '%$pesquisa%' OR
													contato LIKE '%$pesquisa%' OR
													cnpj LIKE '%$pesquisa%' OR
													cpf LIKE '%$pesquisa%'
													) 
													ORDER BY endereco ASC LIMIT $inicio,$maximo");  
		}

}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?><head>
    <meta charset="iso-8859-1">
</head>



<div class="content-wrapper">
    <section class="content-header">
        <h1>Cadastro de Visitas</h1>
        <ol class="breadcrumb">
            <li>Home</a>
             <li>Visita</a>
             <li class="active">Cadastro</li>
        </ol>
    </section>

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
                    
                 <div class="box-header">
                   <a href="painel.php?execute=suporte/visita/visita-editar" class="btnnovo">
                    <img src="../admin/ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
                    </a>
                    <small>Cadastrar Visita</small>
                 </div>
                 <!-- /.box-header -->
      
                 <div class="box-header">
                     
                    <div class="col-xs-10 col-md-5 pull-right">
                        <form name="form-pesquisa" method="post" class="form-inline " role="form">
                            <div class="input-group">
                                <input type="text" name="nome" class="form-control input-sm" placeholder="Nome">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisaNome" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                <!-- /.input-group -->
                            </div>
                            <!-- /input-group-->
                        </form>
                    </div>
                    <!-- /col-xs-10 col-md-3 pull-left-->

                    <div class="col-xs-10 col-md-5 pull-right">
                        <form name="form-pesquisa" method="post" class="form-inline " role="form">
                            <div class="input-group">
                                <input type="text" name="endereco" class="form-control input-sm" placeholder="Endereço">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisaEndereco" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                <!-- /.input-group -->
                            </div>
                            <!-- /input-group-->
                        </form>
                    </div>
                    <!-- /col-xs-10 col-md-3 pull-left-->
           </div>
         <!-- /box-header-->

       <div class="box-body table-responsive">

        <?php 
		   
		if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
                    <td align="center">Nome</td>
                    <td align="center">Endereço</td>
                    <td align="center">Numero</td>
                    <td align="center">Bairro</td>
					<td align="center">Cep</td>
                    <td align="center">Telefone</td>
                    <td align="center">Interação</td>
					<td align="center" colspan="6">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
                echo '<td>'.substr($mostra['nome'],0,15).'</td>';
                echo '<td>'.substr($mostra['endereco'],0,15).'</td>';
                echo '<td>'.$mostra['numero'].'</td>';
                echo '<td>'.$mostra['bairro'].'</td>';
                echo '<td>'.$mostra['cep'].'</td>';
                echo '<td>'.$mostra['telefone'].'</td>';
				echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';

				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/visita/visita-editar&visitaEditar='.$mostra['id'].'">
							<img src="../admin/ico/editar.png" alt="Editar" title="Editar" />
              			</a>
					</td>';
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/visita/visita-editar&visitaProposta='.$mostra['id'].'">
							<img src="../admin/ico/contratos.png" alt="Gerar Orçamento" title="Gerar Orçamento" />
              			</a>
					</td>';
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/visita/visita-editar&visitaCancelar='.$mostra['id'].'">
							<img src="../admin/ico/baixar.png" alt="Cancelar" title="Cancelar" />
              			</a>
					</td>';
			
			echo '</tr>';
		 endforeach;
            
         echo '<tfoot>';
     			echo '<tr>';
                	echo '<td colspan="16">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
         echo '</tfoot>';

		 echo '</table>';
            if( $pesquisaAtiva==0){
               $link = 'painel.php?execute=suporte/visita/visitas&pag=';
				pag('cadastro_visita',"WHERE id AND status='0' AND consultor='$consultorId' ORDER BY interacao ASC", $maximo, $link, $pag);
            } 

		}
	?>

                    <div class="box-footer">
                        <?php echo $_SESSION['cadastro'];
                            unset($_SESSION['cadastro']);
                            ?>
                    </div>
                    <!-- /.box-footer-->

                </div>
                <!-- /.box-body table-responsive -->

            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->