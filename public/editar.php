<?php
require_once '../repositories/JogoRepository.php';

$repo = new JogoRepository();
$erros = [];

if (!isset($_GET['id']) || !Jogo::validarId($_GET['id'])) {
    echo 'ID inválido. <a href="index.php">Voltar</a>';
    exit;
}

if ($_POST) {
    $erros = Jogo::validar($_POST);

    if (!Jogo::validarId($_POST['id'] ?? null)) {
        $erros[] = 'ID inválido.';
    }

    if (!$erros) {
        try {
            $jogo = new Jogo(
                trim($_POST['titulo']),
                trim($_POST['genero']),
                trim($_POST['plataforma']),
                (int) $_POST['ano']
            );

            $repo->atualizar($_POST['id'], $jogo);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível atualizar o jogo. Tente novamente.';
        }
    }
}

$jogo = $repo->buscarPorID($_GET['id']);

if (!$jogo) {
    echo 'Jogo não encontrado. <a href="index.php">Voltar</a>';
    exit;
}

$dados = !empty($erros) ? $_POST : $jogo;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Editar Jogo</title>
	<style>
		body { font-family: Arial; margin: 40px; }
		.erros { color: #c00; }
		input { padding: 5px; margin-bottom: 8px; }
		button { padding: 6px 15px; }
	</style>
</head>
<body>

<h1>Editar Jogo</h1>

<?php if ($erros): ?>
	<ul class="erros">
		<?php foreach ($erros as $erro): ?>
			<li><?= htmlspecialchars($erro) ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<form method="post">
	<input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">
	<input name="titulo" value="<?= htmlspecialchars($dados['titulo']) ?>" required><br>
	<input name="genero" value="<?= htmlspecialchars($dados['genero']) ?>" required><br>
	<input name="plataforma" value="<?= htmlspecialchars($dados['plataforma']) ?>" required><br>
	<input name="ano" type="number" value="<?= htmlspecialchars($dados['ano']) ?>" required><br>
	<button>Atualizar</button>
</form>

</body>
</html>
