 <?php 
	 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autCliente']['id'])){
				header('Location: painel.php');	
			}	
		}

		 
		if(!empty($_GET['clienteId'])){
			$clienteId = $_GET['clienteId'];
		}
		$readCliente = read('cliente',"WHERE id = '$clienteId'");
		foreach($readCliente as $cliente);

 ?><head> <meta charset="iso-8859-1"></head>


<div class="content-wrapper">
  <section class="content-header">
       <h1>Cliente</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Cadastro</a></li>
         <li class="active">Cliente</li>
       </ol>
 </section>
 
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
           <div class="box-header with-border">
            	 <?php require_once('cliente-modal.php');?>
               	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                     <?php if($acao=='contrato') echo 'Contratos';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
    
   
      <div class="box-header">
      
    

    </div><!-- /.box-header -->
     
     <div class="box-body table-responsive">  
     
     
     <?php 
	 
 	$leitura = read('contrato',"WHERE id AND id_cliente='$clienteId' ORDER BY id ASC");
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Consultor</td>
					<td align="center">Aprovação</td>
					<td align="center">Início</td>
					<td align="center">Valor Mensal</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
  
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
		
		        echo '<td>'.$mostra['controle'].'</td>';
		
				$tipocontratoId = $mostra['contrato_tipo'];
				$tipo = mostra('contrato_tipo',"WHERE id ='$tipocontratoId'");
				echo '<td>'.$tipo['nome'].'</td>';
		
				$consultorId = $mostra['consultor'];
				$consultor= mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.$consultor['nome'].'</td>';
		
		        if($mostra['tipo']=='1'){
				   echo '<td align="center">'.converteData($mostra['orc_data']).'</td>';
				  }else{
				  echo '<td align="center">'.converteData($mostra['aprovacao']).'</td>';
				}
		
				if($mostra['tipo']=='1'){
				   echo '<td align="center">-</td>';
				  }else{
				  echo '<td align="center">'.converteData($mostra['inicio']).'</td>';
				}
		
		
				if($mostra['tipo']=='1'){
				   echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				  }else{
				   echo '<td align="right">'.converteValor($mostra['valor_mensal']).'</td>';
				}
				

						echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$$mostra['id'].'">
								<img src="../admin/ico/visualizar.png" alt="Visualizar" title="Visualizar" class="tip" />
							</a>
						  </td>';	
		 
		
			echo '</tr>';
		 endforeach;
		 
		 echo '</table>';
		}
	?>
	 </div><!-- /.box-body -->
 
    </div><!-- /.box-default -->
    
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
