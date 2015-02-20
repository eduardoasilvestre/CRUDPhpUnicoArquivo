<?php
	include('conexao.php');
	if(isset($_POST['salvar'])) {
		$opcao = "cadastrar";
	} else if(isset($_GET['opcao'])) {
		$opcao = $_GET['opcao'];
	} else {
		$opcao = "";
	}
	$id    =  (int) isset($_GET['id']) ? $_GET['id'] : "";
	$nome  =  isset($_POST['nome']) ? trim($_POST['nome']) : "";
	$login =  isset($_POST['login']) ? trim($_POST['login']) : "";
	$senha =  isset($_POST['senha']) ? trim($_POST['senha']) : "";
	$sexo  =  isset($_POST['sexo']) ? trim($_POST['sexo']) : "";
	$ec    =  isset($_POST['ec']) ? trim($_POST['ec']) : "";
	$comen =  isset($_POST['comentarios']) ? trim($_POST['comentarios']) : "";
	switch($opcao){
		case('cadastrar'):
			if(isset($_POST['salvar'])) {
				if(empty($nome) or empty($login) or empty($senha) or empty($sexo) or empty($ec) or empty($comen)) {
					$msg = 'Preencha todos os campos!';
				} else {
					$sql = mysql_query('INSERT INTO usuario (nome,login,senha,sexo,estado_civil,comentarios)
										VALUES ("'. $nome .'", "'. $login .'" , "'. $senha .'" , "'. $sexo .'" , "'. $ec .'" , "'. $comen .'" ) ');
					if($sql){
						$msg = '<span class="ok">Cadastro com sucesso!</span>';
					} else {
						$msg = 'Houve um erro ao cadastrar!';
					}
					$nome = ""; $login = ""; $senha = "";$sexo = ""; $ec = ""; $comen = ""	; //unset($nome,$login, $senha,$sexo,$ec,$comen);
				}
			}
		break;
		case('editar'):
			if(isset($_POST['salvar'])) {
				if(empty($nome) or empty($login) or empty($senha) or empty($sexo) or empty($ec) or empty($comen)){
					$msg = 'Preencha todos os campos!';
				}  else {
					$sql = mysql_query('UPDATE usuario SET
										nome  = "'. $nome .'" ,
										login = "'. $login .'" ,
										senha = "'. $senha .'" ,
										sexo  = "'. $sexo .'" ,
										estado_civil = "'. $ec .'" ,
										comentarios  = "'. $comen .'"
										WHERE id = "'. $id .'"	');
					if($sql) {
						$msg = '<span class="ok">Salvo com sucesso!</span>';
					}else{
						$msg = 'Houve um erro ao atualizar!';
					}
				}
			} else {
				$sql     = mysql_query('SELECT * FROM usuario WHERE id = "'. $id .'"');
				$usuario = mysql_fetch_array($sql);
				$nome  =  trim($usuario['nome']);
				$login =  trim($usuario['login']);
				$senha =  trim($usuario['senha']);
				$sexo  =  trim($usuario['sexo']);
				$ec    =  trim($usuario['estado_civil']);
				$comen =  trim($usuario['comentarios']);
			}
		break;
		case('excluir'):
			//Exclui no banco
			$sql = mysql_query('DELETE FROM usuario WHERE id = "'. $id .'" ');
		break;
	}
	if(empty($msg)) $msg = 'Preencha os campos abaixo:';
	$estadoCivis = array(1 => 'Solteiro', 2 => 'Casado' , 3 => 'Divorciado' , 4 => 'Viúvo');
	$sexos = array( 'M' => 'Masculino' , 'F' => 'Feminino' , 'O' => 'Outro');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head> <link rel="stylesheet" type="text/css" href="../css/estilo.css" /> </head>
    <body>
	    <form id="oqueisso" method="post" action="">
	    <p><?php echo $msg; ?></p>
	    
	    <label for="nome">Nome:</label>
	    <input id="nome" name="nome" type="text" value="<?php echo $nome; ?>"/>
		<br />	<br />
	
	    <label  for="login">Login:</label>
	    <input id="login" name="login" type="text" value="<?php echo $login;?>"/>
		<br />	<br />
	    
		<label  for="senha">Senha:</label>
	    <input id="senha" name="senha" type="password" />
		<br />	<br />
		
		<label  for="sexo">Sexo:</label>
		<input type='radio' name='sexo' value='M' <?php if($sexo == 'M') echo 'checked="checked"';?> >Masculino
		<input type='radio' name='sexo' value='F' <?php if($sexo == 'F') echo 'checked="checked"';?>>Feminino
		<input type='radio' name='sexo' value='O' <?php if($sexo == 'O') echo 'checked="checked"';?>>Outro
		<br /> <br />
		
		<label for="ec">Estado Civil:</label>
		<select name="ec">
		  <option value="1" <?php if($ec == 1) echo 'selected="selected"';?> >Solteiro</option>
		  <option value="2" <?php if($ec == 2) echo 'selected="selected"';?>>Casado</option>
		  <option value="3" <?php if($ec == 3) echo 'selected="selected"';?>>Divorciado</option>
		  <option value="4" <?php if($ec == 4) echo 'selected="selected"';?>>Viúvo</option>
		</select>
		<br /> <br />
		
	    <label  for="comentarios">Comentários:</label>
	    <textarea name="comentarios" id="comentarios"><?php echo $comen; ?></textarea>
		<br /> <br />
		
	    <input id="salvar"  type="submit" name="salvar" value="SALVAR" />
	    </form>
    <h3>Usuários Cadastrados:</h3>
    <?php
		$sql     = mysql_query('SELECT * FROM usuario ORDER BY id DESC');
	?>
    <table width="736" border="0" cellpadding="1" cellspacing="2" id="listar">
      <thead>
      	<tr>
            <th>Nome:</th><th>Login:</th><th>Sexo:</th><th>Estado Civil:</th><th>Opções:</th>
        </tr>
      </thead>
      <tbody>
      	<?php 	if (mysql_num_rows($sql) == 0) { ?>
        <tr>
            <td colspan="5"> Não há registros cadastrados. </td>
        </tr>
        <?php 
			} else { 
				while( $usuario = mysql_fetch_array($sql) ) {
		?>
        <tr>
            <td><?php echo $usuario['nome']; ?></td>
            <td><?php echo $usuario['login']; ?></td>
            <td><?php echo $sexos[$usuario['sexo']]; ?></td>
            <td><?php echo $estadoCivis[$usuario['estado_civil']]; ?></td>
            <td>
            	<a href="index.php?opcao=editar&id=<?php echo $usuario['id']?>">editar</a>
                <a onclick="if(!confirm('Deseja realmente excluir?')) return false;" 
                href="index.php?opcao=excluir&id=<?php echo $usuario['id']?>">excluir</a>
            </td>
        </tr>
        <?php 	
			}
		} 
		?>
      </tbody>
    </table>
</body>
</html>