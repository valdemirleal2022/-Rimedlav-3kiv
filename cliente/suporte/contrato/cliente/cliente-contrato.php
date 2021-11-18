<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');	
			}	
		}
	
		$clienteId = $_SESSION['autUser']['id'];
		$readCliente = read('cliente',"WHERE id = '$clienteId'");
		if(!$readCliente){
			header('Location: painel.php?execute=suporte/error');
		}
		foreach($readCliente as $cliente);
		$id_sistema=$cliente['id_sistema'];
		$sistemaId=$cliente['id_sistema'];
		
		$readSistema = read('sistema',"WHERE id = '$sistemaId'");
		if(!$readSistema){
			header('Location: painel.php?execute=suporte/error');
		}
		foreach($readSistema as $sistema);
		$contrato=$sistema['contrato'];

?>

<div class="content">
	<h1>Cliente : <strong><?php echo $cliente['nome'];?></strong></h1>
	

    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
            
             <p>
             <textarea name="contrato" cols="110" rows="10" readonly class="estilotextarea" id="contrato">
                    <?PHP print $contrato;  ?>
             </textarea>
           </p>
           <p>
     		 <input type="checkbox" name="termos_contrato" id="termos_contrato" value="concordo">
     			 Li integralmente o contrato acima e concordo com os termos do mesmo.
             </p>
               <p><a href="cliente-contrato.php">
                 <input name="imprimir" type="button" value="Imprimir">
               </a>
            
          
		 
		</form>
    
</div><!--/content-->
 