<?php
include 'fator_demanda_grupo_a.php';
include 'fator_demanda_grupo_b.php';
include 'fator_demanda_grupo_c.php';
include 'fator_demanda_grupo_d.php';
include 'fator_demanda_grupo_e.php';

function calcularFatoresDemanda($potencias, $cargas, $qtds, $ramo_selecionado)
{
    $fatores_demanda = [];

    foreach (['A', 'B', 'C', 'D', 'E'] as $grupo) {
        if (isset($potencias[$grupo])) {
            switch ($grupo) {
                case 'A':
                    $fatores_demanda[$grupo] = calcularFatorDemandaGrupoA($ramo_selecionado, array_map(fn ($pot, $qtd) => $pot * $qtd, $potencias[$grupo], $qtds[$grupo]));
                    break;
                case 'B':
                    $fatores_demanda[$grupo] = array_map(fn ($carga, $qtd) => calcularFatorDemandaGrupoB($carga, $qtd), $cargas[$grupo], $qtds[$grupo]);
                    break;
                case 'C':
                    $fatores_demanda[$grupo] = array_fill(0, count($potencias[$grupo]), calcularFatorDemandaGrupoC(count($potencias[$grupo])));
                    break;
                case 'D':
                    $pot_va_totais = array_map(fn ($pot, $qtd) => $pot * $qtd, $potencias[$grupo], $qtds[$grupo]);
                    $max_pot_va = max($pot_va_totais);
                    $fatores_demanda[$grupo] = calcularFatorDemandaGrupoD($pot_va_totais, $max_pot_va);
                    break;
                case 'E':
                    $pot_va_totais = array_map(fn ($pot, $qtd) => $pot * $qtd, $potencias[$grupo], $qtds[$grupo]);
                    $max_pot_va = max($pot_va_totais);
                    $fatores_demanda[$grupo] = calcularFatorDemandaGrupoE($pot_va_totais, $max_pot_va);
                    break;
            }
        }
    }

    return $fatores_demanda;
}
?>
