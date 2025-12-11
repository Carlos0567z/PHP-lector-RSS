<?php
require_once __DIR__ . '/conexionRSS.php';
require_once __DIR__ . '/conexionBBDD.php';

// URL del Feed
$urlFeed = "https://e00-elmundo.uecdn.es/elmundo/rss/espana.xml";
$sXML = download($urlFeed);

if (strpos($sXML, '<error>') === false && !empty($sXML)) {
    
    $oXML = new SimpleXMLElement($sXML);

    if ($link) {
        $misFiltros = [
            "Política", "Politica", "Deportes", "Sport", "Ciencia", 
            "Interior", "Economía", "Economia", 
            "Música", "Musica", "Cine", "Cultura", 
            "Sucesos", "Tribunales"
        ];

        foreach ($oXML->channel->item as $item) {
            $categoriaParaGuardar = "";
            
            if (strpos($urlFeed, 'espana.xml') !== false) {
                $categoriaParaGuardar .= "[España]";
            }

            foreach ($item->category as $catXML) {
                $catLimpia = trim((string)$catXML);
                
                if (mb_stripos($catLimpia, "Nacional") !== false) {
                     if (strpos($categoriaParaGuardar, "[España]") === false) {
                         $categoriaParaGuardar .= "[España]";
                     }
                }

                foreach ($misFiltros as $filtro) {
                    if (mb_stripos($catLimpia, $filtro) !== false) {
                        $etiquetaFinal = ucfirst($filtro); 
                        if ($etiquetaFinal == "Politica") $etiquetaFinal = "Política";
                        if ($etiquetaFinal == "Economia") $etiquetaFinal = "Economía";

                        if (strpos($categoriaParaGuardar, "[" . $etiquetaFinal . "]") === false) {
                            $categoriaParaGuardar .= "[" . $etiquetaFinal . "]";
                        }
                    }
                }
            }

            if ($categoriaParaGuardar != "") {
                
                $enlace = mysqli_real_escape_string($link, $item->link);

                $checkSQL = "SELECT link FROM elmundo WHERE link = '$enlace' LIMIT 1";
                $checkResult = mysqli_query($link, $checkSQL);

                if (mysqli_num_rows($checkResult) == 0) {
                    
                    $media = $item->children("media", true);
                    if (isset($media->description) && !empty($media->description)) {
                        $descripcionRaw = (string)$media->description;
                    } else {
                        $descripcionRaw = (string)$item->description;
                    }

                    $fPubli = strtotime($item->pubDate);
                    $new_fPubli = date('Y-m-d', $fPubli);

                    $titulo = mysqli_real_escape_string($link, $item->title);
                    $desc   = mysqli_real_escape_string($link, $descripcionRaw);
                    $cat    = mysqli_real_escape_string($link, $categoriaParaGuardar);
                    $guid   = mysqli_real_escape_string($link, $item->guid);

                    $sql = "INSERT INTO elmundo VALUES(NULL, '$titulo', '$enlace', '$desc', '$cat', '$new_fPubli', '$guid')";
                    mysqli_query($link, $sql);
                }
            }
        }
    }
}
?>