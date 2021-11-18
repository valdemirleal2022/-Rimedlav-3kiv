<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'],'1')){
				header('Location: painel.php');		
			}
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['videoEditar'])){
			$videoId = $_GET['videoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['videoDeletar'])){
			$videoId = $_GET['videoDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['videoEnviar'])){
			$videoId = $_GET['videoEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['videoNovo'])){
			$videoId = $_GET['videoEnviar'];
			$acao = "enviar";
		}
		if(!empty($videoId)){
			$readvideo = read('video',"WHERE id = '$videoId'");
			if(!$readvideo){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readvideo as $edit);
		}
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Eventos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/Eventos">Eventos</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>

  <section class="content">

      <div class="box box-default">
      
            <div class="box-header with-border">
               <h1><?php echo $edit['descricao']  ;?></h1>	
                <?php
     			echo '<iframe class="video-iframe" src="//www.youtube.com/embed/' . $edit['nome'] .'?rel=0&amp;wmode=transparent" frameborder="0" allowfullscreen=""></iframe>';
			?>
            </div><!-- /.box-header -->
            
      <div class="box-body">

	<?php 
		if(isset($_POST['cadastrar'])){
			$cad['nome'] = mysql_real_escape_string($_POST['nome']);
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['data']= date('Y/m/d H:i:s');
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			 }else{
				create('video',$cad);
				unset($cad);
				header('Location: painel.php?execute=site/videos');
			}
		}
	
		if(isset($_POST['atualizar'])){
			$cad['nome'] = mysql_real_escape_string($_POST['nome']);
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['data']= date('Y/m/d H:i:s');
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				update('video', $cad, "id = '$videoId'");
				unset($cad);
				header('Location: painel.php?execute=site/videos');
			  }
		}
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('video',"WHERE id = '$videoId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('videos',"id = '$videoId'");
					header('Location: painel.php?execute=site/video');
				}
		}
		
		
	?>
    
	
     <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
      			
			 <div class="form-group">
                 <label>Endereço URL</label>
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>"/>
            </div>
            
             <div class="form-group">
                 <label>Descrição</label>
                  <input name="descricao"  class="form-control" type="text" value="<?php echo $edit['descricao'];?>"/>
            </div>

           <div class="form-group">
                 <label>Data:</label>
                  <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" name="data" class="form-control pull-right" value="<?php echo date('d/m/Y H:i:s');?>" disabled>
                        </div><!-- /.input group -->
                 </div>   
              </div>

			
          <div class="box-footer">
            <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-danger"> </a>
            <?php 
                if($acao=="atualizar"){
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                }
                if($acao=="deletar"){
                    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-primary" />';	
                }
                if($acao=="cadastrar"){
                    echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                }
                if($acao=="enviar"){
                    echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
                }
             ?>  
        </div>
  
   </form>
   
   
		</div><!-- /.box-body -->

            
        <div class="box-footer">
            
        </div><!-- /.box-footer-->
            
    </div><!-- /.box box-default -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->



 
 


