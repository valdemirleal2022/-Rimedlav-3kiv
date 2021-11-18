<?php
$paginasurl = mysql_real_escape_string( $url[ 1 ] );
$readpaginas = read( 'paginas', "WHERE url = '$paginasurl'" );
if ( !$readpaginas ) {
	header( 'Location: ' . URL . '/404' );
} else {
	foreach ( $readpaginas as $pag );
}

echo '<meta name="description" content="' . $pag[ 'nome' ] . ' - ' . $pag[ 'tags' ] . '" />';
?>


<div class="content container">

	<div class="page-wrapper">
		<header class="page-heading clearfix">
			<h1 class="heading-title pull-left">
				<?php echo $pag['nome'];?>
			</h1>
			<div class="breadcrumbs pull-right">
				<ul class="breadcrumbs-list">
					<li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i>
					</li>
					<li class="current">
						<?php echo $pag['nome'];?>
					</li>
				</ul>
			</div>
			<!--//breadcrumbs-->
		</header>

		<div class="page-content">

			<div class="row page-row">

				<div class="course-wrapper col-md-8 col-sm-7">

					<article class="course-item">

						<div class="page-row">
							<p>
								<?php echo stripslashes($pag['conteudo']);?>
							</p>
						</div>
						<!--page-row-->

						<div class="row page-row">

							<div id="fb-root"></div>
							<script>
								( function ( d, s, id ) {
										var js, fjs = d.getElementsByTagName( s )[ 0 ];
										if ( d.getElementById( id ) ) return;
										js = d.createElement( s );
										js.id = id;
										js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1";
										fjs.parentNode.insertBefore( js, fjs );
									}
									( document, 'script', 'facebook-jssdk' ) );
							</script>

							<div class="fb-like" data-href="<?php echo $_SERVER['REQUEST_URI']?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true">
							</div>
						</div>
						<!--row page-row-->

						<div class="row page-row">
							<div class="alert alert-info">
								<strong>Tags : </strong>
								<?php echo $pag['tags'];?>
							</div>
						</div>

					</article>

				</div>
				<!--//course-wrapper col-md-8 col-sm-7-->
				<?php require("site/inc/sidebar-tab.php");?>
			</div>
			<!--//page-row-->
		</div>
		<!--//page-content-->
	</div>
	<!--//page-->
</div>
<!--//content-->
</div>
<!--//wrapper-->