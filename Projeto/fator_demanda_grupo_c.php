<?php
function calcularFatorDemandaGrupoC($numAparelhos)
{
    $fatores = [1.00,1.00, 0.88, 0.82, 0.78, 0.76, 0.74, 0.72, 0.71, 0.70];
    return $numAparelhos <= 10 ? $fatores[$numAparelhos - 1] : 0.70;
}
?>
