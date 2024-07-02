<?php
function calcularFatorDemandaGrupoA($ramo_selecionado, $potencias)
{
    $fatores = [];
    $potencia_total = 0;

    foreach ($potencias as $potencia) {
        switch ($ramo_selecionado) {
            case 'auditorio':
            case 'bancos':
            case 'barbearias':
            case 'clubes':
            case 'igreja':
            case 'restaurantes':
                $fatores[] = 1;
                break;
            case 'escola':
                $potencia_total += $potencia;
                if ($potencia_total <= 12000) {
                    $fatores[] = 1;
                } else {
                    $fatores[] = 0.5;
                }
                break;
            case 'escritorios':
                $potencia_total += $potencia;
                if ($potencia_total <= 20000) {
                    $fatores[] = 1;
                } else {
                    $fatores[] = 0.7;
                }
                break;
            case 'garagem':
                $fatores[] = 1;
                break;
            case 'hospitais':
                $potencia_total += $potencia;
                if ($potencia_total <= 50000) {
                    $fatores[] = 0.4;
                } else {
                    $fatores[] = 0.2;
                }
                break;
            case 'hoteis':
                $potencia_total += $potencia;
                if ($potencia_total <= 20000) {
                    $fatores[] = 0.5;
                } elseif ($potencia_total <= 100000) {
                    $fatores[] = 0.4;
                } else {
                    $fatores[] = 0.3;
                }
                break;
            case 'residencias':
                $potencia_total += $potencia;
                if ($potencia_total <= 10000) {
                    $fatores[] = 1;
                } elseif ($potencia_total <= 120000) {
                    $fatores[] = 0.35;
                } else {
                    $fatores[] = 0.25;
                }
                break;
            default:
                $fatores[] = 69; // Caso padrão se o ramo não for reconhecido
        }
    }

    return $fatores;
}
?>
