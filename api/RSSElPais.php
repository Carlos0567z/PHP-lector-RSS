<?php
require_once __DIR__ . '/conexionRSS.php';
require_once __DIR__ . '/conexionBBDD.php';

$sXML = download("http://ep00.epimg.net/rss/elpais/portada.xml");

if (strpos($sXML, '<error>') === false && !empty($sXML)) {
    
    $oXML = new SimpleXMLElement($sXML);

    if ($link) {
        // Filtros
        $misFiltros = [
            "Política", "Politica", "Deportes", "Sport", "Ciencia", 
            "España", "Nacional", "Interior", "Economía", "Economia", 
            "Música", "Musica", "Concierto", "Cine", "Película", 
            "Europa", "Internacional", "Justicia", "Tribunales", "Cultura"
        ];

        foreach ($oXML->channel->item as $item) {
            $categoriaParaGuardar = "";
            $encontrado = false;

            foreach ($item->category as $catXML) {
                $catLimpia = trim((string)$catXML);

                foreach ($misFiltros as $filtro) {
                    if (mb_stripos($catLimpia, $filtro) !== false) {
                        $etiquetaFinal = ucfirst($filtro);
                        if (strpos($categoriaParaGuardar, "[" . $etiquetaFinal . "]") === false) {
                            $categoriaParaGuardar .= "[" . $etiquetaFinal . "]";
                            $encontrado = true;
                        }
                    }
                }
            }

            if ($encontrado) {
                $enlace = mysqli_real_escape_string($link, $item->link);

                $checkSQL = "SELECT link FROM elpais WHERE link = '$enlace' LIMIT 1";
                $checkResult = mysqli_query($link, $checkSQL);

                if (mysqli_num_rows($checkResult) == 0) {
                    
                    $fPubli = strtotime($item->pubDate);
                    $new_fPubli = date('Y-m-d', $fPubli);

                    $content = $item->children("content", true);
                    $encoded = (string)$content->encoded;

                    $titulo = mysqli_real_escape_string($link, $item->title);
                    $desc   = mysqli_real_escape_string($link, $item->description);
                    $cont   = mysqli_real_escape_string($link, $encoded);
                    $cat    = mysqli_real_escape_string($link, $categoriaParaGuardar);

                    $sql = "INSERT INTO elpais VALUES(NULL, '$titulo', '$enlace', '$desc', '$cat', '$new_fPubli', '$cont')";
                    mysqli_query($link, $sql);
                }
            }
        }
    }
}
?>