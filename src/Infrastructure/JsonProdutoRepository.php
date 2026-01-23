<?php
namespace App\Infrastructure;

use App\Contract\ProdutoRepositoryInterface;
use App\Entity\Produto;

class JsonProdutoRepository implements ProdutoRepositoryInterface
{
    private string $caminhoArquivo;

    public function __construct()
    {
        $this->caminhoArquivo = __DIR__ . '/../../data/estoque.json';

        $diretorio = dirname($this->caminhoArquivo);
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0775, true);
        }

        if (!file_exists($this->caminhoArquivo)) {
            file_put_contents($this->caminhoArquivo, json_encode([]), LOCK_EX);
        }
    }

    public function buscarPorNome(string $nome): ?Produto
    {
        $dados = $this->carregarDadosPuros();
        $nomeChave = strtolower($nome);

        if (!isset($dados[$nomeChave])) {
            return null;
        }

        $p = $dados[$nomeChave];
        return new Produto($p['nome'], $p['quantidade'], $p['preco']);
    }

    public function buscarTodos(): array
    {
        $dados = $this->carregarDadosPuros();
        return array_map(function ($p) {
            return new Produto($p['nome'], $p['quantidade'], $p['preco']);
        }, $dados);
    }

    public function salvar(Produto $produto): void
    {
        $dados = $this->carregarDadosPuros();

        $nomeChave = strtolower($produto->nome);
        $dados[$nomeChave] = $produto->toArray();

        $this->persistir($dados);
    }

    private function carregarDadosPuros(): array
    {
        if (!file_exists($this->caminhoArquivo))
            return [];

        $json = file_get_contents($this->caminhoArquivo);
        return json_decode($json, true) ?? [];
    }

    private function persistir(array $dados): void
    {
        file_put_contents(
            $this->caminhoArquivo,
            json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX // CRUCIAL: impede corrupção de dados
        );
    }
    public function excluir(string $nome): void
    {
        $dados = $this->carregarDadosPuros();
        $nomeChave = strtolower($nome);

        if (isset($dados[$nomeChave])) {
            unset($dados[$nomeChave]);
            $this->persistir($dados);
        }
    }
}