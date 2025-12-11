<?php
require_once __DIR__ . '/conexionRSS.php';
require_once __DIR__ . '/conexionBBDD.php';

$urlFeed = "https://feeds.elpais.com/mrss-s/pages/ep/site/elpais.com/section/espana/portada";
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

            $checkSQL = "SELECT link FROM elpais WHERE link = '$enlace' LIMIT 1";
            $checkResult = mysqli_query($link, $checkSQL);

            if (mysqli_num_rows($checkResult) == 0) {
                
                $content = $item->children("content", true);
                $encoded = isset($content->encoded) ? (string)$content->encoded : "";
                
                $descripcionFinal = !empty($encoded) ? $encoded : (string)$item->description;
                $descripcionLimpia = strip_tags($descripcionFinal);
                if (strlen($descripcionLimpia) > 400) $descripcionLimpia = substr($descripcionLimpia, 0, 400) . "...";

                $fPubli = strtotime($item->pubDate);
                $new_fPubli = date('Y-m-d', $fPubli);

                $titulo = mysqli_real_escape_string($link, $item->title);
                $desc   = mysqli_real_escape_string($link, $descripcionLimpia);
                $cat    = mysqli_real_escape_string($link, $categoriaParaGuardar);
                
                $guid_contenido = mysqli_real_escape_string($link, $item->guid);

                $sql = "INSERT INTO elpais (cod, titulo, link, descripcion, categoria, fPubli, contenido) 
                        VALUES (NULL, '$titulo', '$enlace', '$desc', '$cat', '$new_fPubli', '$guid_contenido')";
                
                mysqli_query($link, $sql);
            }
        }
    }
}
?>