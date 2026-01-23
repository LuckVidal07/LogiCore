<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Infrastructure\JsonProdutoRepository;
use App\Service\LogService;
use App\Service\EstoqueService;
use App\UI\Console\EstoqueShell;

$repo = new JsonProdutoRepository();
$log = new LogService();

$sistema = new EstoqueService($repo, $log);

$shell = new EstoqueShell($sistema);
$shell->executar();