<?php
require_once __DIR__ . '/conexionBBDD.php';

$sqlMundo = "SELECT * FROM elmundo ORDER BY fPubli DESC ";
$resMundo = mysqli_query($link, $sqlMundo);

$sqlPais = "SELECT * FROM elpais ORDER BY fPubli DESC";
$resPais = mysqli_query($link, $sqlPais);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Noticias - Tablas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-slate-800 p-4 shadow-md mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-white font-bold text-xl">📡 Mis Noticias RSS</div>
            <div class="space-x-4">
                <a href="#elmundo" class="text-gray-300 hover:text-white hover:underline transition">El Mundo</a>
                <a href="#elpais" class="text-gray-300 hover:text-white hover:underline transition">El País</a>
                <a href="index.php" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-sm transition">Recargar</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 pb-12">

        <div id="elmundo" class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-cyan-700 border-b pb-2">Noticias: El Mundo</h2>
            
            <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200 bg-white">
                <table class="w-full table-auto text-left text-sm">
                    <thead class="bg-gray-100 uppercase text-gray-600 font-bold">
                        <tr>
                            <th class="px-6 py-3 border-b">Fecha</th>
                            <th class="px-6 py-3 border-b">Categoría</th>
                            <th class="px-6 py-3 border-b">Título y Descripción</th>
                            <th class="px-6 py-3 border-b text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while($row = mysqli_fetch_assoc($resMundo)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                <?php echo date("d/m/Y", strtotime($row['fPubli'])); ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-cyan-100 text-cyan-800 px-2 py-1 rounded text-xs font-semibold">
                                    <?php echo $row['categoria']; ?>
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 text-base mb-1">
                                    <?php echo $row['titulo']; ?>
                                </div>
                                <div class="text-gray-500 text-xs">
                                    <?php echo substr(strip_tags($row['descripcion']), 0, 120) . "..."; ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="<?php echo $row['link']; ?>" target="_blank" class="text-cyan-600 hover:text-cyan-900 font-bold hover:underline">
                                    Leer
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="elpais">
            <h2 class="text-2xl font-bold mb-4 text-indigo-700 border-b pb-2">Noticias: El País</h2>
            
            <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200 bg-white">
                <table class="w-full table-auto text-left text-sm">
                    <thead class="bg-gray-100 uppercase text-gray-600 font-bold">
                        <tr>
                            <th class="px-6 py-3 border-b">Fecha</th>
                            <th class="px-6 py-3 border-b">Categoría</th>
                            <th class="px-6 py-3 border-b">Título y Descripción</th>
                            <th class="px-6 py-3 border-b text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while($row = mysqli_fetch_assoc($resPais)): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                <?php echo date("d/m/Y", strtotime($row['fPubli'])); ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-semibold">
                                    <?php echo $row['categoria']; ?>
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 text-base mb-1">
                                    <?php echo $row['titulo']; ?>
                                </div>
                                <div class="text-gray-500 text-xs">
                                    <?php echo substr(strip_tags($row['descripcion']), 0, 120) . "..."; ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="<?php echo $row['link']; ?>" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                    Leer
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>