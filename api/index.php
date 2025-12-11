<?php
require_once __DIR__ . '/conexionBBDD.php';

function limpiarTexto($texto, $limite = 150) {
    $textoLimpio = strip_tags($texto);
    if (strlen($textoLimpio) > $limite) {
        $textoLimpio = substr($textoLimpio, 0, $limite) . "...";
    }
    return $textoLimpio;
}

$sqlMundo = "SELECT * FROM elmundo ORDER BY fPubli DESC, cod DESC";
$resMundo = mysqli_query($link, $sqlMundo);

$sqlPais = "SELECT * FROM elpais ORDER BY fPubli DESC, cod DESC";
$resPais = mysqli_query($link, $sqlPais);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias RSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-blue-900 p-4 shadow-lg text-white sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">📰 Agregador de Noticias</h1>
            <div class="text-sm">PHP + MySQL + Tailwind</div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">

        <div class="mb-12">
            <h2 class="text-3xl font-bold text-blue-800 mb-6 border-b-4 border-blue-500 inline-block pb-2">
                El Mundo
            </h2>
            
            <?php if (mysqli_num_rows($resMundo) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while($row = mysqli_fetch_assoc($resMundo)): ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full">
                            <div class="p-5 flex-grow">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    <?php echo htmlspecialchars($row['categoria'] ?: '[General]'); ?>
                                </span>
                                
                                <h3 class="mt-3 text-lg font-bold text-gray-900 leading-tight">
                                    <a href="<?php echo $row['link']; ?>" target="_blank" class="hover:text-blue-600">
                                        <?php echo $row['titulo']; ?>
                                    </a>
                                </h3>

                                <p class="text-gray-400 text-sm mt-1 mb-3">
                                    📅 <?php echo date("d/m/Y", strtotime($row['fPubli'])); ?>
                                </p>

                                <p class="text-gray-600 text-sm">
                                    <?php echo limpiarTexto($row['descripcion']); ?>
                                </p>
                            </div>

                            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                                <a href="<?php echo $row['link']; ?>" target="_blank" class="text-blue-600 font-semibold hover:text-blue-800 text-sm uppercase">
                                    Leer noticia &rarr;
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p>No hay noticias de El Mundo guardadas aún.</p>
                </div>
            <?php endif; ?>
        </div>


        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-4 border-gray-500 inline-block pb-2">
                El País
            </h2>

            <?php if (mysqli_num_rows($resPais) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while($row = mysqli_fetch_assoc($resPais)): ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full">
                            <div class="p-5 flex-grow">
                                <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    <?php echo htmlspecialchars($row['categoria'] ?: '[General]'); ?>
                                </span>
                                
                                <h3 class="mt-3 text-lg font-bold text-gray-900 leading-tight">
                                    <a href="<?php echo $row['link']; ?>" target="_blank" class="hover:text-gray-600">
                                        <?php echo $row['titulo']; ?>
                                    </a>
                                </h3>

                                <p class="text-gray-400 text-sm mt-1 mb-3">
                                    📅 <?php echo date("d/m/Y", strtotime($row['fPubli'])); ?>
                                </p>

                                <p class="text-gray-600 text-sm">
                                    <?php echo limpiarTexto($row['descripcion']); ?>
                                </p>
                            </div>
                            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                                <a href="<?php echo $row['link']; ?>" target="_blank" class="text-gray-700 font-semibold hover:text-black text-sm uppercase">
                                    Leer noticia &rarr;
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p>No hay noticias de El País guardadas aún.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <footer class="bg-white border-t mt-12 py-8 text-center text-gray-500 text-sm">
        <p>Proyecto RSS con PHP y Tailwind</p>
    </footer>

</body>
</html>