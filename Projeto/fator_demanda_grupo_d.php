<?php
function calcularFatorDemandaGrupoD($pot_va_totais, $max_pot_va)
{
    $fatores = [];
    $max_count = 0;

    foreach ($pot_va_totais as $pot_va) {
        if ($pot_va == $max_pot_va && $max_count == 0) {
            $fatores[] = 1;
            $max_count++;
        } else {
            $fatores[] = 0.7;
        }
    }
    return $fatores;
}
?>
