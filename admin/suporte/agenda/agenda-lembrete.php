<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
		
	$usuarioId=$_SESSION['autUser']['id'];
	
	if(isset($_POST['cadastrar'])){
		$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
		$cad['usuario'] = $_SESSION['autUser']['id'];
		$cad['interacao']= date('Y/m/d H:i:s');
		create('agenda_lembrete',$cad);	
	}
	
	if(!empty($_GET['agendaDeletar'])){
		$agendaId = $_GET['agendaDeletar'];
		$acao = "deletar";
		$agendaId = $_GET['agendaDeletar'];
		delete('agenda_lembrete',"id = '$agendaId'");
	}
	
	

	//$data1 = date("Y-m-d");
//	$agenda = conta('agenda',"WHERE retorno<='$data1' AND status='1'"); 
//	$agendaNegociacao = conta('agenda_negociacao',"WHERE retorno<='$data1' AND status='1'"); 
//	$agendaTotal=$agenda+$agendaNegociacao ;
//	if($agendaTotal<>0){
//			$mensagem='Existe negociação pendente hoje';
//	//		echo "<script>toastr.success('". $mensagem ."');</script>";
//	 }
		

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
			$leitura = read('agenda_lembrete',"WHERE usuario='$usuarioId' ORDER BY interacao ASC");
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

