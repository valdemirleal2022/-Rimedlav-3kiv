<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Upload planilha CSV</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Upload</a>
     	<li class="active">Ler Arquivo</li>
     </ol>
 </section>

 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
            <div class="box-header">
                <h3 class="box-title">Upload planilha CSV</h3>
                 <?php echo $_SESSION['retorna'];
					unset($_SESSION['retorna']);
				?> 
      	  	</div><!-- /.box-header -->
          
          
             <div class="box-body">
               <form action="#" method="POST" enctype="multipart/form-data">
                  <div class="form-group col-xs-12 col-md-8"> 
                     <input type="file" name="fileUpload" class="form-control">
                  </div>
                   <div class="form-group col-xs-12 col-md-8"> 
                    <input type="submit" name="atualizar" value="Upload Planilha CSV" title="Nome Endereço Numero Complemento Bairro Cidade Cep" class="btn btn-primary" />
                   </div> 
               </form>
            </div><!-- /.box-body -->
           
    
    <div class="box-body table-responsive">
      	<div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
					
								 
			 <?php
			 
                $diretorio= 'retorno';
                if(is_dir("$diretorio")) {
                //	echo 'O diretorio já existe !';
                 }else{
                    mkdir ("$diretorio", 0777); // criar o diretorio com permissao
                }
				
                if(isset($_FILES['fileUpload'])){
                  $new_name =$_FILES['fileUpload']['name']; //Pegando extensao do arquivo
                  $dir = 'retorno/'; //Diretório para uploads
                  move_uploaded_file($_FILES['fileUpload']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
                  $ponteiro = fopen ($dir.$new_name,"r");
				}
				  
				if(empty($ponteiro)){
				   return;
				}

				// obter os dados em cada linha
				while (($data = fgetcsv($ponteiro, 1000, ";")) !== FALSE) {
					
					$nome=$data[0];
					$endereco=$data[1];
					$numero=$data[2];
					$complemento=$data[3];
					$bairro=$data[4];
					$cidade=$data[5];
					$cep=$data[6];
					
					echo $nome."|";
					echo $endereco."|";
					echo $numero."|";
					echo $complemento."|";
					echo $bairro."|";
					echo $cidade."|";
					echo $cep."<br>";
					
					$cad['nome'] = $nome;
					$cad['endereco'] = $endereco;
					$cad['numero'] = $numero;
					$cad['complemento'] = $complemento;
					$cad['bairro'] = $bairro;
					$cad['cidade'] = $cidade;
					$cad['cep'] = $cep;
					$cad['orc_solicitacao']= date('Y/m/d H:i:s');
					$cad['data']= date('Y/m/d');
					$cad['status']= '0';
					$cad['indicacao']= '17';
					$cad['empresa_atual']= '59';
		
					$cad['interacao']= date('Y/m/d H:i:s');
					create('cadastro_prospeccao',$cad);	
					
				}
               fclose ($ponteiro);
			   
            ?>
            
      	 </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->

    </div><!-- /."box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->