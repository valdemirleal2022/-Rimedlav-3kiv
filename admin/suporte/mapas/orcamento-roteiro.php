<?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data1'];
		$consultor=$_POST['consultor'];
		$mostraConsultor = mostra('contrato_consultor',"WHERE id ='$consultor'");
		$nomeconsultor=$mostraConsultor['nome'];
		$telefoneconsultor=$mostraConsultor['telefone'];
		$latitude=$mostraConsultor['latitude'];
		$longitude=$mostraConsultor['longitude'];
		$datageo=date('d/m/Y H:i:s',strtotime($mostraConsultor['data']));
		$foto = $mostraConsultor['fotoperfil'];
		if($foto != '' && file_exists('../../../uploads/consultores/'.$foto)){
			$icone='../../../uploads/consultores/'.$foto;
		  }else{
			$icone="../../../site/images/autor.png";
		}
	}
	
	if(!isset($_SESSION['dataroteiro'])){
		$dataroteiro = date( "Y/m/d");
		$_SESSION['dataroteiro']=$dataroteiro;
	}else{
		$dataroteiro=$_SESSION['dataroteiro'];
	}
	
	if(!isset($_SESSION['consultor'])){
		$consultor=$_POST['consultor'];
		$_SESSION['consultor']=$consultor ;
	}
	
	$empresa = mostra('empresa');
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	 
	 
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Roteiro de Orçamentos</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Orcamentos</li>
           <li>Roteiro</li>
         </ol>
 	</section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
          <div class="box-header">
             <h3 class="box-title"><small>Roteiro de Orçamentos : <?php echo date('d/m/Y',strtotime($dataroteiro))  ?> </small></h3>
              
               <div class="row">
                    <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                        <div class="form-group pull-left">
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        
                       <div class="form-group pull-left">
                         <select name="consultor" class="form-control input-sm">
                                <option value="">Selecione o Consultor</option>
                                <?php 
                                    $readConta = read('contrato_consultor',"WHERE id");
                                    if(!$readConta){
                                        echo '<option value="">Não temos consultor no momento</option>';	
                                    }else{
                                        foreach($readConta as $mae):
                                         if($consultor == $mae['id']){
                                          echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>    
                        </div><!-- /.form-group pull-left -->
                         <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>
                         </div><!-- /.form-group pull-left --> 
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
             </div><!-- /row-->   
       </div><!-- /box-header-->   
    
    <div class="box-body table-responsive">
       
   <?php

	if(empty($consultor)){
		$leitura = read('cadastro_visita',"WHERE id AND status='2' ORDER BY orc_data DESC, orc_hora ASC");
	 }else{
		$leitura = read('cadastro_visita',"WHERE id AND status='2' AND orc_data='$dataroteiro' 
					AND consultor='$consultor' ORDER BY orc_data DESC, orc_hora ASC"); 
	}				
	if($leitura){
			echo '<table class="table table-hover">		
					<tr class="set">
					<td>Nome</td>
					<td>Bairro</td>
					<td>Consultor</td>
					<td align="center">Orçamento</td>
					<td align="center">Interação</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
				$contador=0;
		foreach($leitura as $mostra):
		 	echo '<tr>';
				 
				echo '<td align="left">'.substr($mostra['nome'],0,15).'</td>';
				echo '<td align="left">'.substr($mostra['bairro'],0,15).'</td>';
		
				$consultorId = $mostra['consultor'];
				$mostraConsultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td align="left">'.$mostraConsultor['nome'].'</td>';
		
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['orc_data'])).'/'.$mostra['orc_hora'].' '.$mostra['orc_limite'].'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['interacao'])).'</td>';
				
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId '");
				echo '<td align="center">'.$status['nome'].'</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$mostra['id_cliente'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Cliente" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Orçamento" class="tip" />
              			</a>
				      </td>';
			echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEnviar='.$mostra['id'].'">
							<img src="ico/email.png" alt="Enviar Proposta" title="Enviar Proposta" class="tip" />
              			</a>
						</td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoMapa='.$mostra['id'].'">
							<img src="ico/mapa.png" alt="Mapa" title="Mapa" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
		 echo '</table>';
	  }
	?>   
    
</div><!-- / box-body table-responsive -->  


 </div><!-- /box box-default -->
  	</div><!-- /.col-md-12 -->
  </div><!-- /.row -->
 </section><!-- /.content -->

<section class="content">
  <div class="box box-warning">
     <div class="box-body">    
            
    <div id="mp-container" class="mp-container">
        <div id="ss-container" class="ss-container">
		<div id="map_canvas"> 
    		<div id="map"> <!-- area de carregar o mapa -->
					<span style="color:Gray;">Loading map...</span>		
            </div><!-- map -->
				<script type="text/javascript">
				
					var locations =[
					
							<?php 
								if($latitude<>''){
									$lapt = $latitude;
									$long = $longitude;
									echo "['<div class=info><h4>$nomeconsultor</h4><br><img src=$icone></a><br><p>$datageo<br>$telefoneconsultor<br></p></div>', $lapt, $long],";
					 			 }else{
									$icone="suporte/mapas/images/marker07.png";
									$hora="07:00";
									$data=$dataroteiro;
									$nome="Toyama Dedetizadora";
									$endereco = $empresa['endereco'].', '.$cliente['empresa'].' - '.$empresa['cidade'].' - '.$empresa['cep'];
									$geo=geo($endereco);
									$lapt = $geo[0];
									$long = $geo[1];
									
									echo "['<div class=info><h4>$nome</h4><br><img src=$icone></a><br><p>$endereco<br>$data<br>$hora</p></div>', $lapt, $long],";
			
								}

					if(empty($consultor)){
						$leitura = read('cadastro_visita',"WHERE status='2' AND orc_data='$dataroteiro'
						    	   ORDER BY orc_data DESC, orc_hora ASC");
					  }else{
						$leitura = read('cadastro_visita',"WHERE status='2' AND orc_data='$dataroteiro' 
								   AND consultor='$consultor' ORDER BY orc_data DESC, orc_hora ASC"); 
					}	
															
					if($leitura){
									foreach($leitura as $servico):
										$orcamentoId=$servico['id'];
										$clienteId=$servico['id_cliente'];
										$hora=$servico['orc_hora'];
										if($hora=='08:00'){
											$hora08='08:00';
										}
										if($hora=='09:00'){
											$hora09='09:00';
										}
										if($hora=='10:00'){
											$hora10='10:00';
										}
										if($hora=='11:00'){
											$hora11='11:00';
										}
										if($hora=='12:00'){
											$hora12='12:00';
										}
										if($hora=='13:00'){
											$hora13='13:00';
										}
										if($hora=='14:00'){
											$hora14='14:00';
										}
										if($hora=='15:00'){
											$hora15='15:00';
										}
										if($hora=='16:00'){
											$hora16='16:00';
										}
										if($hora=='17:00'){
											$hora17='17:00';
										}
										if($hora=='18:00'){
											$hora18='18:00';
										}
										$data=date('d/m/Y',strtotime($servico['orc_data']));
										 
										$nome=substr($servico['nome'],0,30);
										$icone="suporte/mapas/images/editar.png";
										$endereco = $servico['endereco'].', '.$servico['numero'].$servico['bairro'].' - '.$servico['cidade'].' - '.$servico['cep'];
										$geo=geo($endereco);
										$lapt = $geo[0];
										$long = $geo[1];
										
										//// pegando a lat e long direto do cliente
//										$lapt = $cliente['latitude'];
//										$long = $cliente['longitude'];
										
										echo "['<div class=info><h4>$nome</h4><br><a href=http://www.toyamadedetizadora.com.br/admin/painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar=$orcamentoId><img src=$icone></a><br><p>$endereco<br>$data<br>$hora</p></div>', $lapt, $long],";
									endforeach;
								  }else{
								   echo "<h3 align='center'><font color='#ff0000'>No Content Found</font></h3>";
								  }
							?>
						];
						
    var iconURLPrefix = 'suporte/mapas/images/';
    var icons = [  
	  iconURLPrefix + 'geo.png',
	 <?php
	    if(isset($hora08)){
		  ?>
		   iconURLPrefix + 'marker08.png',
		 <?php 
		} 
	  ?>
	  <?php
	    if(isset($hora09)){
		  ?>
		   iconURLPrefix + 'marker09.png',
		 <?php 
		} 
	  ?>
	  <?php
	    if(isset($hora10)){
		  ?>
		   iconURLPrefix + 'marker10.png',
		 <?php 
		} 
	  ?>
	  <?php
	    if(isset($hora11)){
		  ?>
		   iconURLPrefix + 'marker11.png',
		 <?php 
		} 
	  ?>
	  <?php
	    if(isset($hora12)){
		  ?>
		   iconURLPrefix + 'marker12.png',
		 <?php 
		} 
	  ?>
	  <?php
	    if(isset($hora13)){
		  ?>
		   iconURLPrefix + 'marker13.png',
		 <?php 
		} 
	  ?>
	   <?php
	    if(isset($hora14)){
		  ?>
		   iconURLPrefix + 'marker14.png',
		 <?php 
		} 
	  ?>
	   <?php
	    if(isset($hora15)){
		  ?>
		   iconURLPrefix + 'marker15.png',
		 <?php 
		} 
	  ?>
	   <?php
	    if(isset($hora16)){
		  ?>
		   iconURLPrefix + 'marker16.png',
		 <?php 
		} 
	  ?>
	   <?php
	    if(isset($hora17)){
		  ?>
		   iconURLPrefix + 'marker17.png',
		 <?php 
		} 
	  ?>
	   <?php
	    if(isset($hora18)){
		  ?>
		   iconURLPrefix + 'marker18.png',
		 <?php 
		} 
	  ?>
	 // iconURLPrefix + 'marker1.png',
//      iconURLPrefix + 'marker2.png',
//      iconURLPrefix + 'marker3.png',
//      iconURLPrefix + 'marker4.png',
//      iconURLPrefix + 'marker5.png',
//      iconURLPrefix + 'marker6.png', 
//	  iconURLPrefix + 'marker7.png', 
//	  iconURLPrefix + 'marker8.png',      
//      iconURLPrefix + 'marker9.png'
    ]
    var icons_length = icons.length;
    var shadow = {
      anchor: new google.maps.Point(10,13),
      url: iconURLPrefix + 'msmarker.shadow.png'
    };
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 5,
      center: new google.maps.LatLng(<?php echo $lapt ;?>, <?php echo $long ;?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
	  disableDefaultUI: true,
      panControl: false,
      zoomControlOptions: {
      position: google.maps.ControlPosition.LEFT_BOTTOM
      }
    });
    var infowindow = new google.maps.InfoWindow({
      maxWidth: 450,
	  maxHeight: 550
    });
    var marker;
    var markers = new Array();
    var iconCounter = 0;
    for (var i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2], locations[i][3], locations[i][4], locations[i][5]),
        map: map,
        icon : icons[iconCounter],
        shadow: shadow
      });
      markers.push(marker);
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      iconCounter++;
      if(iconCounter >= icons_length){
      	iconCounter = 0;
      }
    }

    function AutoCenter() {
      var bounds = new google.maps.LatLngBounds();
      $.each(markers, function (index, marker) {
        bounds.extend(marker.position);
      });
      map.fitBounds(bounds);
    }
    AutoCenter();
  </script> 
  
  </div>
   </div>
  </div>
  
  
   </div><!-- /.box-body -->
  </div><!--/box box-default-->
 </section><!-- /.content -->

</div><!-- /.content-wrapper -->