<?php
/**
 * Obtém o transformador recomendado com base na demanda em kVA.
 */
function obterTransformadorRecomendado($demanda_kva)
{
    if ($demanda_kva >= 60*1e3 && $demanda_kva <= 82*1e3) {
        return "75 kVA";
    } elseif ($demanda_kva >= 83*1e3 && $demanda_kva <= 124*1e3) {
        return "112,5 kVA";
    } elseif ($demanda_kva >= 125*1e3 && $demanda_kva <= 165*1e3) {
        return "150 kVA";
    } elseif ($demanda_kva >= 166*1e3 && $demanda_kva <= 248*1e3) {
        return "225 kVA";
    } elseif ($demanda_kva >= 249*1e3 && $demanda_kva <= 330*1e3) {
        return "300 kVA";
    } elseif ($demanda_kva >= 331*1e3 && $demanda_kva <= 550*1e3) {
        return "500 kVA";
    } elseif ($demanda_kva >= 551*1e3 && $demanda_kva <= 825*1e3) {
        return "750 kVA";
    } elseif ($demanda_kva >= 826*1e3 && $demanda_kva <= 1100*1e3) {
        return "1000 kVA";
    } elseif ($demanda_kva >= 1101*1e3 && $demanda_kva <= 1375*1e3) {
        return "1250 kVA";
    } elseif ($demanda_kva >= 1376*1e3 && $demanda_kva <= 1650*1e3) {
        return "1500 kVA";
    } elseif ($demanda_kva >= 1651*1e3 && $demanda_kva <= 2200*1e3) {
        return "2000 kVA";
    } elseif ($demanda_kva >= 2201*1e3 && $demanda_kva <= 2717*1e3) {
        return "2500 kVA";
    } else {
        return "não há transformador recomendável para essa potência";
    }
}

/**
 * Obtém o fusível elo recomendado com base na potência do transformador.
 */
function obterFusivelElo($transformador_kva) {
    $fusivel = '';

    switch ($transformador_kva) {
        case "75 kVA":
            $fusivel = "3 H (13,8 kV)";
            break;
        case "112,5 kVA":
            $fusivel = "5 H (13,8 kV)";
            break;
        case "150 kVA":
            $fusivel = "7 H (13,8 kV)";
            break;
        case "225 kVA":
            $fusivel = "10 H (13,8 kV)";
            break;
        case "300 kVA":
            $fusivel = "15 H (13,8 kV)";
            break;
        case "500 kVA":
            $fusivel = "20 H (13,8 kV)";
            break;
        case "750 kVA":
            $fusivel = "30 H (13,8 kV)";
            break;
        case "1000 kVA":
            $fusivel = "40 H (13,8 kV)";
            break;
        case "1250 kVA":
            $fusivel = "50 H (13,8 kV)";
            break;
        case "1500 kVA":
            $fusivel = "65 H (13,8 kV)";
            break;
        case "2000 kVA":
            $fusivel = "80 H (13,8 kV)";
            break;
        case "2500 kVA":
            $fusivel = "100 H (13,8 kV)";
            break;
        default:
            $fusivel = "N/A";
            break;
    }

    return $fusivel;
}

/**
 * Obtém o fusível HH recomendado com base na potência do transformador.
 */
function obterFusivelHH($transformador_kva) {
    $fusivel = '';

    switch ($transformador_kva) {
        case "75 kVA":
            $fusivel = "6 A";
            break;
        case "112,5 kVA":
            $fusivel = "8 A";
            break;
        case "150 kVA":
            $fusivel = "10 A";
            break;
        case "225 kVA":
            $fusivel = "16 A";
            break;
        case "300 kVA":
            $fusivel = "20 A";
            break;
        case "500 kVA":
            $fusivel = "32 A";
            break;
        case "750 kVA":
            $fusivel = "50 A";
            break;
        case "1000 kVA":
            $fusivel = "63 A";
            break;
        default:
            $fusivel = "N/A";
            break;
    }

    return $fusivel;
}

/**
 * Obtém o barramento primário recomendado com base na demanda total em kVA.
 */
function obterBarramentoPrimario($demanda_kva) {
    $barramento = '';

    if ($demanda_kva <= 800*1e3) {
        $barramento = "3/4\" x 1/8\" (30 mm²) - 1/4\" Φ";
    } elseif ($demanda_kva <= 1500*1e3) {
        $barramento = "3/4\" x 3/16\" (40 mm²) - 3/8\" Φ";
    } elseif ($demanda_kva <= 2500*1e3) {
        $barramento = "1\" x 3/8\" (60 mm²) - 1/2\" Φ";
    }

    return $barramento;
}

/**
 * Obtém o poste recomendado com base na potência do transformador.
 */
function obterPoste($transformador_kva) {
    $poste = '';

    switch ($transformador_kva) {
        case "75 kVA":
            $poste = "300 daN";
            break;
        case "112,5 kVA":
        case "150 kVA":
            $poste = "600 daN";
            break;
        case "225 kVA":
            $poste = "800 daN";
            break;
        case "300 kVA":
            $poste = "1000 daN";
            break;
        default:
            $poste = "N/A";
            break;
    }

    return $poste;
}

/**
 * Obtém o circuito secundário recomendado com base na potência do transformador.
 */
function obterCircuitoSecundario($transformador_kva) {
    $tabela = [
        "75 kVA" => ['380', 114, '3#35 (95)', '50 (2")', 125, '1/0'],
        "112,5 kVA" => ['380', 171, '3#70 (35)', '65 (2 1/2")', 175, '2/0'],
        "150 kVA" => ['380', 228, '3#95 (50)', '65 (2 1/2")', 225, '3/0'],
        "225 kVA" => ['380', 342, '3#150 (70)', '80 (3")', 350, '4/0'],
        "300 kVA" => ['380', 456, '2x3#95 (2#150)', '100 (4")', 500, '250'],
    ];

    if (isset($tabela[$transformador_kva])) {
        $linha = $tabela[$transformador_kva];
        return "Tensão Secundária: {$linha[0]} V, Corrente Nominal Secundária: {$linha[1]} A, Cabo de Cobre: {$linha[2]}, Diâmetro do Eletroduto: {$linha[3]}, Corrente do Disjuntor: {$linha[4]} A, Bitola do Condutor de Aterramento (cobre): {$linha[5]} AWG";
    }

    return 'N/A';
}
?>
