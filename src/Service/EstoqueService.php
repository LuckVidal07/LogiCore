<?php
namespace App\Service;

use App\Contract\ProdutoRepositoryInterface;
use App\Entity\Produto;

class EstoqueService
{
    private ProdutoRepositoryInterface $repository;
    private LogService $logger;

    public function __construct(ProdutoRepositoryInterface $repository, LogService $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * Helper privado para transformar arrays do JSON em Objetos Produto
     */
    private function carregarProdutos(): array
    {
        $dados = $this->repository->buscarTodos();
        return array_map(fn($p) => new Produto($p['nome'], $p['quantidade'], $p['preco']), $dados);
    }

    /**
     * Helper privado para transformar Objetos de volta em Arrays para o JSON
     */
    private function persistir(array $produtos): void
    {
        $dadosParaSalvar = array_map(fn($p) => $p->toArray(), $produtos);
        $this->repository->salvar($dadosParaSalvar);
    }

    public function adicionarProduto(string $nome, int $quantidade, float $preco): void
    {
        $produtos = $this->carregarProdutos();
        $encontrado = false;

        foreach ($produtos as $produto) {
            if (strtolower($produto->nome) === strtolower($nome)) {
                $produto->adicionarEstoque($quantidade);
                $produto->preco = $preco; // Atualiza o preço
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $produtos[] = new Produto($nome, $quantidade, $preco);
        }

        $this->persistir($produtos);
        $this->logger->registrar("ENTRADA: $quantidade unidades de '$nome'.");
    }

    public function darBaixa(string $nome, int $quantidade): bool
    {
        $produtos = $this->carregarProdutos();
        $sucesso = false;

        foreach ($produtos as $produto) {
            if (strtolower($produto->nome) === strtolower($nome)) {
                try {
                    $produto->reduzirEstoque($quantidade);
                    $this->logger->registrar("BAIXA: $quantidade de {$produto->nome}");
                    $sucesso = true;
                } catch (\Exception $e) {
                    echo "\033[31m❌ Erro: " . $e->getMessage() . "\033[0m\n";
                    return false;
                }
                break;
            }
        }

        if ($sucesso) {
            $this->persistir($produtos);
            return true;
        }

        echo "\033[31m❌ Produto '$nome' não encontrado.\033[0m\n";
        return false;
    }

    public function listarTudo(): void
    {
        $produtos = $this->carregarProdutos();

        if (empty($produtos)) {
            echo "\n[!] O estoque está vazio.\n";
            return;
        }

        echo "\n" . str_repeat("-", 55) . "\n";
        printf("%-25s | %-10s | %-10s\n", "PRODUTO", "QTD", "PREÇO");
        echo str_repeat("-", 55) . "\n";

        foreach ($produtos as $p) {
            printf("%-25s | %-10d | R$ %-8.2f\n", $p->nome, $p->quantidade, $p->preco);
        }
    }

    public function conferirEstoqueCritico(): void
    {
        $produtos = $this->carregarProdutos();
        $criticos = array_filter($produtos, fn($p) => $p->quantidade < 5);

        if (empty($criticos)) {
            echo "✅ Nenhum item em nível crítico.\n";
            return;
        }

        foreach ($criticos as $p) {
            echo "⚠️ ALERTA: {$p->nome} possui apenas {$p->quantidade} unidades!\n";
            $this->logger->registrar("CRÍTICO: {$p->nome} com {$p->quantidade} un.");
        }
    }

    public function removerProduto(string $nome): bool
    {
        $produtos = $this->carregarProdutos();
        $totalOriginal = count($produtos);

        // Filtra o array: mantém apenas quem NÃO tem o nome digitado
        $produtosFiltrados = array_filter($produtos, function ($p) use ($nome) {
            return strtolower($p->nome) !== strtolower($nome);
        });

        if (count($produtosFiltrados) === $totalOriginal) {
            echo "\033[31m❌ Produto '$nome' não encontrado para remoção.\033[0m\n";
            return false;
        }

        $this->persistir($produtosFiltrados);
        $this->logger->registrar("REMOÇÃO: Produto '$nome' excluído do sistema.");
        echo "\033[32m✅ Produto removido com sucesso!\033[0m\n";

        return true;
    }
}