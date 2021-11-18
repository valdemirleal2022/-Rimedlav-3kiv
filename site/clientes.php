<head>
    <meta charset="iso-8859-1">
 </head>
 
 
 <div class="content container">

      <div class="page-wrapper">
      
               <header class="page-heading clearfix">
                     <h1 class="heading-title pull-left">Clientes</h1>
                    <div class="breadcrumbs pull-right">
                        <ul class="breadcrumbs-list">
                            <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                            <li><a href="<?php echo URL.'/clientes/'?>">Clientes</a><i class="fa fa-angle-right"></i></li                        </ul>
                    </div><!--//breadcrumbs-->
                </header> 
                
                <div class="page-content">
                
                    <div class="row page-row">
                  
                        <div class="course-wrapper col-md-8 col-sm-7"> 
                         <div class="table-responsive"> 
                          
                          <table class="table table-hover">
                                <thead>
                                      <tr>
                                            <th>Id</th>
                                                <th>Empresa</th>
                                                <th>Endere&ccedil;o</th>
                                                <th>Telefone</th>
                                                <th>Sistema</th>
                                         </tr>
                               </thead>
                           <tbody>
                                        
                                      
   	<?php    
    $leitura = read('cliente',"WHERE id AND status='1' AND visualizar='1' ORDER BY id_sistema ASC, nome ASC");
	if(leitura){
		$contador=0;
 
		foreach($leitura as $cliente):
				$contador++;
				$sistemaId=$cliente['id_sistema'];
				$sistema = mostra('sistema',"WHERE id = '$sistemaId'"); 
				echo '<tr>';
                     echo '<td>'.$contador.'</td>';
                     echo '<td>'.resumos(ucwords(strtolower($cliente['nome'])),$palavras = '28').'</td>';
					 echo '<td>'.ucwords(strtolower($cliente['bairro'])).' - '. ucwords(strtolower($cliente['cidade'])).' - '. strtoupper($cliente['uf']).'</td>';
					 echo '<td>'.resumos(ucwords(strtolower($cliente['telefone'])),$palavras = '15').'</td>';
					  echo '<td> <span class="label label-success">'.$sistema['nome'].'</td>';
                     echo '<tr>';
		 endforeach;
	}
	
	?>
     							 </tbody>
                           </table><!--//table-->

                        </div><!--//course-wrapper col-md-8 col-sm-7-->                      
                    </div><!--//course-wrapper col-md-8 col-sm-7-->
                 <?php require("site/inc/sidebar-tab.php");?>
               </div><!--//row page-row-->
           </div><!--//page-content-->
         </div><!--//page--> 
      </div><!--//content-->
 </div><!--//wrapper-->
     