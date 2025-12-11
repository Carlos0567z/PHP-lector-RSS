<?php

function download($ruta){
    $ch = curl_init();
    
    // URL a descargar
    curl_setopt($ch, CURLOPT_URL, $ruta);
    
    // Devuelve el resultado como string en lugar de imprimirlo directamente
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    // ¡CRUCIAL! Seguir redirecciones (si http te manda a https)
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    
    // Simulamos ser un navegador Chrome para que no nos bloqueen por ser un "bot"
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
    // Aumentamos el tiempo de espera por si la conexión es lenta
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    // Saltamos la verificación estricta SSL (ayuda en algunos entornos de servidor)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $salida = curl_exec($ch);
    
    // Si curl falla, devolvemos un XML vacío válido para evitar el Fatal Error
    if(curl_errno($ch) || empty($salida)){
        // Podríamos imprimir el error para debug: echo curl_error($ch);
        curl_close($ch);
        return "<error>No se pudo descargar el feed</error>";
    }
    
    curl_close($ch);
    return $salida;
}
?>