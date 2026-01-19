<?php
namespace App\Infrastructure;

use App\Contract\ProdutoRepositoryInterface;

class JsonProdutoRepository implements ProdutoRepositoryInterface {
    private string $arquivo = __DIR__ . '/../../data/estoque.json';

    public function buscarTodos(): array {
        if (!file_exists($this->arquivo)) {
            return [];
        }
        $dados = file_get_contents($this->arquivo);
        // O true transforma o JSON em Array do PHP
        return json_decode($dados, true) ?? [];
    }

    public function salvar(array $produtos): void {
        // JSON_PRETTY_PRINT deixa o arquivo legível para humanos
        $json = json_encode($produtos, JSON_PRETTY_PRINT);
        file_put_contents($this->arquivo, $json);
    }
}