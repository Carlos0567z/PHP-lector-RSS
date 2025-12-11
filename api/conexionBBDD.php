<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $database = getenv('DB_NAME');
    $port = getenv('DB_PORT');

    if (empty($host)) {
        throw new Exception("🛑 <strong>Vercel no está leyendo las variables de entorno.</strong><br>Ve a <em>Settings > Environment Variables</em> en Vercel y asegúrate de que DB_HOST, DB_USER, etc. están definidas para el entorno 'Production'.");
    }

    $link = mysqli_init();

    mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 30);

    mysqli_ssl_set($link, NULL, NULL, NULL, NULL, NULL);

    mysqli_real_connect($link, $host, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL);

    $link->query("SET NAMES 'utf8'");

} catch (Exception $e) {
    die("
        <div style='font-family: sans-serif; padding: 20px; border: 2px solid red; background: #ffe6e6; color: #333;'>
            <h2 style='color: #d8000c;'>❌ Error de Conexión</h2>
            
            <p><strong>Mensaje del sistema:</strong> " . $e->getMessage() . "</p>
            
            <hr style='border: 0; border-top: 1px solid #ffcccc;'>
            
            <h3>💡 ¿Qué debo hacer?</h3>
            <ul>
                <li><strong>Si dice 'Connection timed out':</strong> Es el Firewall de Aiven. Ve a Aiven > Overview > Allowed IP Addresses y añade <code>0.0.0.0/0</code>.</li>
                <li><strong>Si dice 'Access denied':</strong> Revisa tu Usuario y Contraseña en las variables de Vercel.</li>
                <li><strong>Si dice 'Vercel no está leyendo...':</strong> Te faltan las variables en Vercel o necesitas hacer un nuevo Deploy.</li>
            </ul>
        </div>
    ");
}
?>