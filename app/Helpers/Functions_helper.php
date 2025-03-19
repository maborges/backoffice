<?php

function displayError($field, $errors)
{
    if (empty($errors)) {
        return;
    }

    if (array_key_exists($field, $errors)) {
        return '<div class="text-danger fw-bold"><small><i class="fa-regular fa-circle-xmark mr-1"></i>' . $errors[$field] . '</small></div>';
    }
}

function printData($data, $die = true)
{
    echo '<pre>';

    if (is_object($data) || is_array($data)) {
        print_r($data);
    } else {
        echo $data;
    }

    if ($die) {
        die(PHP_EOL . 'die => true: ****** FINALIZADO ******');
    }
}

// Retorna valor monetário com 2 casas decimais separado por virgula
function normalizeNumber($value, $decimals = 2)
{
    return number_format($value, $decimals, ',', '.');
}

// Cria prefixo das imagens conforme filial.
function prefixedFileName($file_name)
{
    $prefix = 'gbo' . str_pad(session()->user['selectedBranchId'], 5, '0', STR_PAD_LEFT);
    return $prefix . '_' . $file_name;
}

function gerarPeriodoAnoMesJSON($dataFim, $mesesAtras = 11)
{
    $mesesAbreviados = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        // Cria um objeto DateTime com a data final
        $dataFimObj = new DateTime($dataFim);

        // Ajusta a data final para o último dia do mês
        $dataFimObj->modify('last day of this month');

        // Calcula a data inicial (primeiro dia do mês de $dataFim - 11 meses)
        $dataInicioObj = (new DateTime($dataFim))
            ->modify("first day of -11 months");

        // Inicializa o array de meses
        $meses = [];

        // Itera sobre o intervalo de meses
        while ($dataInicioObj <= $dataFimObj) {
            // Adiciona o ano e o número do mês ao array
            $meses[] = $dataInicioObj->format('Y') . '-' . $mesesAbreviados[$dataInicioObj->format('m') - 1];

            // Avança para o próximo mês
            $dataInicioObj->modify('+1 month');
        }
        // Retorna o array de meses como JSON
        return json_encode($meses);
}

function gerarPeriodoAnoMesIndexJSON($dataFim, $formato = 'm')
{
        // Cria um objeto DateTime com a data final
        $dataFimObj = new DateTime($dataFim);

        // Ajusta a data final para o último dia do mês
        $dataFimObj->modify('last day of this month');

        // Calcula a data inicial (primeiro dia do mês de $dataFim - 11 meses)
        $dataInicioObj = (new DateTime($dataFim))
            ->modify("first day of -11 months");

        // Inicializa o array de meses
        $meses = [];

        // Itera sobre o intervalo de meses
        while ($dataInicioObj <= $dataFimObj) {
            // Adiciona o número do mês ao array
            $meses[] = (int)$dataInicioObj->format($formato);

            // Avança para o próximo mês
            $dataInicioObj->modify('+1 month');
        }
        // Retorna o array de meses como JSON
        return json_encode($meses);
}



