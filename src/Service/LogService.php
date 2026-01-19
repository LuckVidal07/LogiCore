<?php

namespace App\Service;

class LogService
{
    public function registrar(string $mensagem)
    {
        $caminhoArquivo = __DIR__ . '/../../data/historico.log';

        $data = date('Y-m-d H:i:s');
        $linhaCompleta = "[$data] " . $mensagem . PHP_EOL;

        file_put_contents($caminhoArquivo, $linhaCompleta, FILE_APPEND);
    }
}