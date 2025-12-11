<?php
// Usamos tu archivo de conexión que ya sabe conectarse a Aiven
require_once __DIR__ . '/conexionBBDD.php';

// SQL para crear la tabla 'elmundo'
$sql1 = "CREATE TABLE IF NOT EXISTS `elmundo` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) DEFAULT NULL,
  `link` text,
  `descripcion` text,
  `categoria` varchar(20) DEFAULT NULL,
  `fPubli` date DEFAULT NULL,
  `contenido` longtext,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// SQL para crear la tabla 'elpais'
$sql2 = "CREATE TABLE IF NOT EXISTS `elpais` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) DEFAULT NULL,
  `link` text,
  `descripcion` text,
  `categoria` varchar(20) DEFAULT NULL,
  `fPubli` date DEFAULT NULL,
  `contenido` longtext,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Ejecutamos las órdenes
if (mysqli_query($link, $sql1)) {
    echo "<h3>✅ Tabla 'elmundo' creada (o ya existía).</h3>";
} else {
    echo "<h3>❌ Error con 'elmundo': " . mysqli_error($link) . "</h3>";
}

if (mysqli_query($link, $sql2)) {
    echo "<h3>✅ Tabla 'elpais' creada (o ya existía).</h3>";
} else {
    echo "<h3>❌ Error con 'elpais': " . mysqli_error($link) . "</h3>";
}
?>