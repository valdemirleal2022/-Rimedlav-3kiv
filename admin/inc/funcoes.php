<!--24/09/2018 Padrão -->

<link rel="stylesheet" href="<?php setaurl();?>/admin/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

<link rel="stylesheet" href="<?php setaurl();?>/admin/dist/css/AdminLTE.css">
<link rel="stylesheet" href="<?php setaurl();?>/admin/dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<?php setaurl();?>/admin/bootstrap/css/estilo.css">
<link rel="stylesheet" href="<?php setaurl();?>/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
 
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<link rel="icon" type="image/png" href="<?php setaurl();?>/admin/ico/favico.png">
 
   <!-- jQuery 2.1.4 -->
    <script src="<?php setaurl();?>/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php setaurl();?>/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php setaurl();?>/admin/plugins/fastclick/fastclick.min.js"></script>
    <script src="<?php setaurl();?>/admin/dist/js/app.min.js"></script>
    <script src="<?php setaurl();?>/admin/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php setaurl();?>/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php setaurl();?>/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="<?php setaurl();?>/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="<?php setaurl();?>/admin/plugins/iCheck/icheck.min.js"></script>
    
    <script src="<?php setaurl();?>/admin/dist/js/demo.js"></script>
    <script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
    <script src="<?php setaurl();?>/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>


    
    <script src="<?php setaurl();?>/admin/plugins/fastclick/fastclick.min.js"></script>
    <script type="text/javascript" src="/Scripts/MyScripts/Uom.js"></script>
    
    <!-- MAPAS --> 
    <link rel="stylesheet" type="text/css" href="<?php setaurl();?>/admin/css/mapas.css">
    
 <!--   <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4b2zbrrO2vKHiMYGQDKip9o_zS05dNUU&callback=initMap"
    async defer></script>

    <script type="text/javascript" src="<?php setaurl();?>/admin/js/gmaps.js"></script>
    <script type="text/javascript" src="<?php setaurl();?>/admin/js/markers.js"></script>
    
    <link rel="stylesheet" href="<?php setaurl();?>/admin/css/toast.css">
    <script type="text/javascript" src="<?php setaurl();?>/admin/js/toaster.js"></script>
-->	
      
 <script>
	
	$(document).ready(function() {
		
            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                $("#ibge").val("");
            }
		
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {
                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');
                //Verifica se campo cep possui valor informado.
                if (cep != "") {
                    //Expressao regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;
                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endereco").val("procurando... Aguarde ")
                        $("#bairro").val("")
                        $("#cidade").val("")
                        $("#uf").val("")
                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
								$("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado nao foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP nao encontrado.");
                            }
                        });
                    } //end if.
					
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
	
	
        });
	  
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor-texto');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
	  
	  
	   //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

		
 </script>


  <script type="text/javascript">
    $("#cnpj").mask("00.000.000/0000-00");
	$("#cpf").mask("000.000.000-00");
	$("#cep").mask("00000-000");
	$("#celular").mask("00000-0000");
	$("#telefone").mask("0000-0000");
</script>
 


 