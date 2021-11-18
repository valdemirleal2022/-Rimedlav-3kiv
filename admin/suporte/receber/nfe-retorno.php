<div class="content-wrapper">
  <section class="content-header">
       <h1>NFe ler Arquivo de Retorno</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">NFe</a></li>
         <li class="active">NFe ler Arquivo de Retorno</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
   
                <div class="box-header">
                    <h3 class="box-title">Arquivo de Retorno</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-header">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="form-group col-xs-12 col-md-8">
                            <input type="file" name="fileUpload" class="form-control">
                        </div>
                        <div class="form-group col-xs-12 col-md-8">
                            <input type="submit" name="atualizar" value="Ler Arquivo de Retorno da NFe" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
                <!-- /.box-header -->

 
            <div class="box-body table-responsive">
                
 
                <?php

                $diretorio = 'retorno';
                if ( is_dir( "$diretorio" ) ) {
                    //	echo 'O diretorio já existe !';
                } else {
                    mkdir( "$diretorio", 0777 ); // criar o diretorio com permissao
                }

                if ( isset( $_FILES[ 'fileUpload' ] ) ) {
                    $new_name = $_FILES[ 'fileUpload' ][ 'name' ]; //Pegando extensao do arquivo
                    $dir = 'retorno/'; //Diretório para uploads
                    move_uploaded_file( $_FILES[ 'fileUpload' ][ 'tmp_name' ], $dir . $new_name ); //Fazer upload do arquivo
                    $ponteiro = fopen( $dir . $new_name, "r" );
                }

                if ( empty( $ponteiro ) ) {
                    return;
                }

                $total = 0;
                $valor_total = 0;

                while ( !feof( $ponteiro ) ) {

                    $linha = fgets( $ponteiro, 4096 );
                    $nota = substr( $linha, 9, 8 );
                    $codigo = substr( $linha, 18, 9 );
                    $receberId = substr( $linha, 56, 6 );
                    $inscricao = substr( $linha, 93, 7 );
                    $link = 'https://notacarioca.rio.gov.br/nfse.aspx?inscricao=' . $inscricao . '&nf=' . $nota . '&cod=' . $codigo;

                   if ( substr( $linha, 0, 1 ) == '1' ) {
                    echo '<table class="table table-hover">
                        <tr class="set">
                            <td align="center">Id</td>
                            <td align="center">Nome</td>
                            <td align="center">Valor</td>
                            <td align="center">Nota</td>
                            <td align="center">Link</td>
                        </tr>';

                   }

                    if ( substr( $linha, 0, 1 ) == '2' ) {

                        $mostra = mostra( 'receber', "WHERE id = '$receberId'" );
                        $clienteId = $mostra[ 'id_cliente' ];
                        if ( $mostra ) {
                            $cad[ 'nota' ] = $nota;
                            $cad[ 'link' ] = $link;
                            $cad[ 'interacao' ] = date( 'Y/m/d H:i:s' );
							$cad[ 'nota_data' ] = date( 'Y/m/d' );
                            $cad[ 'usuario' ] = $_SESSION[ 'autUser' ][ 'nome' ];
                            update( 'receber', $cad, "id = '$receberId'" );
                            unset( $cad );
                        }

                        echo '<td>' . $receberId . '</td>';

                        $clienteId = $mostra[ 'id_cliente' ];
                        $cliente = mostra( 'cliente', "WHERE id ='$clienteId '" );
                        if ( !$cliente ) {
                            echo '<td align="center">cliente Nao Encontrado</td>';
                        } else {
                            echo '<td>'.substr($cliente[ 'nome' ],0,30).'</td>';
                        }
                        echo '<td align="right">' . converteValor( $mostra[ 'valor' ] ) . '</td>';
                        echo '<td align="left">' . $nota . '</td>';
                        echo '<td align="left">' . $link . '</td>';
                        
                        $total=$total+1;
                        $valorTotal=$valorTotal+$mostra[ 'valor' ];
                        
                        echo '</tr>';
                    } //fim do receberId

                } // while


               echo '<tfoot>';
                    echo '<tr>';
                        echo '<td colspan="16">' . 'Total de Registros : ' .  $total . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td colspan="16">' . 'Valor Total R$ : ' . converteValor($valorTotal) . '</td>';
                    echo '</tr>';
                echo '</tfoot>';

                echo '</table>';

                fclose( $ponteiro );

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