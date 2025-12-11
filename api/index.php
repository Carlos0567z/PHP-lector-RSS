<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Noticias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'mi-morado': '#E4CCE8',
                        'mi-cyan': '#66E9D9',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-900 text-gray-200 p-4 font-sans">
    
    <div class="w-full">
        
        <form action="index.php" class="mb-8">
            <fieldset class="border-2 border-mi-morado rounded-lg p-6 shadow-lg shadow-purple-900/20">
                <legend class="px-2 text-mi-cyan font-bold text-xl tracking-wider">FILTRO</legend>
                
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <label class="font-semibold text-mi-morado">PERIODICO : </label>
                        <select type="selector" name="periodicos" class="bg-gray-800 border border-mi-morado text-white rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-mi-cyan">
                            <option name="elpais">El Pais</option>
                            <option name="elmundo">El Mundo</option>
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <label class="font-semibold text-mi-morado">CATEGORIA : </label>
                        <select type="selector" name="categoria" value="" class="bg-gray-800 border border-mi-morado text-white rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-mi-cyan">
                            <option name=""></option>
                            <option name="Política">Política</option>
                            <option name="Deportes">Deportes</option>
                            <option name="Ciencia">Ciencia</option>
                            <option name="España">España</option>
                            <option name="Economía">Economía</option>
                            <option name="Música">Música</option>
                            <option name="Cine">Cine</option>
                            <option name="Europa">Europa</option>
                            <option name="Justicia">Justicia</option>
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <label class="font-semibold text-mi-morado">FECHA : </label>
                        <input type="date" name="fecha" value="" class="bg-gray-800 border border-mi-morado text-white rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-mi-cyan text-sm"></input>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 flex-grow">
                        <label class="font-semibold text-mi-morado sm:ml-4">AMPLIAR FILTRO (palabra) : </label>
                        <input type="text" name="buscar" value="" class="bg-gray-800 border border-mi-morado text-white rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-mi-cyan flex-grow w-full sm:w-auto"></input>
                    </div>

                    <input type="submit" name="filtrar" value="Filtrar" class="bg-mi-cyan hover:bg-teal-400 text-gray-900 font-bold py-1 px-6 rounded cursor-pointer transition-colors duration-200 w-full sm:w-auto">
                </div>
            </fieldset>
        </form>

        <div class="w-full overflow-x-auto">
            <?php

            require_once __DIR__ . '/RSSElPais.php';
            require_once __DIR__ . '/RSSElMundo.php';
            require_once __DIR__ . '/conexionBBDD.php';

            function filtros($sql, $link)
            {
                $filtrar = mysqli_query($link, $sql);
                while ($arrayFiltro = mysqli_fetch_array($filtrar)) {

                    echo "<tr class='hover:bg-gray-800 transition-colors'>";
                    echo "<th class='border border-mi-morado p-2 font-normal text-left align-top text-sm break-words'>" . $arrayFiltro['titulo'] . "</th>";
                    echo "<th class='border border-mi-morado p-2 font-normal text-left align-top text-xs break-words'>" . $arrayFiltro['contenido'] . "</th>";
                    echo "<th class='border border-mi-morado p-2 font-normal text-left align-top text-xs break-words'>" . $arrayFiltro['descripcion'] . "</th>";
                    echo "<th class='border border-mi-morado p-2 font-normal text-center align-top text-sm'>" . $arrayFiltro['categoria'] . "</th>";
                    echo "<th class='border border-mi-morado p-2 font-normal text-center align-top text-mi-cyan underline text-xs break-all'><a href='" . $arrayFiltro['link'] . "' target='_blank'>" . $arrayFiltro['link'] . "</a></th>";
                    $fecha = date_create($arrayFiltro['fPubli']);
                    $fechaConversion = date_format($fecha, 'd-M-Y');
                    echo "<th class='border border-mi-morado p-2 font-normal text-center align-top whitespace-nowrap text-sm'>" . $fechaConversion . "</th>";
                    echo "</tr>";
                }
            }

            require_once "conexionBBDD.php";

            if (mysqli_connect_error()) {
                printf("Conexión fallida");
            } else {

                echo "<table class='w-full border-collapse border-4 border-mi-morado shadow-2xl bg-gray-900/50 text-gray-300 table-fixed'>";
                
                echo "<tr class='bg-gray-800'>";
                echo "<th class='p-3 text-mi-cyan border border-mi-morado text-base w-[15%]'>TITULO</th>";
                echo "<th class='p-3 text-mi-cyan border border-mi-morado text-base w-[25%]'>CONTENIDO</th>";
                echo "<th class='p-3 text-mi-cyan border border-mi-morado text-base w-[25%]'>DESCRIPCIÓN</th>";
                echo "<th class='p-3 text-mi-cyan border border-mi-morado text-base w-[10%]'>CATEGORÍA</th>";
                echo "<th class='p-3 text-mi-cyan border border-mi-morado text-base w-[15%]'>ENLACE</th>";
                echo "<th class='p-3 text-mi-cyan border border-mi-morado text-base w-[10%]'>FECHA</th>";
                echo "</tr>";


                if (isset($_REQUEST['filtrar'])) {

                    $periodicos = str_replace(' ', '', $_REQUEST['periodicos']);
                    $periodicosMin = strtolower($periodicos);


                    $cat = $_REQUEST['categoria'];
                    $f = $_REQUEST['fecha'];
                    $palabra = $_REQUEST["buscar"];


                    if ($cat == "" && $f == "" && $palabra == "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " ORDER BY fPubli desc";
                        filtros($sql, $link);
                    }


                    if ($cat != "" && $f == "" && $palabra == "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " WHERE categoria LIKE '%$cat%'";
                        filtros($sql, $link);
                    }


                    if ($cat == "" && $f != "" && $palabra == "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " WHERE fPubli='$f'";
                        filtros($sql, $link);
                    }

                    if ($cat != "" && $f != "" && $palabra == "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " WHERE categoria LIKE '%$cat%' and fPubli='$f'";
                        filtros($sql, $link);
                    }


                    if ($cat != "" && $f != "" && $palabra != "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " WHERE descripcion LIKE '%$palabra%' and categoria LIKE '%$cat%' and fPubli='$f'";
                        filtros($sql, $link);
                    }


                    if ($cat != "" && $f == "" && $palabra != "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " WHERE descripcion LIKE '%$palabra%' and categoria LIKE '%$cat%'";
                        filtros($sql, $link);
                    }


                    if ($cat == "" && $f != "" && $palabra != "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " WHERE descripcion LIKE '%$palabra%' and fPubli='$f'";
                        filtros($sql, $link);
                    }


                    if ($palabra != "" && $cat == "" && $f == "") {
                        $sql = "SELECT * FROM " . $periodicosMin . " WHERE descripcion LIKE '%$palabra%' ";
                        filtros($sql, $link);
                    }
                } else {

                    $sql = "SELECT * FROM elpais ORDER BY fPubli desc";
                    filtros($sql, $link);
                }
            }

            echo "</table>";

            ?>
        </div>
    </div>

</body>

</html>