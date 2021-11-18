    <?php
	 header('Location: painel.php?execute=contrato/mapas/rota-roteiro');	
	 ?> 
    
    
    <?php require_once("inc/geo.php");?> 

<div class="content">

 		 <?php 
			
			$tecnicoId=$_SESSION['autTecnico']['id'];
			$mostratecnico = mostra('contrato_rota',"WHERE id ='$tecnicoId'");
			$id = $mostratecnico['id'];
			$nome = $mostratecnico['nome'];
			$telefone = $mostratecnico['telefone'];
			$latitude = $mostratecnico['latitude'];
			$longitude = $mostratecnico['longitude'];
			$data = date('d/m/Y H:i:s',strtotime($mostratecnico['data']));
				
			if($mostratecnico['fotoperfil']!= '' && file_exists('../uploads/rotas/'.$mostratecnico['fotoperfil'])){
					echo '<h2>
						<img src="../config/tim.php?src=../uploads/rotas/'.$mostratecnico['fotoperfil'].'&w=200&h=160&zc=1&q=100"
						 title="Ver" alt="Foto do tecnicos" />';
				  }else{
					echo '<h2>
		  				<img src="../config/tim.php?src=site/images/autor.png&w=200&h=160&zc=1&q=100"
						 title="Ver" alt="Foto do tecnicos" class="tip" />
				   	 </h2>';
			}	
				
			echo '<h1>'.$nome.'</h1>';
			echo '<h2> <strong>Telefone : </strong> '.$telefone.'</h2>';
			echo '<h2> <strong>Latitude : </strong> '.$latitude.'</h2>';
			echo '<h2> <strong>Longitude : </strong> '.$longitude.'</h2>';
			echo '<h2> <strong>Data : </strong> '.$data.'</h2>';
	
		?>
        
      <div class="boxtabela">
          <div class="mapa">
	        <div id="mapholder"></div> <!--/mapa geolocalizacao-->
           </div>
        
      </div>
      
</div><!--/content-->

