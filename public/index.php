<?php
require_once '../repositories/JogoRepository.php';

$repo = new JogoRepository();
$jogos = $repo->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Sistema de Jogos</title>
	<style>
    	body { font-family: Arial; margin: 40px; }
    	nav a { margin-right: 15px; text-decoration: none; font-weight: bold; }
    	table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    	th, td { border: 1px solid #ccc; padding: 8px; }
    	th { background: #eee; }
	</style>
</head>
<body>

<h1>Sistema de Cadastro de Jogos</h1>

<nav>
	<a href="index.php">📋 Listar Jogos</a>
	<a href="criar.php">➕ Novo Jogo</a>
</nav>

<table>
	<tr>
    	<th>ID</th>
    	<th>Título</th>
    	<th>Gênero</th>
    	<th>Plataforma</th>
    	<th>Ano</th>
    	<th>Ações</th>
	</tr>

	<?php foreach ($jogos as $j): ?>
    	<tr>
        	<td><?= $j['id'] ?></td>
        	<td><?= htmlspecialchars($j['titulo']) ?></td>
        	<td><?= htmlspecialchars($j['genero']) ?></td>
        	<td><?= htmlspecialchars($j['plataforma']) ?></td>
        	<td><?= $j['ano'] ?></td>
        	<td>
            	<a href="editar.php?id=<?= $j['id'] ?>">✏️ Editar</a> |
            	<a href="excluir.php?id=<?= $j['id'] ?>"
                	onclick="return confirm('Deseja excluir?')">🗑️ Excluir</a>
        	</td>
    	</tr>
	<?php endforeach; ?>
</table>

</body>
</html>
