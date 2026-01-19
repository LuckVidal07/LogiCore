<?php
namespace App\Entity;

class Produto
{
    public function __construct(
        public string $nome,
        public int $quantidade,
        public float $preco
    ) {
        if ($this->quantidade < 0) {
            throw new \Exception("Quantidade não pode ser negativa.");
        }
    }

    public function reduzirEstoque(int $qtd): void
    {
        if ($qtd > $this->quantidade) {
            throw new \Exception("Estoque insuficiente para {$this->nome}.");
        }
        $this->quantidade -= $qtd;
    }

    public function adicionarEstoque(int $qtd): void
    {
        $this->quantidade += $qtd;
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'quantidade' => $this->quantidade,
            'preco' => $this->preco
        ];
    }
}