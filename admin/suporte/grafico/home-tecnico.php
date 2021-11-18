<head>
    <meta charset="iso-8859-1">
</head>
    
       
 <div class="content-wrapper">
 
    <section class="content-header">
          <h1>Técnicos <small>
             <?php
			 $mes = date('m/Y');
			 $mesano = explode('/',$mes);
			 echo $mes;				   
			?> 
          </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel</li>
          </ol>
        </section>

 	 

              <?php  
			
				$mes = date('m/Y');
				$mesano = explode('/',$mes);	
					
				$contrato00 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$mesano[0]' AND 
									Year(contrato)='$mesano[1]' AND situacao<>'4'" ,'valor');
				$contrato00 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$mesano[0]' AND 									Year(contrato)='$mesano[1]' AND situacao<>'4'",'valor');
				$orcamento00 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$mesano[0]'
									 AND Year(orc_data)='$mesano[1]'",'orc_valor');
				$venda00=$contrato00+$contrato00;

		?>
        
  <section class="content">
  
  <div class="row">
  
   		<?php 
  		$leitura = read('contrato_tecnico',"WHERE id ORDER BY nome ASC");
		if($leitura){
			foreach($leitura as $mostra):
            ?> 
            
            <div class="col-md-3">
              <div class="box box-primary">
                <div class="box-body box-profile">
                
                 <?php 
				 if($mostra['fotoperfil'] != '' && file_exists('../uploads/tecnicos/'.$mostra['fotoperfil'])){
					echo '<img class="profile-user-img img-responsive img-circle" src="../uploads/tecnicos/'.$mostra['fotoperfil'].'"/>';
				  }
                  echo '<h3 class="profile-username text-center">' . $mostra['nome'] . '</h3>';
                  echo '<p class="text-muted text-center">Técnicos</p>';
					
				  $tecnicoId=$mostra['id'];
                  echo '<ul class="list-group list-group-unbordered">';
						
					$contrato1 = conta('contrato',"WHERE tipo<>'1' AND Month(contrato)='$mesano[0]' AND 
								Year(contrato)='$mesano[1]' AND situacao<>'4' AND tecnico1='$tecnicoId'" );
								
					$contrato2 = conta('contrato',"WHERE tipo<>'1' AND Month(contrato)='$mesano[0]' AND 
								Year(contrato)='$mesano[1]' AND situacao<>'4' AND tecnico2='$tecnicoId'" );	
								
					$total=$contrato1+$contrato2 ;		
								
					$contrato3 = soma('contrato',"WHERE tipo<>'1' AND Month(contrato)='$mesano[0]' AND 
								Year(contrato)='$mesano[1]' AND situacao<>'4' AND tecnico1='$tecnicoId'" ,'valor');
					
					$contrato4 = soma('contrato',"WHERE tipo<>'1' AND Month(contrato)='$mesano[0]' AND 
								Year(contrato)='$mesano[1]' AND situacao<>'4' AND tecnico2='$tecnicoId'" ,'valor');
								
					$total_valor=$contrato3+$contrato4 ;	
								
                    echo '<li class="list-group-item">';
                    echo '<b>Total de Serviços</b> <a class="pull-right">' . $total . '</a>';
                    echo '</li>';
					
                    echo '<li class="list-group-item">';
                    echo '<b>Valor dos Serviços</b> <a class="pull-right">' . converteValor($total_valor) . '</a>';
                    echo ' </li>';
			 		
                    echo '<li class="list-group-item">';
                    echo '<b>Manutenção</b> <a class="pull-right">13,287</a>';
                    echo '</li>';
					
					echo '<li class="list-group-item">';
                    echo '<b>Abastecimento</b> <a class="pull-right">13,287</a>';
                    echo '</li>';
					
                  echo '</ul>';

					?> 
                  
                </div><!-- /.box-body box-profile -->
              </div><!-- /.box box-primary -->
             </div><!-- /col-md-3 -->
             
     <?php 
		endforeach;
		}
	  ?> 

</div><!-- /row -->

</section>


             

</div><!-- /.content-wrapper -->

