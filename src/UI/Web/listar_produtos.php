<?php 
// Preparando dados para o gráfico (PHP para JS)
$labels = [];
$quantidades = [];
foreach ($produtos as $p) {
    $labels[] = $p->nome;
    $quantidades[] = $p->quantidade;
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">📊 Níveis de Estoque</h5>
                <canvas id="estoqueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm bg-primary text-white mb-3">
            <div class="card-body text-center">
                <h6>Total de Itens</h6>
                <h2 class="mb-0"><?= array_sum($quantidades) ?></h2>
            </div>
        </div>
        <div class="card shadow-sm bg-warning text-dark">
            <div class="card-body text-center">
                <h6>Itens Críticos</h6>
                <h2 class="mb-0"><?= count(array_filter($quantidades, fn($q) => $q <= 5)) ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📋 Detalhes do Inventário</h2>
    <a href="?acao=form_adicionar" class="btn btn-primary">+ Novo Produto</a>
</div>

<div class="table-responsive">
    <table class="table table-hover shadow-sm bg-white">
        </table>
</div>

<script>
const ctx = document.getElementById('estoqueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Quantidade em Estoque',
            data: <?= json_encode($quantidades) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>