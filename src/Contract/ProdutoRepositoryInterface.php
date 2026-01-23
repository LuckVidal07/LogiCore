<?php
namespace App\Contract;

use App\Entity\Produto;

interface ProdutoRepositoryInterface {
    public function buscarPorNome(string $nome): ?Produto;
    public function salvar(Produto $produto): void;
    public function buscarTodos(): array;
    public function excluir(string $nome): void;
}