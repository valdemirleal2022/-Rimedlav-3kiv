<?php 

	if(function_exists(ProtUser)){
		
		if(!ProtUser($_SESSION['autpos_venda']['id'])){
			header('Location: painel.php');	
		}	
		
	}


	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
		
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];
 
	$total = conta('contrato_atendimento_pos_venda',"WHERE pos_venda='$pos_vendaId' AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
	$leitura = read('contrato_atendimento_pos_venda',"WHERE pos_venda='$pos_vendaId' AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
	
  <section class="content-header">
       <h1>Atendimentos Pos-Venda</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Contrato</a>
            <li class="active">Atendimentos</li>
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
                                                      
                    </form> 
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
          </div><!-- /box-header-->   
		 
		 	 
  <div class="box-body table-responsive">
     
 <?php
	
	 
	if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Tipo</td>
					<td align="center">Data</td>
					<td align="center">Origem</td>
					<td align="center">Atendente</td>
					<td align="center">Solicitacao</td>
					<td align="center">Motivo</td>
					<td align="center">E</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
	
				$clienteId = $mostra['id_cliente'];
				$contratoId = $mostra['id_contrato'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		
				echo '<td>'.substr($cliente['nome'],0,15).'</td>';
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				$contratoTipoId = $contrato['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
		
				echo '<td>'.converteData($mostra['data_solicitacao']).'</td>';
		
				$origemId = $mostra['id_origem'];
				$origem = mostra('pedido_origem',"WHERE id ='$origemId '");
				echo '<td>'.$origem['nome'].'</td>';
			 
				echo '<td>'.substr($mostra['atendente_abertura'],0,10).'</td>';
				echo '<td>'.substr($mostra['solicitacao'],0,15).'</td>';
				
				$suporteId = $mostra['id_suporte'];
				$suporteMostra = mostra('pedido_suporte',"WHERE id ='$suporteId '");
				echo '<td>'.substr($suporteMostra['nome'],0,15).'</td>';
				if($mostra['cliente_solicitou']==1){
					echo '<td align="center"><img src="../admin/ico/usuario.png" title="Solicitado pelo Cliente"/></td>';
				}else{
					echo '<td>-</td>';
				 }
				echo '<td>'.$mostra['status'].'</td>';
		 
	
				echo '<td align="center">
						<a href="painel.php?execute=suporte/atendimento/atendimento-editar&atendimentoEditar='.$mostra['id'].'">
			  				<img src="../admin/ico/visualizar.png"  title="Editar Visualizar" />
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
