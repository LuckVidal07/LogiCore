<div class="card shadow-sm mx-auto" style="max-width: 500px;">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Entrada de Mercadoria</h4>
    </div>
    <div class="card-body">
        <form action="?acao=salvar" method="POST">
            <div class="mb-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" name="nome" class="form-control" required placeholder="Ex: Teclado Mecânico">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantidade</label>
                    <input type="number" name="qtd" class="form-control" required min="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Preço Unitário</label>
                    <input type="number" step="0.01" name="preco" class="form-control" required>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Salvar no Estoque</button>
                <a href="index.php" class="btn btn-light">Cancelar</a>
            </div>
        </form>
    </div>
</div>