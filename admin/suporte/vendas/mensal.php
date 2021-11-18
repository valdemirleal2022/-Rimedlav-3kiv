<?php 



		 if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}

		$data1 = converteData1();
		$data2 = converteData2();
		
		if(isset($_POST['pesquisar'])){
			$data1=strip_tags(trim(mysql_real_escape_string($_POST['data1'])));
			$data2=strip_tags(trim(mysql_real_escape_string($_POST['data2'])));
		}
	
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Vendas por Consultor</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Vendas</a>
     	<li class="active">Mensal</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
            <div class="box-header">
               <div class="row">
                    <div class="col-xs-10 col-md-4 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>
                    </form> 
                  </div><!-- /col-xs-4-->
              </div><!-- /box-header-->   
    </div><!-- /box-header--> 
            
      
    
    <div class="box-body table-responsive">
    <?php 
	
	$total=0;
	$valor_total=0;								  
	 
 	$leitura = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
	if($leitura){
			 echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Foto</td>
					<td align="center">Nome</td>
					<td align="center">Email</td>
					<td align="center">Quantidade</td>
					<td align="center">Valor</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';

				if($mostra['fotoperfil']!= '' && file_exists('../uploads/consultores/'.$mostra['fotoperfil'])){
                        echo '<td align="center">
                              <img class="img-thumbnail imagem-tabela" src="'.URL.'/uploads/consultores/'
                                         .$mostra['fotoperfil'].'">';
                      }else{
                        echo '<td align="center">
                             <i class="fa fa-picture-o"></i>
                         </td>';
                }	
			 		
				
				
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['email'].'</td>';
				$consultorId=$mostra['id'];
				$consultorNome=$mostra['nome'];
	
				$valor = soma('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1' AND aprovacao<='$data2' AND status<>'9'AND consultor='$consultorId'",'valor_mensal');
		
				$contrato = conta('contrato',"WHERE id AND tipo='2' AND aprovacao>='$data1' AND aprovacao<='$data2' AND status<>'9'AND consultor='$consultorId'");
								
				
				echo '<td align="right">'.$contrato.'</td>';
				echo '<td align="right">'.converteValor($valor).'</td>';
					
				$valor_total = $valor_total + $valor;
		
				$total = $total + $contrato;
	
			echo '</tr>';
		
		 endforeach;
		 
		 	echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="11">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
                        echo '</tr>';
  
          	echo '</tfoot>';
		
		echo '</table>';
		
		}
		
		
		
		
	?>
    
    
    	</div><!-- /.box-body table-responsive -->
       
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->

 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
