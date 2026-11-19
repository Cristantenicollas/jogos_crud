<?php
require_once '../repositories/JogoRepository.php';

$erros = [];
$dados = $_POST;

if ($_POST) {
    $erros = Jogo::validar($_POST);

    if (!$erros) {
        try {
            $jogo = new Jogo(
                trim($_POST['titulo']),
                trim($_POST['genero']),
                trim($_POST['plataforma']),
                (int) $_POST['ano']
            );

            (new JogoRepository())->criar($jogo);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível salvar o jogo. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Novo Jogo</title>
	<style>
		body { font-family: Arial; margin: 40px; }
		.erros { color: #c00; }
		input { padding: 5px; margin-bottom: 8px; }
		button { padding: 6px 15px; }
	</style>
</head>
<body>

<h1>Novo Jogo</h1>

<?php if ($erros): ?>
	<ul class="erros">
		<?php foreach ($erros as $erro): ?>
			<li><?= htmlspecialchars($erro) ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<form method="post">
	<input name="titulo" placeholder="Título" value="<?= htmlspecialchars($dados['titulo'] ?? '') ?>" required><br>
	<input name="genero" placeholder="Gênero" value="<?= htmlspecialchars($dados['genero'] ?? '') ?>" required><br>
	<input name="plataforma" placeholder="Plataforma (PC, PS5, Xbox...)" value="<?= htmlspecialchars($dados['plataforma'] ?? '') ?>" required><br>
	<input name="ano" type="number" placeholder="Ano de lançamento" value="<?= htmlspecialchars($dados['ano'] ?? '') ?>" required><br>
	<button>Salvar</button>
</form>

</body>
</html>
