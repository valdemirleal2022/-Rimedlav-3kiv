<?php 

	if(function_exists(ProtUser)){
		
		if(!ProtUser($_SESSION['autpos_venda']['id'])){
			header('Location: painel.php');	
		}	
		
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];	
 
	
	if(isset($_POST['cadastrar'])){
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['pos_venda'] = $_SESSION['autpos_venda']['id'];
			$cad['interacao']= date('Y/m/d H:i:s');
			create('agenda_lembrete',$cad);	
	}
	
	if(!empty($_GET['agendaDeletar'])){
			$agendaId = $_GET['agendaDeletar'];
			$acao = "deletar";
		$agendaId = $_GET['agendaDeletar'];
		delete('agenda_lembrete',"id = '$agendaId'");
	}
?><head>
    <meta charset="iso-8859-1">
</head>

 



<div class="content-wrapper">
  <section class="content-header">
       <h1>Post-it</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li class="active">Post-it</li>
          </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
      <div class="box-header">

    	</div><!-- /.box-header -->
     
      <div class="box-body"> 
  		 <div class="col-md-12">  
            <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-interacao">
             		<div class="input-group">
                       <input type="text" name="descricao" class="form-control input-sm" placeholder="Texto">
                     	<div class="input-group-btn">
                   			<button class="btn btn-sm btn-default" name="cadastrar" type="submit"><i class="fa fa-floppy-o"></i></button>											
                   		</div><!-- /.input-group -->
                   	  </div><!-- /input-group-->
              </form>
        </div><!-- /.col-md-12 -->
        
       </div><!-- /.col-md-12 -->
       
       
	<div class="box-body"> 	
	  <div class="col-md-12">  
       <ul class="notes">
                    
                    
      <?php 
  		$leitura = read('agenda_lembrete',"WHERE pos_venda='$pos_vendaId' ORDER BY interacao ASC");
		if($leitura){
			foreach($leitura as $mostra):
            
                        echo '<li>';
                            echo '<div>';
                                echo ' <small>'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</small>';
                                 echo '<h4>'.$mostra['titulo'].'</h4>';
                                 echo '<p>'.$mostra['descricao'].'</p>';
                                 echo '<a href="painel.php?execute=suporte/agenda/agenda-lembrete&agendaDeletar='.$mostra['id'].'"><i class="fa fa-trash-o "></i></a>';
                            echo ' </div>';
                       echo '</li>';
        
				endforeach;
				}
			  ?> 
             </ul>
                    
            </div><!-- /.col-md-12 -->
       </div><!-- /.box-body table-responsive -->
       
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

