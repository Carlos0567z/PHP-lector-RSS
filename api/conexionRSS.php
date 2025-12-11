<?php

function download($ruta){
    $ch = curl_init();
    
    // Configuración de la descarga
    curl_setopt($ch, CURLOPT_URL, $ruta);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Seguir redirecciones
    
    // Simulamos ser un navegador para evitar bloqueos
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $salida = curl_exec($ch);
    
    // Si falla, devolvemos error (sin usar curl_close)
    if(curl_errno($ch) || empty($salida)){
        return "<error>No se pudo descargar el feed</error>";
    }
    
    // Ya no hace falta cerrar, PHP lo hace solo
    return $salida;
}
?>