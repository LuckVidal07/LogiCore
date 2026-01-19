<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\JsonProdutoRepository;
use App\Service\LogService;
use App\Service\EstoqueService;

// Configuração de cores
$verde = "\033[32m";
$vermelho = "\033[31m";
$azul = "\033[34m";
$amarelo = "\033[33m";
$cyan = "\033[36m";
$reset = "\033[0m";

$repo = new JsonProdutoRepository();
$log = new LogService();
$sistema = new EstoqueService($repo, $log);

// Limpa a tela do terminal para começar bonito
echo "\033[2J\033[H";

echo "{$azul}==================================={$reset}\n";
echo "{$azul}     LOGICORE - GESTÃO ATIVA       {$reset}\n";
echo "{$azul}==================================={$reset}\n";

while (true) {
    echo "\n{$cyan}Menu Principal:{$reset}\n";
    echo "{$amarelo}1.{$reset} Listar Todos os Produtos\n";
    echo "{$amarelo}2.{$reset} Adicionar/Repor Produto\n";
    echo "{$amarelo}3.{$reset} Dar Baixa em Estoque\n";
    echo "{$amarelo}4.{$reset} Conferir Alertas Críticos\n";
    echo "{$amarelo}5.{$reset} Remover Produto\n";
    echo "{$amarelo}5.{$reset} Sair\n";
    echo "\nDigite uma opção {$amarelo}> {$reset}";

    $opcao = trim(fgets(STDIN));

    switch ($opcao) {
        case '1':
            echo "\n{$azul}--- RELATÓRIO DE ESTOQUE ---{$reset}";
            $sistema->listarTudo();
            break;

        case '2':
            echo "\n{$azul}--- ENTRADA DE MERCADORIA ---{$reset}\n";
            echo "Nome do produto: ";
            $nome = trim(fgets(STDIN));
            echo "Quantidade: ";
            $qtd = (int) trim(fgets(STDIN));
            echo "Preço (ex: 10.50): ";
            $preco = (float) trim(fgets(STDIN));

            $sistema->adicionarProduto($nome, $qtd, $preco);
            break;

        case '3':
            echo "\n{$azul}--- SAÍDA DE MERCADORIA ---{$reset}\n";
            echo "Nome exato do produto: ";
            $nome = trim(fgets(STDIN));
            echo "Quantidade a retirar: ";
            $qtd = (int) trim(fgets(STDIN));

            if ($sistema->darBaixa($nome, $qtd)) {
                echo "{$verde}✅ Baixa processada!{$reset}\n";
            } else {
                echo "{$vermelho}❌ Falha na operação.{$reset}\n";
            }
            break;

        case '4':
            echo "\n{$vermelho}--- PRODUTOS ABAIXO DO LIMITE ---{$reset}\n";
            $sistema->conferirEstoqueCritico();
            break;

        case '5':
            echo "\n{$vermelho}--- REMOVER PRODUTO ---{$reset}\n";
            echo "Digite o nome do produto que deseja EXCLUIR: ";
            $nome = trim(fgets(STDIN));

            echo "Tem certeza que deseja remover '$nome'? (s/n): ";
            $confirmacao = strtolower(trim(fgets(STDIN)));

            if ($confirmacao === 's') {
                $sistema->removerProduto($nome);
            } else {
                echo "Operação cancelada.\n";
            }
            break;

        case '6':
            echo "\n{$azul}Encerrando sistema...{$reset}\n";
            exit;

        default:
            echo "{$vermelho}Opção inválida!{$reset}\n";
            break;
    }
}