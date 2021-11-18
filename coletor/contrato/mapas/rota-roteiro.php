<?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autRota']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
	

	if(!isset($_SESSION['dataEmissao'])){
		$dataroteiro = date( "Y/m/d");
		$_SESSION['dataEmissao']=$dataroteiro;
	}else{
		$dataroteiro=$_SESSION['dataEmissao'];
	}

	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data'];
		$_SESSION['dataEmissao']=$dataroteiro;
	}
	
	$rotaId=$_SESSION['autRota']['id'];
	$rotaNome=$_SESSION['autRota']['nome'];
	
	$empresa = mostra('empresa');
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	 
?>
 		
        
<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Rota</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Ordem de Serviço</a>
     	<li class="active">Rota</li>
     </ol>
 </section>

 
 <section class="content">
   <div class="row">
    <div class="col-xs-12">
         <div class="box">
         
             <div class="box-header">
                 
               <div class="row">
                    <div class="col-xs-10 col-md-4 pull-right">
                    
                       <form name="form-pesquisa" method="post">
                        <div class="form-group pull-left">
                        <input type="date" name="data" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        <div class="form-group pull-left">

               		  
                   </div><!-- /.input-group -->
                  <button class="btn btn-sm btn-default"  name="pesquisar" type="submit"><i class="fa fa-search"></i></button>                            
              </form>
          </div><!-- /col-xs-10 col-md-4 pull-right-->
     </div><!-- /row-> -->
</div><!-- /box-header-> -->

<div class="box-body table-responsive">

    <?php

	$leituraRota = read('contrato_ordem',"WHERE id AND data='$dataroteiro' AND rota='$rotaId' AND status='12' ORDER BY data DESC, hora ASC"); 
							   
	//$leituraRota = read('ordem_ordem',"WHERE id ORDER BY data DESC, hora ASC"); 
							   
	if($leituraRota){
			echo '<table class="table table-hover">
					<td align="center" colspan="1">Baixar</td>
					<td align="center">N</td>
					<td align="center">Id</td>
					<td align="center">Hora</td>
					<td>Nome</td>
					<td>Bairro</td>
					<td align="center">Tipo de Coleta</td>
					<td align="center">Coletado</td>
					<td align="center">Posição</td>
				 	<td align="center" colspan="1">Imprimir</td>
				</tr>';
				$contador=1;
		foreach($leituraRota as $mostra):
		 	echo '<tr>';
		
				if($mostra['status']=='12'){
					 echo '<td align="center">
						<a href="painel.php?execute=contrato/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
			  				<img src="../admin/ico/termino.png" alt="Realizado" title="Coleta Realizado" class="tip" />
              			</a>
				 </td>';
				}else{
					echo '<td align="center">-</td>';
				}
		
			
				 
				echo '<td>'.$contador++.'</td>';
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td align="center">'.$mostra['hora'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td align="left">'.substr($cliente['nome'],0,17).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';


				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
				echo '<td>'.$coleta['nome'].'</td>';
				echo '<td align="center">'.$mostra['quantidade1'].'</td>';
		
				$status='';
				$horaatual=date("H:i");
				if($horaatual>$mostra['hora']){
					$status='Atrasado'; 
				}
				if(!empty($mostra['hora_coleta'])){
					$status=$mostra['hora_coleta']; 
				}
				
				echo '<td align="left">'.$status.'</td>';
		 
				
		
				echo '<td align="center">
							<a href="painel2.php?execute=contrato/ordem/ordem-servico&ordemImprimir='.$mostra['id'].'" target="_blank">
								<img src="../admin/ico/imprimir.png" alt="Imprimir" title="Imprimir"  />
							</a>
						 </td>';
  
				

				 
			echo '</tr>';
		 endforeach;
		 echo '</table>';
		 
		}
	?>
 </div>
 

	
      </div> <!-- /.box-body table-responsive no-padding-->
     </div><!-- /.box -->
    </div><!-- /.col-xs-12 -->
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper --> 

