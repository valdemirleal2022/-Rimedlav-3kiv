
 <?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autConsultor']['id'])){
			header('Location: painel.php');	
		}	
	}

	$consultorId=$_SESSION['autConsultor']['id'];
?>


<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Onde Estou </h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Roteiro</a>
     	<li class="active">Onde Estou</li>
     </ol>
 </section>

 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
 
    <div class="box-body table-responsive">
    
          <?php 

			$consultorId=$_SESSION['autConsultor']['id'];
			$mostraconsultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$id = $mostraconsultor['id'];
			$nome = $mostraconsultor['nome'];
			$telefone = $mostraconsultor['telefone'];
			$latitude = $mostraconsultor['latitude'];
			$longitude = $mostraconsultor['longitude'];
			$data = date('d/m/Y H:i:s',strtotime($mostraconsultor['data']));
			 
			echo '<div class="col-xs-10 col-md-4 pull-left">';
				if($mostraconsultor['fotoperfil'] != '' && file_exists('../uploads/consultores/'.$mostraconsultor['fotoperfil'])){
					echo '<img src="../uploads/consultores/'.$mostraconsultor['fotoperfil'].'"/>';
				}else{
					echo '<img src="../../../site/images/autor.png"';
				}	
			 echo ' </div> ';
			
			echo '<div class="col-xs-10 col-md-4 pull-left">';		
				echo '<p>'.$nome.'</p>';
				echo '<p> <strong>Telefone : </strong> '.$telefone.'</p>';
				echo '<p> <strong>Latitude : </strong> '.$latitude.'</p>';
				echo '<p> <strong>Longitude : </strong> '.$longitude.'</p>';
				echo '<p> <strong>Data : </strong> '.$data.'</p>';
			echo ' </div> ';
		?>
                
     <div class="form-group col-xs-12 col-md-12">
          <div class="mapa">
	        <div id="mapholder"></div> <!--/mapa geolocalizacao-->
          </div><!--/mapa-->
      </div><!--/form-group col-xs-12 col-md-12-->
      
      
      
	</div> <!-- /.box-body table-responsive no-padding-->

     </div><!-- /.box -->
    </div><!-- /.col-xs-12 -->
     </div><!-- /.col-xs-12 -->
     
    
 </section><!-- /.content -->
 

  <div class="row">
 		<?php require_once("inc/geo.php"); ?>
  </div> <!-- /.box-body table-responsive no-padding-->

    
</div><!-- /.content-wrapper --> 

 