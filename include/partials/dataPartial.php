
<?php
$dies = array(
    1 => "Dilluns",
    2 => "Dimarts",
    3 => "Dimecres",
    4 => "Dijous",
    5 => "Divendres",
    6 => "Dissabte",
    7 => "Diumenge"
);

$mesos = array(
    1 => "Gener",
    2 => "Febrer",
    3 => "Març",
    4 => "Abril",
    5 => "Maig",
    6 => "Juny",
    7 => "Juliol",
    8 => "Agost",
    9 => "Setembre",
    10 => "Octubre",
    11 => "Novembre",
    12 => "Desembre"
);

$diaSetmana = date('N'); 
$diaMes = date('j'); 
$mes = (int)date('m'); 
$any = date('Y'); 

$dataFormatejada = $dies[$diaSetmana] . ", " . $diaMes . " de " . $mesos[$mes] . " de " . $any;


echo $dataFormatejada;
?>
