<?php 
	ob_start();
	session_start();
		unset($_SESSION['autCliente']);
		header('Location: index.php');
	ob_end_flush();
?>