<?php
namespace App\UI\Console;

use App\Service\EstoqueService;

class EstoqueShell
{
    private EstoqueService $sistema;

    // Definição de Cores
    private string $verde = "\033[32m";
    private string $vermelho = "\033[31m";
    private string $azul = "\033[34m";
    private string $amarelo = "\033[33m";
    private string $cyan = "\033[36m";
    private string $reset = "\033[0m";

    public function __construct(EstoqueService $sistema)
    {
        $this->sistema = $sistema;
    }

    public function executar(): void
    {
        echo "\033[2J\033[H"; // Limpa a tela
        echo "{$this->azul}==================================={$this->reset}\n";
        echo "{$this->azul}     LOGICORE - GESTÃO ATIVA       {$this->reset}\n";
        echo "{$this->azul}==================================={$this->reset}\n";

        while (true) {
            $this->exibirMenu();
            $opcao = trim(fgets(STDIN));

            if ($opcao === '6') {
                echo "\n{$this->azul}Encerrando sistema...{$this->reset}\n";
                break;
            }

            $this->processarOpcao($opcao);
        }
    }

    private function exibirMenu(): void
    {
        echo "\n{$this->cyan}Menu Principal:{$this->reset}\n";
        echo "{$this->amarelo}1.{$this->reset} Listar Todos os Produtos\n";
        echo "{$this->amarelo}2.{$this->reset} Adicionar/Repor Produto\n";
        echo "{$this->amarelo}3.{$this->reset} Dar Baixa em Estoque\n";
        echo "{$this->amarelo}4.{$this->reset} Conferir Alertas Críticos\n";
        echo "{$this->amarelo}5.{$this->reset} Remover Produto\n";
        echo "{$this->amarelo}6.{$this->reset} Sair\n";
        echo "\nDigite uma opção {$this->amarelo}> {$this->reset}";
    }

    private function processarOpcao(string $opcao): void
    {
        switch ($opcao) {
            case '1': $this->listarTudo(); break;
            case '2': $this->adicionarProduto(); break;
            case '3': $this->darBaixa(); break;
            case '4': $this->conferirEstoqueCritico(); break;
            case '5': $this->removerProduto(); break;
            default: echo "{$this->vermelho}Opção inválida!{$this->reset}\n"; break;
        }
    }

    // --- MÉTODOS DE INTERAÇÃO

    private function listarTudo(): void
    {
        echo "\n{$this->azul}--- RELATÓRIO DE ESTOQUE ---{$this->reset}\n";
        $produtos = $this->sistema->listarTudo();
        
        if (empty($produtos)) {
            echo "Estoque vazio.\n";
            return;
        }

        printf("%-20s | %-10s | %-10s\n", "PRODUTO", "QTD", "PREÇO");
        echo str_repeat("-", 45) . "\n";
        foreach ($produtos as $p) {
            $corQtd = ($p->quantidade <= 5) ? $this->vermelho : $this->verde;
            echo sprintf("%-20s | %s%-10d%s | R$ %-10.2f\n", $p->nome, $corQtd, $p->quantidade, $this->reset, $p->preco);
        }
    }

    private function adicionarProduto(): void
    {
        echo "\n{$this->azul}--- ENTRADA DE MERCADORIA ---{$this->reset}\n";
        echo "Nome do produto: ";
        $nome = trim(fgets(STDIN));
        echo "Quantidade: ";
        $qtd = (int) trim(fgets(STDIN));
        echo "Preço (ex: 10.50): ";
        $preco = (float) trim(fgets(STDIN));

        $this->sistema->adicionarProduto($nome, $qtd, $preco);
        echo "{$this->verde}✅ Operação realizada com sucesso!{$this->reset}\n";
    }

    private function darBaixa(): void
    {
        echo "\n{$this->azul}--- SAÍDA DE MERCADORIA ---{$this->reset}\n";
        echo "Nome exato do produto: ";
        $nome = trim(fgets(STDIN));
        echo "Quantidade a retirar: ";
        $qtd = (int) trim(fgets(STDIN));

        if ($this->sistema->darBaixa($nome, $qtd)) {
            echo "{$this->verde}✅ Baixa processada!{$this->reset}\n";
        } else {
            echo "{$this->vermelho}❌ Falha na operação (Produto não encontrado ou estoque insuficiente).{$this->reset}\n";
        }
    }

    private function conferirEstoqueCritico(): void
    {
        echo "\n{$this->vermelho}--- PRODUTOS ABAIXO DO LIMITE ---{$this->reset}\n";
        $criticos = $this->sistema->conferirEstoqueCritico();

        if (empty($criticos)) {
            echo "{$this->verde}✅ Tudo sob controle! Nenhum item crítico.{$this->reset}\n";
        } else {
            foreach ($criticos as $p) {
                echo "{$this->amarelo}⚠️  {$p->nome} está com apenas {$p->quantidade} unidades!{$this->reset}\n";
            }
        }
    }

    private function removerProduto(): void
    {
        echo "\n{$this->vermelho}--- REMOVER PRODUTO ---{$this->reset}\n";
        echo "Digite o nome do produto que deseja EXCLUIR: ";
        $nome = trim(fgets(STDIN));

        echo "Tem certeza que deseja remover '$nome'? (s/n): ";
        $confirmacao = strtolower(trim(fgets(STDIN)));

        if ($confirmacao === 's') {
            if ($this->sistema->removerProduto($nome)) {
                echo "{$this->verde}✅ Produto removido!{$this->reset}\n";
            } else {
                echo "{$this->vermelho}❌ Produto não encontrado.{$this->reset}\n";
            }
        } else {
            echo "Operação cancelada.\n";
        }
    }
}