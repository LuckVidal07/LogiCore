<?php
namespace App\Service;

use App\Contract\ProdutoRepositoryInterface;
use App\Entity\Produto;
use Exception;

class EstoqueService
{
    private ProdutoRepositoryInterface $repository;
    private LogService $logger;
    private const LIMITE_ESTOQUE_CRITICO = 5;

    public function __construct(ProdutoRepositoryInterface $repository, LogService $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function adicionarProduto(string $nome, int $quantidade, float $preco): void
    {
        // Busca direta: O repositório resolve se está no JSON ou não
        $produto = $this->repository->buscarPorNome($nome);

        if ($produto) {
            $produto->adicionarEstoque($quantidade);
            $produto->preco = $preco;
        } else {
            $produto = new Produto($nome, $quantidade, $preco);
        }

        $this->repository->salvar($produto);
        $this->logger->registrar("ENTRADA: $quantidade unidades de '$nome'.");
    }

    public function darBaixa(string $nome, int $quantidade): bool
    {
        $produto = $this->repository->buscarPorNome($nome);

        if (!$produto) {
            return false;
        }

        try {
            $produto->reduzirEstoque($quantidade);
            $this->repository->salvar($produto);
            $this->logger->registrar("BAIXA: $quantidade de {$produto->nome}");
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function listarTudo(): array
    {
        try {
            return $this->repository->buscarTodos();
        } catch (Exception $e) {
            $this->logger->registrar("ERRO: Falha ao listar produtos. " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna apenas os produtos que estão com quantidade abaixo do limite.
     * Útil para relatórios de reposição rápida.
     */
    public function conferirEstoqueCritico(): array
    {
        $todos = $this->repository->buscarTodos();

        // Filtra o array sem precisar carregar lógica pesada
        $criticos = array_filter($todos, function ($produto) {
            return $produto->quantidade <= self::LIMITE_ESTOQUE_CRITICO;
        });

        if (count($criticos) > 0) {
            $this->logger->registrar("ALERTA: " . count($criticos) . " itens em nível crítico.");
        }

        return $criticos;
    }

    public function isProdutoEmNivelCritico(string $nome): bool
    {
        $produto = $this->repository->buscarPorNome($nome);
        return $produto && $produto->quantidade <= self::LIMITE_ESTOQUE_CRITICO;
    }

    public function removerProduto(string $nome): bool
    {
        $produto = $this->repository->buscarPorNome($nome);

        if (!$produto) {
            $this->logger->registrar("AVISO: Tentativa de remover produto inexistente: '$nome'.");
            return false;
        }

        try {
            $this->repository->excluir($nome);

            // 3. Log de auditoria (importante para saber quem/quando sumiu com um item)
            $this->logger->registrar("REMOÇÃO: Produto '$nome' foi excluído do catálogo.");

            return true;
        } catch (Exception $e) {
            $this->logger->registrar("ERRO: Falha ao excluir '$nome'. " . $e->getMessage());
            throw $e;
        }
    }
}