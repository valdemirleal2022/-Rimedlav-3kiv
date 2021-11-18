<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data1' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/ficha-contrato2-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'data1' ] = $_POST[ 'data1' ];
		$_SESSION[ 'data2' ] = $_POST[ 'data1' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-conferencia-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data1'];
		$rotaId = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$_SESSION[ 'dataroteiro' ] = $dataroteiro;
	}

	if (!isset( $_SESSION[ 'dataroteiro' ] ) ) {
		$dataroteiro = date( "Y/m/d" );
		$_SESSION[ 'dataroteiro' ] = $dataroteiro;
	} else {
		$dataroteiro = $_SESSION[ 'dataroteiro' ];
		$rotaId = $_SESSION[ 'rotaColeta' ];
	}

	$pag = ( empty( $_GET[ 'pag' ] ) ? '1' : $_GET[ 'pag' ] );
	$maximo = '50';
	$inicio = ( $pag * $maximo ) - $maximo;

	$dia_semana = diaSemana($dataroteiro);
	$numero_semana = numeroSemana($dataroteiro);

	if ( $numero_semana == 0 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND domingo='1'" );
	}
	if ( $numero_semana == 1 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND segunda='1' AND segunda_rota1='$rotaId' ORDER BY segunda_hora1" );
		$total = conta( 'contrato', "WHERE id AND status='5' AND terca='1' AND terca_rota1='$rotaId' ORDER BY terca_hora1" );
	}
	if ( $numero_semana == 2 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND terca='1' AND terca_rota1='$rotaId' ORDER BY terca_hora1" );
		$total = conta( 'contrato', "WHERE id AND status='5' AND terca='1' AND terca_rota1='$rotaId' ORDER BY terca_hora1" );
	}
	if ( $numero_semana == 3 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND quarta='1'" );
	}
	if ( $numero_semana == 4 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND quinta='1'" );
	}
	if ( $numero_semana == 5 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sexta='1'" );
	}
	if ( $numero_semana == 6 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sabado='1'" );
	}

	$dia_semana = diaSemana( $dataroteiro );
	$numero_semana = numeroSemana( $dataroteiro );


$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];


?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Contrato por Rota</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Contrato</a>
            </li>
            <li>Contrato por Rota</a>
            </li>
        </ol>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">

                    <div class="box-header">
           
                         <div class="col-xs-6 col-md-8 pull-right">
        
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
                                
                                  <div class="form-group pull-left">         
										<input type="text" name="dia" value="<?php echo $dia_semana; ?>" class="form-control input-sm" disabled>
									 </div> <!-- /.input-group -->


                                 
                                 <div class="form-group pull-left">
										<input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
								</div><!-- /.input-group -->
                                
                                <div class="form-group pull-left">
								  <select name="rota"  class="form-control input-sm" >
									<option value="">Selecione tipo de coleta</option>
									<?php 
									$rotaRead = read('contrato_rota',"WHERE id ORDER BY nome ASC");
										if(!$rotaRead){
										echo '<option value="">Nao temos tipo de coleta no momento</option>';	
									}else{
										foreach($rotaRead as $mae):
											if($rotaId == $mae['id']){
											echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
										 }else{
											echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
											}
										endforeach;	
									}
									?> 
								  </select>
							  </div>

                              <div class="form-group pull-left">
								<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
								 <i class="fa fa-search"></i></button>
							 </div><!-- /.input-group -->

							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->

                              </form>
                         </div>  <!-- /col-xs-6 col-md-5 pull-right-->
                    </div>   <!-- /box-header-->

          <div class="box-body table-responsive">

	 <?php
		 
	$enderecoPartida='Avenida Guilherme Maxwel, 156 - Bonsucesso, Rio de Janeiro';
	$enderecoChegada='Avenida Guilherme Maxwel, 256 - Bonsucesso, Rio de Janeiro';
	$enderecoAterro='Estrada Santa Rosa, s/n - Chapero, Seropédica';
		 
	$endereco1='';
	$endereco2='';
	$endereco3='';
	$endereco4='';
	$endereco5='';
	$endereco6='';
	$endereco7='';
	$endereco8='';	
	$endereco9='';
	$endereco10='';
	$endereco11='';
	$endereco12='';
	$endereco13='';
	$endereco14='';
	$endereco15='';
	$endereco16='';	
	$endereco17='';
	$endereco18='';
	$endereco19='';
	$endereco20='';
	$endereco22='';	
	$endereco20='';
	$endereco22='';	
			  
	 

	if($leitura){
					
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">N</td>
					<td align="center">Id</td>
					<td align="center">Hora</td>
					<td align="center">Rota</td>
					<td>Nome</td>
					<td>Bairro</td>
					<td>Endereço</td>
					<td>Numero</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center">Coleta</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		
				$contador=0;
				$letras='BCDEFGHIJKLMNOPQRSTUVWABCDEFGHIJKLMNOPQRSTUVW';
		
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
				
				
				if ( $numero_semana == 0 ) {
					$hora = $mostra['domingo_hora1'];
					$rota = $mostra['domingo_rota1'];
				}
				if ( $numero_semana == 1 ) {
					$hora = $mostra['segunda_hora1'];
					$rota = $mostra['segunda_rota1'];
				}
				if ( $numero_semana == 2 ) {
					$hora = $mostra['terca_hora1'];
					$rota = $mostra['terca_rota1'];
				}
				if ( $numero_semana == 3 ) {
					$hora = $mostra['quarta_hora1'];
					$rota = $mostra['quarta_rota1'];
				}
				if ( $numero_semana == 4 ) {
					$hora = $mostra['quinta_hora1'];
					$rota = $mostra['quinta_rota1'];
				}
				if ( $numero_semana == 5 ) {
					$hora = $mostra['sexta_hora1'];
					$rota = $mostra['sexta_rota1'];
				}
				if ( $numero_semana == 6 ) {
					$hora = $mostra['sabado_hora1'];
					$rota = $mostra['sabado_rota1'];
				}
		
				echo '<td>'.substr($letras,$contador,1).'</td>';
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td align="center">'.$hora.'</td>';
			
				$rota = mostra('contrato_rota',"WHERE id ='$rota'");
				echo '<td>'.$rota['nome'].'</td>';
		
				$clienteId = $mostra['id_cliente'];	
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td>'.substr($cliente['bairro'],0,12).'</td>';
				echo '<td>'.substr($cliente['endereco'],0,40).'</td>';
				echo '<td align="right">'.$cliente['numero'].'</td>';
		
				$endereco[$contador] = tirarAcentos($cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].', '.$cliente['cidade']);
				$contador++;

				$contratoTipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
		
				$contratoId = $mostra['id'];
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato  ='$contratoId'");
		
				$tipoColetaId = $contratoColeta['tipo_coleta'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$tipoColeta['nome'].'</td>';
		
				if(empty($endereco1)){
					$endereco1 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'];
				}elseif(empty($endereco2)){
					$endereco2 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'];
				}elseif(empty($endereco3)){
					$endereco3 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'];
				}elseif(empty($endereco4)){
					$endereco4 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco5)){
					$endereco5 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco6)){
					$endereco6 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco7)){
					$endereco7 = $cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco8)){
					$endereco8 = $cliente['endereco'].','.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco8)){
					$endereco8 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco9)){
					$endereco9 = $cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco10)){
					$endereco10 = $cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco11)){
					$endereco11 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco12)){
					$endereco12 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco13)){
					$endereco13 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco14)){
					$endereco14 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco15)){
					$endereco15 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco16)){
					$endereco16 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco17)){
					$endereco17 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco18)){
					$endereco18 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco19)){
					$endereco19 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco20)){
					$endereco20 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco21)){
					$endereco21 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco22)){
					$endereco22 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco23)){
					$endereco23 = url($cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade']);
				}elseif(empty($endereco24)){
					$endereco24 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
			   }elseif(empty($endereco25)){
					$endereco25 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco26)){
					$endereco26 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco27)){	
					$endereco27 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco28)){
					$endereco28 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco29)){
					$endereco29 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco30)){
					$endereco30 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
			   }

				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar Contrato" class="tip" />
              			</a>
				      </td>';
	
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
         echo '</tfoot>';
		 
		 echo '</table>';
		 
		 $link = 'painel.php?execute=suporte/ordem/ordem-agendadas&pag=';
	
		 pag('contrato_ordem',"WHERE id AND status='12' AND data='$dataroteiro' ORDER BY data ASC, hora ASC", $maximo, $link, $pag);

		}
		
		?>
		<div class="box-footer">
       
        <?php 
			echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
		?>

       </div><!-- /.box-footer-->

	  </div><!-- /.box-body table-responsive -->

    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 </section><!-- /.content -->
  
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
          <div class="box-header">
		   <div class="col-md-8" id="mapa"></div>	
		   <div class="col-md-4" id="trajeto-texto" ></div>
         
          </div> <!-- /.box-title-->  	
      </div> <!-- /.box box-default-->
     </div><!-- /.col-md-12 -->
     </div><!-- /.row--> 
  </section><!-- /.content -->
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
          <div class="box-header">
		   <div class="col-md-8" id="mapa2"></div>	
		   <div class="col-md-4" id="trajeto-texto2" ></div>
            	
          </div> <!-- /.box-title-->  	
      </div> <!-- /.box box-default-->
     </div><!-- /.col-md-12 -->
     </div><!-- /.row--> 
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper --> 


<script>
	
	var endereco1 = "<?php echo $endereco1;?>";
	var endereco2 = "<?php echo $endereco2;?>";
	var endereco3 = "<?php echo $endereco3;?>";
	var endereco4 = "<?php echo $endereco4;?>";
	var endereco5 = "<?php echo $endereco5;?>";
	var endereco6 = "<?php echo $endereco6;?>";
	var endereco7 = "<?php echo $endereco7;?>";
	var endereco8 = "<?php echo $endereco8;?>";
	var endereco9 = "<?php echo $endereco9;?>";
	var endereco10 = "<?php echo $endereco10;?>";
	var endereco11 = "<?php echo $endereco11;?>";
	var endereco12 = "<?php echo $endereco12;?>";
	var endereco13 = "<?php echo $endereco13;?>";
	var endereco14 = "<?php echo $endereco14;?>";
	var endereco15 = "<?php echo $endereco15;?>";
	var endereco16 = "<?php echo $endereco16;?>";
	var endereco17 = "<?php echo $endereco17;?>";
	var endereco18 = "<?php echo $endereco18;?>";
	var endereco19 = "<?php echo $endereco19;?>";
	var endereco20 = "<?php echo $endereco20;?>";
	var endereco21 = "<?php echo $endereco21;?>";
	var endereco22 = "<?php echo $endereco22;?>";

	var enderecoRota = <?php echo json_encode($endereco) ?>;
	var enderecoPartida = "<?php echo $enderecoPartida;?>";
	var enderecoChegada = "<?php echo $enderecoChegada;?>";
	var enderecoAterro = "<?php echo $enderecoAterro;?>";

	//Começando pelo google.maps.DirectionsService, o seu funcionamento é bem simples: Basta nós passarmos um objeto google.maps.DirectionsRequest, o qual irá conter o ponto de origem e o ponto de destino, e o meio de transporte, que podemos definir entre carro, a pé, bicicleta ou transporte público, que ele irá nos retornar um objeto google.maps.DirectionsResult, o qual contém as informações da rota, e o google.maps.DirectionsStatus, que por sua vez define o estado final da nossa requisição, ou seja, ele pode indicar sucesso (DirectionsStatus.OK), sem resultados (DirectionsStatus.ZERO_RESULTS), erro (DirectionsStatus.INVALID_REQUEST ou DirectionsStatus.REQUEST_DENIED), etc.
	//Já o google.maps.DirectionsRenderer, basicamente, fica responsável por renderizar o resultado fornecido pelo google.maps.DirectionsService. 	

	var map;
	var directionsDisplay; // Instanciaremos ele mais tarde, que será o nosso google.maps.DirectionsRenderer
	var directionsService = new google.maps.DirectionsService();

	function initialize() {
		directionsDisplay = new google.maps.DirectionsRenderer(); // Instanciando...
		var latlng = new google.maps.LatLng( -18.8800397, -47.05878999999999 );
		var options = {
			zoom: 5,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP

		};
		map = new google.maps.Map( document.getElementById( "mapa" ), options );
		directionsDisplay.setMap( map ); // Relacionamos o directionsDisplay com o mapa desejado 
		directionsDisplay.setPanel( document.getElementById( "trajeto-texto" ) ); // Aqui faço a definição
	}

	initialize();

	var request = { // Novo objeto google.maps.DirectionsRequest, contendo:
		origin: enderecoPartida, // origem
		destination: enderecoChegada, // origem
		travelMode: google.maps.TravelMode.DRIVING // meio de transporte, nesse caso, de carro
	};



	var request = {
		origin: enderecoPartida,
		destination: enderecoChegada,

		//Para isso, utilizaremos o DirectionsWaypoints, que são alguns pontos pré-definidos no meio do nosso trajeto. Veja que continuaremos a ter os dois pontos que vimos até agora, mas aqui só iremos adicionar alguns pontos no meio desses dois. 

		waypoints: [
				<?php echo (!empty($endereco1) ? '{location: endereco1},' : '') ;?>
				<?php echo (!empty($endereco2) ? '{location: endereco2},' : '') ;?>
				<?php echo (!empty($endereco3) ? '{location: endereco3},' : '') ;?>
				<?php echo (!empty($endereco4) ? '{location: endereco4},' : '') ;?>
				<?php echo (!empty($endereco5) ? '{location: endereco5},' : '') ;?>
				<?php echo (!empty($endereco6) ? '{location: endereco6},' : '') ;?>
				<?php echo (!empty($endereco7) ? '{location: endereco7},' : '') ;?>
				<?php echo (!empty($endereco8) ? '{location: endereco8},' : '') ;?>
				<?php echo (!empty($endereco9) ? '{location: endereco9},' : '') ;?>
				<?php echo (!empty($endereco10) ? '{location: endereco10},' : '') ;?>
				<?php echo (!empty($endereco11) ? '{location: endereco12},' : '') ;?>
				<?php echo (!empty($endereco12) ? '{location: endereco12},' : '') ;?>
				<?php echo (!empty($endereco13) ? '{location: endereco13},' : '') ;?>
				<?php echo (!empty($endereco14) ? '{location: endereco14},' : '') ;?>
				<?php echo (!empty($endereco15) ? '{location: endereco15},' : '') ;?>
				<?php echo (!empty($endereco16) ? '{location: endereco16},' : '') ;?>
				<?php echo (!empty($endereco17) ? '{location: endereco17},' : '') ;?>
				<?php echo (!empty($endereco18) ? '{location: endereco18},' : '') ;?>
				<?php echo (!empty($endereco19) ? '{location: endereco19},' : '') ;?>
				<?php echo (!empty($endereco20) ? '{location: endereco20},' : '') ;?>
				<?php echo (!empty($endereco21) ? '{location: endereco21},' : '') ;?>
				<?php echo (!empty($endereco23) ? '{location: endereco23},' : '') ;?>
			{location: enderecoAterro}
		],

		travelMode: google.maps.TravelMode.DRIVING
	};

	directionsService.route( request, function ( result, status ) {
		if ( status == google.maps.DirectionsStatus.OK ) { // Se deu tudo certo
			directionsDisplay.setDirections( result ); // Renderizamos no mapa o resultado
		}
	} );

	directionsDisplay.setOptions( {
		polylineOptions: {
			strokeWeight: 4,
			strokeOpacity: 1,
			strokeColor: 'red'
		}
	} );
	
	
	//Começando pelo google.maps.DirectionsService, o seu funcionamento é bem simples: Basta nós passarmos 
	
	var map2;
	var directionsDisplay2; // Instanciaremos ele mais tarde, que será o nosso google.maps.DirectionsRenderer
	var directionsService2 = new google.maps.DirectionsService();

	function initialize2() {
		directionsDisplay2 = new google.maps.DirectionsRenderer(); // Instanciando...
		var latlng = new google.maps.LatLng( -18.8800397, -47.05878999999999 );
		var options = {
			zoom: 5,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP

		};
		map2 = new google.maps.Map( document.getElementById( "mapa2" ), options );
		directionsDisplay2.setMap( map2 ); // Relacionamos o directionsDisplay com o mapa desejado 
		directionsDisplay2.setPanel( document.getElementById( "trajeto-texto2" ) ); // Aqui faço a definição
	}

	initialize2();

	var request = { // Novo objeto google.maps.DirectionsRequest, contendo:
		origin: enderecoPartida, // origem
		destination: enderecoChegada, // origem
		travelMode: google.maps.TravelMode.DRIVING // meio de transporte, nesse caso, de carro
	};



	var request = {
		origin: enderecoPartida,
		destination: enderecoChegada,

		//Para isso, utilizaremos o DirectionsWaypoints, que são alguns pontos pré-definidos no meio do nosso trajeto. Veja que continuaremos a ter os dois pontos que vimos até agora, mas aqui só iremos adicionar alguns pontos no meio desses dois. 

		waypoints: [
				<?php echo (!empty($endereco23) ? '{location: endereco23},' : '') ;?>
				<?php echo (!empty($endereco24) ? '{location: endereco24},' : '') ;?>
				<?php echo (!empty($endereco25) ? '{location: endereco25},' : '') ;?>
				<?php echo (!empty($endereco26) ? '{location: endereco26},' : '') ;?>
				<?php echo (!empty($endereco23) ? '{location: endereco27},' : '') ;?> 

				{location: enderecoAterro}
		],

		travelMode: google.maps.TravelMode.DRIVING
	};

	directionsService2.route( request, function ( result, status ) {
		if ( status == google.maps.DirectionsStatus.OK ) { // Se deu tudo certo
			directionsDisplay2.setDirections( result ); // Renderizamos no mapa o resultado
		}
	} );

	directionsDisplay2.setOptions( {
		polylineOptions: {
			strokeWeight: 4,
			strokeOpacity: 1,
			strokeColor: 'red'
		}
	} );
	
	
</script>

<style>
	#mapa {
		width: 750px;
		height: 500px;
		float: left
	}
	
	#trajeto-texto {
		font-size: 12px;
		width: 300px;
		height: 500px;
		float: right;
		overflow: scroll
	}
	
	#mapa2 {
		width: 750px;
		height: 500px;
		float: left
	}
	
	#trajeto-texto2 {
		font-size: 12px;
		width: 300px;
		height: 500px;
		float: right;
		overflow: scroll
	}
</style>