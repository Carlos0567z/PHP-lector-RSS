<?php
require_once __DIR__ . '/conexionBBDD.php';

$filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Preparamos las consultas base
$sqlMundo = "SELECT * FROM elmundo";
$sqlPais  = "SELECT * FROM elpais";

if ($filtro != "" && $filtro != "Todas") {
    $filtroSeguro = mysqli_real_escape_string($link, $filtro);
    $sqlMundo .= " WHERE categoria LIKE '%$filtroSeguro%'";
    $sqlPais  .= " WHERE categoria LIKE '%$filtroSeguro%'";
}

$sqlMundo .= " ORDER BY fPubli DESC";
$sqlPais  .= " ORDER BY fPubli DESC";

$resMundo = mysqli_query($link, $sqlMundo);
$resPais  = mysqli_query($link, $sqlPais);

$listaCategorias = ["Todas", "Política", "Deportes", "Ciencia", "España", "Economía", "Música", "Cine", "Europa", "Justicia", "Cultura"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Noticias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 p-8">

    <div class="max-w-6xl mx-auto">
        
        <h1 class="text-3xl font-bold mb-6 text-gray-900 border-b pb-4">
            Agregador de Noticias RSS
        </h1>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
            
            <form action="index.php" method="GET" class="flex items-end gap-4">
                
                <div class="w-64">
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">
                        Filtrar por categoría:
                    </label>
                    <select name="categoria" id="categoria" class="block w-full border border-gray-300 rounded-md p-2 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                        <?php foreach ($listaCategorias as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php if($filtro == $cat) echo 'selected'; ?>>
                                <?php echo $cat; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors">
                    Filtrar
                </button>

            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div>
                <h2 class="text-xl font-bold text-cyan-700 mb-3 bg-cyan-50 p-2 rounded">El Mundo</h2>
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Título</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (mysqli_num_rows($resMundo) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($resMundo)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                                        <?php echo date("d/m", strtotime($row['fPubli'])); ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="<?php echo $row['link']; ?>" target="_blank" class="text-cyan-700 font-medium hover:underline">
                                            <?php echo $row['titulo']; ?>
                                        </a>
                                        <div class="text-xs text-gray-400 mt-1">
                                            <?php echo $row['categoria']; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">No hay noticias con este filtro.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold text-indigo-700 mb-3 bg-indigo-50 p-2 rounded">El País</h2>
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Título</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (mysqli_num_rows($resPais) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($resPais)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                                        <?php echo date("d/m", strtotime($row['fPubli'])); ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="<?php echo $row['link']; ?>" target="_blank" class="text-indigo-700 font-medium hover:underline">
                                            <?php echo $row['titulo']; ?>
                                        </a>
                                        <div class="text-xs text-gray-400 mt-1">
                                            <?php echo $row['categoria']; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">No hay noticias con este filtro.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>