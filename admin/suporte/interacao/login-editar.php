<?php 
		
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}

		}

		if(!empty($_GET['loginEditar'])){
			$loginId = $_GET['loginEditar'];
			$acao = "visualizar";
		}
		if(!empty($loginId)){
			$readusuario = read('usuarios_login',"WHERE id = '$loginId'");
			if(!$readusuario){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readusuario as $edit);
			$usuarioId = $edit['id_usuario'];
			$usuarios= mostra('usuarios',"WHERE id ='$usuarioId'");
			
				if(!empty($usuarios['id_usuario'])){
					$usuarioId = $usuarios['id_usuario'];
					$usuarioMostra= mostra('usuarios',"WHERE id ='$usuarioId'");
				}
				if(!empty($usuarios['id_consultor'])){
					$usuarioId = $usuarios['id_consultor'];
					$usuarioMostra= mostra('contrato_consultor',"WHERE id ='$usuarioId'");
				}
				if(!empty($usuarios['id_rota'])){
					$usuarioId = $usuarios['id_rota'];
					$usuarioMostra= mostra('contrato_rota',"WHERE id ='$usuarioId'");
				}
			
		}
?>

<div class="content-wrapper">

    <section class="content-header">
          <h1>Login</h1>
          <ol class="breadcrumb">
            <li>Home</a></li>
            <li>Interação</a></li>
            <li class="active">Login</li>
          </ol>
     </section>

 <section class="content">
      <div class="box box-default">
           
            <div class="box-header with-border">
             <h1><?php echo $edit['nome'];?></h1>	
              <div class="box-tools">
                        <small>
                         <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                         <?php if($acao=='deletar') echo 'Deletando';?>
                         <?php if($acao=='atualizar') echo 'Alterando';?>
                         <?php if($acao=='baixar') echo 'Baixando';?>
                         <?php if($acao=='visualizar') echo 'Visualizar';?>
                        </small>
                    </div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
          
     	<div class="box-body">
       
 	    
     <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
     		 
   
     <div class="form-group col-xs-12 col-md-3"> 
          	<?php 
				if($usuarios['fotoperfil'] != '' && file_exists('../uploads/usuarios/'.$usuarios['fotoperfil'])){
					echo '<img src="../uploads/usuarios/'.$usuarios['fotoperfil'].'"/>';
				}
			?>
       </div>
    	 <div class="form-group col-xs-12 col-md-3"> 
           	<label>Nome</label>
            <input name="nome" class="form-control" type="text" value="<?php echo $usuarioMostra['nome'];?>"  disabled  />
       	  </div>
       	  
       	   <div class="form-group col-xs-12 col-md-3"> 
           	<label>Data do Login</label>
            <input name="nome" class="form-control" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['data']));?>" disabled />
       	  </div>
     
      <div class="form-group col-xs-12 col-md-12"> 
		  <div class="box-footer">
			<a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-danger"> </a>
		</div>
    </div>
    
   </form>
 
	</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
 </section>
   
 <section class="content">
 <div class="box box-default">
  <div class="box-body">
  
     <div class="form-group col-xs-12 col-md-12">
          <div class="mapa">
	        <div id="map"></div> <!--/mapa geolocalizacao-->
          </div><!--/mapa-->
      </div><!--/form-group col-xs-12 col-md-12-->
      

	</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

<script>
      function initMap() {
        var uluru = {lat: <?php echo $edit['latitude'];?>, lng: <?php echo $edit['longitude'];?>};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
 </script>
 
 <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4b2zbrrO2vKHiMYGQDKip9o_zS05dNUU&callback=initMap">
 </script>



 