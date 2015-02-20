<?php
	//Conexão ao banco de dados
	$conexao = mysql_connect("127.0.0.1","root","") or die("Erro ao Conectar o Banco de Dados");
	mysql_select_db("crudphpunicoarquivo") or die("Erro ao Selecionar o Banco de Dados");
?>