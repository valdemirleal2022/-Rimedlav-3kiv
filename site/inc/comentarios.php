<?php 
	if(isset($_POST['cadastrar'])){
		$cad['mensagem'] = mysql_real_escape_string($_POST['mensagem']);
		$cad['nome'] = htmlspecialchars(mysql_real_escape_string($_POST['nome']));
		$cad['empresa'] = htmlspecialchars(mysql_real_escape_string($_POST['empresa']));
		$cad['status'] = '1';	
		if (isset($_POST['g-recaptcha-response'])) {
  			$captcha_data = $_POST['g-recaptcha-response'];
		}
		if ($captcha_data) {
			echo "Por favor, confirme o captcha.";
			$cad['data']= date('Y/m/d H:i:s');
			if(!empty($_FILES['foto']['tmp_name'])){
					$imagem = $_FILES['foto'];
					$pasta = 'uploads/comentarios/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['foto'] = $nome;
					uploadImg ($tmp, $nome, '100', $pasta);
			}
				
		//    create('comentarios',$cad);
//			$assunto  = "Comentário publicado no site";
//			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
//			$msg .= "Nome : " . $cad['nome'] . "<br />";
//			$msg .= "Mensagem : " . $cad['mensagem'] . "<br />";
//			$msg .= "Data : " . date('d/m/Y H:i') . "<br />";
//			$msg .=  "</font>";
//			enviaEmail($assunto,$msg,MAILUSER,$con['nome'],MAILUSER,SITENOME);
//			unset($cad);
			header('Location: '.URL.setaurl());
	  	}
	}
	
?><head>
    <meta charset="iso-8859-1">
</head>
    








<div class="col-md-6">
    <section class="testimonials">
            <h1 class="section-heading text-highlight"><span class="line">Coment&aacute;rios</span></h1>
             <div class="carousel-controls">
             	<a class="prev" href="#testimonials-carousel" data-slide="prev"><i class="fa fa-caret-left"></i></a>
              	<a class="next" href="#testimonials-carousel" data-slide="next"><i class="fa fa-caret-right"></i></a>
            </div><!--//carousel-controls-->
                        
          <div class="section-content">
          
             <div id="testimonials-carousel" class="testimonials-carousel carousel slide">
                <div class="carousel-inner">

					<?php
                     $readComentario = read('comentarios',"WHERE id AND status = '1' ORDER BY data DESC");
                     if($readComentario){
						 $active=0;
						 $num=0;
                         foreach($readComentario as $comentario):
						    $num=$num+1;
							if($active==0){
								echo '<div class="item active">';
								$active=1;
							}
							if($active==2){
								echo '<div class="item">';
								$active=3;
							}
                            echo '<blockquote class="quote"> ';
                            echo '<p><i class="fa fa-quote-left"></i>'
                                            .resumos($comentario['mensagem'],$palavras = '60')
                                            .'</p>';
                            echo '</blockquote>  ';
                            echo '<div class="row">';
                            echo '<p class="people col-md-8 col-sm-3 col-xs-8"><span class="name">'
                                  .resumos($comentario['nome'],$palavras = '30')
                                  .'</span><br /><span class="title">'.resumos($comentario['empresa'],$palavras = '30')
                                  .'</span><br /></p>';
                             if(!empty($comentario['foto'])){
                               echo '<img class="profile col-md-3 pull-right img-circle" src="'.URL.'/uploads/comentarios/'
                                     .$comentario['foto'].'">';
                                }else{
									 
                                 echo '<img class="class="profile col-md-3 pull-right img-circle" src="'.URL.'/site/images/autor-logo.png">';
								 
                              }
                              echo ' </div> ';
							  
							  if($num==1){
						        echo '</div>';
							    $active=2;
								$num=0;
							   }
                            endforeach;
                           }
                         ?>
  
                    </div><!--//carousel-inner-->
               </div><!--//testimonials-carousel-->
           </div><!--//section-content-->
           
    </section><!--//testimonials-->
 </div><!--//col-md-6-->

 <div class="col-md-6">
             <div class="section-content">
        <form name="formulario" action="" class="course-finder-form" method="post" enctype="multipart/form-data">
      			<div class="form-group message">
                <label for="message">Mensagem<span class="required">*</span></label>
                <textarea  class="form-control" rows="3" name="mensagem" placeholder="Seu comentário"></textarea>
                </div><!--//form-group-->
                <div class="form-group name">
                       <label for="name">Nome<span class="required">*</span></label>
                        <input  type="text" class="form-control" name="nome" placeholder="Seu nome">
                </div><!--//form-group-->
                
                 <div class="form-group name">
                       <label for="name">Empresa<span class="required">*</span></label>
                        <input  type="text" class="form-control" name="empresa" placeholder="Sua Empresa">
                </div><!--//form-group-->
                
                <div class="form-group name">
                       <label for="name">Foto</label>
                       <input type="file" name="foto" size="10" class="form-control" />   
                </div><!--//form-group-->
                
               <div class="g-recaptcha" data-sitekey="6LcJcRgTAAAAAGRivgEp3CUDtjz3w-SNo09iGau4"></div>

				 <input type="submit" name="cadastrar" value="Publicar" class="btn btn-theme">
                 
       </form> 
       </div><!--//section-content-->
 </div><!--//col-md-6-->
  
  
          