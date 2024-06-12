<?php
/**
 * Obtém o transformador recomendado com base na demanda em kVA.
 */
function obterTransformadorRecomendado($demanda_kva)
{
    if ($demanda_kva >= 60000 && $demanda_kva <= 82000) {
        return "75 kVA";
    } elseif ($demanda_kva >= 83000 && $demanda_kva <= 124000) {
        return "112,5 kVA";
    } elseif ($demanda_kva >= 125000 && $demanda_kva <= 165000) {
        return "150 kVA";
    } elseif ($demanda_kva >= 166000 && $demanda_kva <= 248000) {
        return "225 kVA";
    } elseif ($demanda_kva >= 249000 && $demanda_kva <= 330000) {
        return "300 kVA";
    } elseif ($demanda_kva >= 331000 && $demanda_kva <= 550000) {
        return "500 kVA";
    } elseif ($demanda_kva >= 551000 && $demanda_kva <= 825000) {
        return "750 kVA";
    } elseif ($demanda_kva >= 826000 && $demanda_kva <= 1100000) {
        return "1000 kVA";
    } elseif ($demanda_kva >= 1101000 && $demanda_kva <= 1375000) {
        return "1250 kVA";
    } elseif ($demanda_kva >= 1376000 && $demanda_kva <= 1650000) {
        return "1500 kVA";
    } elseif ($demanda_kva >= 1651000 && $demanda_kva <= 2200000) {
        return "2000 kVA";
    } elseif ($demanda_kva >= 2201000 && $demanda_kva <= 2717000) {
        return "2500 kVA";
    } else {
        return "não há transformador recomendável para essa potência";
    }
}

/**
 * Obtém o fusível monofásico recomendado com base na potência em kVA.
 */
function obterFusiveisMonofasicos($potencia) {
    $fusivel = '';

    if ($potencia <= 5 * 1000) {
        $fusivel = "0,5 H (13,8 kV ou 34,5 kV)";
    } elseif ($potencia <= 10 * 1000) {
        $fusivel = "1 H (13,8 kV ou 34,5 kV)";
    } elseif ($potencia <= 15 * 1000) {
        $fusivel = "1,5 H (13,8 kV)";
    } elseif ($potencia <= 25 * 1000) {
        $fusivel = "2 H (13,8 kV)";
    } elseif ($potencia <= 37.5 * 1000) {
        $fusivel = "3 H (13,8 kV)";
    }

    return $fusivel;
}

/**
 * Obtém o fusível trifásico recomendado com base na potência em kVA.
 */
function obterFusiveisTrifasicos($potencia) {
    $fusivel = '';

    if ($potencia <= 45 * 1000) {
        $fusivel = "2 H (13,8 kV)";
    } elseif ($potencia <= 75 * 1000) {
        $fusivel = "3 H (13,8 kV)";
    } elseif ($potencia <= 112.5 * 1000) {
        $fusivel = "5 H (13,8 kV)";
    } elseif ($potencia <= 150 * 1000) {
        $fusivel = "7 H (13,8 kV)";
    } elseif ($potencia <= 225 * 1000) {
        $fusivel = "10 H (13,8 kV)";
    } elseif ($potencia <= 300 * 1000) {
        $fusivel = "15 H (13,8 kV)";
    } elseif ($potencia <= 500 * 1000) {
        $fusivel = "20 H (13,8 kV)";
    } elseif ($potencia <= 750 * 1000) {
        $fusivel = "30 H (13,8 kV)";
    } elseif ($potencia <= 1000 * 1000) {
        $fusivel = "40 H (13,8 kV)";
    } elseif ($potencia <= 1500 * 1000) {
        $fusivel = "65 H (13,8 kV)";
    }

    return $fusivel;
}

/**
 * Obtém o barramento primário recomendado com base na potência em kVA.
 */
function obterBarramentoPrimario($potencia) {
    $barramento = '';

    if ($potencia <= 800 * 1000) {
        $barramento = "3/4\" x 1/8\" (30 mm²) - 1/4\" Φ";
    } elseif ($potencia <= 1500 * 1000) {
        $barramento = "3/4\" x 3/16\" (40 mm²) - 3/8\" Φ";
    } elseif ($potencia <= 2500 * 1000) {
        $barramento = "1\" x 3/8\" (60 mm²) - 1/2\" Φ";
    }

    return $barramento;
}

/**
 * Obtém o poste recomendado com base na potência em kVA.
 */
function obterPoste($potencia) {
    $poste = '';

    if ($potencia <= 75 * 1000) {
        $poste = "300 daN";
    } elseif ($potencia <= 150 * 1000) {
        $poste = "600 daN";
    } elseif ($potencia <= 225 * 1000) {
        $poste = "800 daN";
    } elseif ($potencia <= 300 * 1000) {
        $poste = "1000 daN";
    }

    return $poste;
}

/**
 * Obtém o circuito secundário recomendado com base na potência em kVA.
 */
function obterCircuitoSecundario($potencia) {
    $tabela = [
        5 => ['220', 23, '1#6 (35)', '20 (3/4")', 25, '2'],
        10 => ['220', 45, '1#6 (35)', '20 (3/4")', 40, '2'],
        15 => ['220', 68, '1#10 (25)', '20 (3/4")', 70, '2'],
        25 => ['220', 114, '1#25 (25)', '25 (1")', 100, '1/0'],
        37.5 => ['220', 170, '1#50 (25)', '25 (1")', 175, '2/0'],
        75 => ['380', 114, '3#35 (95)', '50 (2")', 125, '1/0'],
        112.5 => ['380', 171, '3#70 (35)', '65 (2 1/2")', 175, '2/0'],
        150 => ['380', 228, '3#95 (50)', '65 (2 1/2")', 225, '3/0'],
        225 => ['380', 342, '3#150 (70)', '80 (3")', 350, '4/0'],
        300 => ['380', 456, '2x3#95 (2#150)', '100 (4")', 500, '250'],
    ];

    $pot_kva = $potencia / 1000;
    if (isset($tabela[$pot_kva])) {
        $linha = $tabela[$pot_kva];
        return "Tensão Secundária: {$linha[0]} V, Corrente Nominal Secundária: {$linha[1]} A, Cabo de Cobre: {$linha[2]}, Diâmetro do Eletroduto: {$linha[3]}, Corrente do Disjuntor: {$linha[4]} A, Bitola do Condutor de Aterramento (cobre): {$linha[5]} AWG";
    }

    return 'N/A';
}
?>
