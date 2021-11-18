<script>

window.onload = initPage;  
   
function initPage(){ 

  if (navigator.geolocation){
    navigator.geolocation.getCurrentPosition(showPosition,showError);
   }else{
	 x.innerHTML="Geolocalização não é suportada nesse browser.";}
  }

function showPosition(position){
	
  document.getElementById("latitude").value= position.coords.latitude;
  document.getElementById("longitude").value= position.coords.longitude;

  lat=position.coords.latitude;
  lon=position.coords.longitude;
  
  latlon=new google.maps.LatLng(lat, lon) 
  mapholder=document.getElementById('mapholder')
  mapholder.style.height='310px';
  mapholder.style.width='725px';
 
  var myOptions={
  center:latlon,zoom:15,
  mapTypeId:google.maps.MapTypeId.ROADMAP,
  mapTypeControl:false,
  navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
  };
  var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
  var marker=new google.maps.Marker({position:latlon,map:map,title:"Você está Aqui!"});
}
 
function showError(error){
  switch(error.code){
    case error.PERMISSION_DENIED:
      x.innerHTML="Usuário rejeitou a solicitação de Geolocalização."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML="Localização indisponível."
      break;
    case error.TIMEOUT:
      x.innerHTML="O tempo da requisição expirou."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML="Algum erro desconhecido aconteceu."
      break;
    }
  }


setInterval("my_function();",15000); 
function my_function(){
	document.forms.formulario.submit();
   // window.location = location.href;
}


</script>

<?php 

	if($_POST['latitude']<>''){
		$consultorId=$_SESSION['autConsultor']['id'];
		$cad['latitude'] = $_POST['latitude'];
		$cad['longitude'] = $_POST['longitude'];
		$cad['data']= date('Y/m/d H:i:s');
		update('contrato_consultor',$cad,"id = '$consultorId'");	
	}
?>
        
<div id="formulario" style="display:none">

<form action="" method="post" name="formulario" hidden="" >
  	<input name="latitude" type="hidden" id="latitude" />
    <input name="longitude" type="hidden" id="longitude" />
	<input type="submit" name="atualizar" id="atualizar" value=""  />
</form>

</div>
 
