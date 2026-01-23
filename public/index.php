<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\JsonProdutoRepository;
use App\Service\LogService;
use App\Service\EstoqueService;

// 1. Inicialização do Motor
$repo = new JsonProdutoRepository();
$log = new LogService();
$sistema = new EstoqueService($repo, $log);

// 2. Roteamento: O navegador diz o que quer via URL (ex: ?acao=listar)
$acao = $_GET['acao'] ?? 'listar';

// Se for uma ação de salvar ou baixar, processamos antes de mostrar o HTML
if ($acao === 'salvar') {
    $sistema->adicionarProduto($_POST['nome'], (int)$_POST['qtd'], (float)$_POST['preco']);
    header('Location: index.php?status=sucesso');
    exit;
}

if ($acao === 'baixa') {
    $sucesso = $sistema->darBaixa($_POST['nome'], (int)$_POST['qtd']);
    header('Location: index.php?status=' . ($sucesso ? 'sucesso' : 'erro'));
    exit;
}

if ($acao === 'remover') {
    $sistema->removerProduto($_GET['nome']);
    header('Location: index.php?status=sucesso');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Logicore Web - Gestão de Estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">LOGICORE WEB</a>
        <div class="d-flex">
            <a href="index.php?acao=listar" class="btn btn-outline-light btn-sm me-2">Estoque</a>
            <a href="index.php?acao=form_adicionar" class="btn btn-primary btn-sm">+ Novo</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php
    switch ($acao) {
        case 'listar':
            $produtos = $sistema->listarTudo();
            include __DIR__ . '/../src/UI/Web/listar_produtos.php';
            break;

        case 'form_adicionar':
            include __DIR__ . '/../src/UI/Web/form_produtos.php';
            break;
            
        default:
            echo "Ação não encontrada.";
            break;
    }
    ?>
</div>

</body>
</html>