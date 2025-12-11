<?php
require_once __DIR__ . '/conexionRSS.php';
require_once __DIR__ . '/conexionBBDD.php';

$urls_elmundo = [
    "https://e00-elmundo.uecdn.es/elmundo/rss/portada.xml",
    "https://e00-elmundo.uecdn.es/elmundo/rss/espana.xml",
    "https://e00-elmundo.uecdn.es/elmundo/rss/internacional.xml",
    "https://e00-elmundo.uecdn.es/elmundo/rss/economia.xml",
    "https://e00-elmundo.uecdn.es/elmundo/rss/cultura.xml",
    "https://e00-elmundo.uecdn.es/elmundo/rss/deportes.xml" 
];

$misFiltros = [
    "Política", "Politica", "Gobierno",
    "Deportes", "Sport", "Fútbol", 
    "Ciencia", 
    "España", "Nacional", "Interior", 
    "Economía", "Economia", "Bolsa",
    "Música", "Musica", "Concierto", 
    "Cine", "Película", 
    "Europa", "Internacional", "Mundo",
    "Justicia", "Tribunales", 
    "Cultura", "Sociedad"
];

foreach ($urls_elmundo as $urlFeed) {

    $sXML = download($urlFeed);

    if (strpos($sXML, '<error>') === false && !empty($sXML)) {
        
        $oXML = new SimpleXMLElement($sXML);

        if ($link) {
            foreach ($oXML->channel->item as $item) {
                
                $categoriaParaGuardar = "";
                
                foreach ($item->category as $catXML) {
                    $catLimpia = trim((string)$catXML);
                    
                    foreach ($misFiltros as $filtro) {
                        if (mb_stripos($catLimpia, $filtro) !== false) {
                            $etiquetaFinal = ucfirst($filtro); 
                            if ($etiquetaFinal == "Politica") $etiquetaFinal = "Política";
                            if ($etiquetaFinal == "Musica") $etiquetaFinal = "Música";
                            
                            if (strpos($categoriaParaGuardar, "[" . $etiquetaFinal . "]") === false) {
                                $categoriaParaGuardar .= "[" . $etiquetaFinal . "]";
                            }
                        }
                    }
                }
                if ($categoriaParaGuardar == "") {
                    if (strpos($urlFeed, 'deportes') !== false) $categoriaParaGuardar = "[Deportes]";
                    elseif (strpos($urlFeed, 'economia') !== false) $categoriaParaGuardar = "[Economía]";
                    elseif (strpos($urlFeed, 'cultura') !== false) $categoriaParaGuardar = "[Cultura]";
                    elseif (strpos($urlFeed, 'espana') !== false) $categoriaParaGuardar = "[España]";
                    elseif (strpos($urlFeed, 'internacional') !== false) $categoriaParaGuardar = "[Internacional]";
                    else $categoriaParaGuardar = "[General]";
                }

                
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

                    $sql = "INSERT INTO elmundo (cod, titulo, link, descripcion, categoria, fPubli, guid) 
                            VALUES (NULL, '$titulo', '$enlace', '$desc', '$cat', '$new_fPubli', '$guid')";
                    
                    if(mysqli_query($link, $sql)){
                    }
                }
            }
        }
    }
}
echo "Proceso de El Mundo finalizado.";
?>