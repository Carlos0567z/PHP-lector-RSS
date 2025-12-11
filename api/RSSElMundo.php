<?php
require_once __DIR__ . '/conexionRSS.php';
require_once __DIR__ . '/conexionBBDD.php';

$urlFeed = "https://e00-elmundo.uecdn.es/elmundo/rss/espana.xml";
$sXML = download($urlFeed);

if (strpos($sXML, '<error>') === false && !empty($sXML)) {
    
    $oXML = new SimpleXMLElement($sXML);

    if ($link) {
        $misFiltros = [
            "Política", "Politica", "Gobierno",
            "Deportes", "Sport", 
            "España", "Nacional", 
            "Economía", "Economia", 
            "Música", "Musica", 
            "Cine", "Cultura"
        ];

        foreach ($oXML->channel->item as $item) {
            $categoriaParaGuardar = "";
            
            $categoriaParaGuardar .= "[España]";

            foreach ($item->category as $catXML) {
                $catLimpia = trim((string)$catXML);
                foreach ($misFiltros as $filtro) {
                    if (mb_stripos($catLimpia, $filtro) !== false) {
                        $etiquetaFinal = ucfirst($filtro); 
                        if ($etiquetaFinal == "Politica") $etiquetaFinal = "Política";
                        if ($etiquetaFinal == "Economia") $etiquetaFinal = "Economía";
                        if ($etiquetaFinal == "Musica") $etiquetaFinal = "Música";

                        if (strpos($categoriaParaGuardar, "[" . $etiquetaFinal . "]") === false) {
                            $categoriaParaGuardar .= "[" . $etiquetaFinal . "]";
                        }
                    }
                }
            }

            $enlace = mysqli_real_escape_string($link, $item->link);

            $checkSQL = "SELECT link FROM elmundo WHERE link = '$enlace' LIMIT 1";
            $checkResult = mysqli_query($link, $checkSQL);

            if (mysqli_num_rows($checkResult) == 0) {
                
                $media = $item->children("media", true);
                $descripcionRaw = (isset($media->description)) ? (string)$media->description : (string)$item->description;
                
                $fPubli = strtotime($item->pubDate);
                $new_fPubli = date('Y-m-d', $fPubli);

                $titulo = mysqli_real_escape_string($link, $item->title);
                $desc   = mysqli_real_escape_string($link, $descripcionRaw);
                $cat    = mysqli_real_escape_string($link, $categoriaParaGuardar);
                
                $guid_contenido = mysqli_real_escape_string($link, $item->guid);

                $sql = "INSERT INTO elmundo (cod, titulo, link, descripcion, categoria, fPubli, contenido) 
                        VALUES (NULL, '$titulo', '$enlace', '$desc', '$cat', '$new_fPubli', '$guid_contenido')";
                
                mysqli_query($link, $sql);
            }
        }
    }
}
?>