<?php 
	ob_start();
	session_start();
		unset($_SESSION['autpos_venda']);
		header('Location: index.php');
	ob_end_flush();
?>