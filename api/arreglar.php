<?php
// Usamos tu conexión existente
require_once __DIR__ . '/conexionBBDD.php';

// Órdenes SQL para ampliar la columna 'categoria' a 255 letras
$sql1 = "ALTER TABLE `elpais` MODIFY `categoria` VARCHAR(255);";
$sql2 = "ALTER TABLE `elmundo` MODIFY `categoria` VARCHAR(255);";

// Ejecutar para El País
if (mysqli_query($link, $sql1)) {
    echo "✅ Tabla 'elpais' corregida (ahora acepta categorías largas).<br>";
} else {
    echo "❌ Error en 'elpais': " . mysqli_error($link) . "<br>";
}

// Ejecutar para El Mundo
if (mysqli_query($link, $sql2)) {
    echo "✅ Tabla 'elmundo' corregida (ahora acepta categorías largas).<br>";
} else {
    echo "❌ Error en 'elmundo': " . mysqli_error($link) . "<br>";
}
?>