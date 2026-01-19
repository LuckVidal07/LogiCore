<?php
namespace App\Contract;

use App\Entity\Produto;

interface ProdutoRepositoryInterface {
    public function buscarTodos(): array;
    public function salvar(array $produtos): void;
}