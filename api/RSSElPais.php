<?php
require_once __DIR__ . '/conexionRSS.php';
require_once __DIR__ . '/conexionBBDD.php';

$sXML = download("http://ep00.epimg.net/rss/elpais/portada.xml");

if (strpos($sXML, '<error>') !== false || empty($sXML)) {
    return;
}

$oXML = new SimpleXMLElement($sXML);

if ($link) {
    $categoria = ["Política", "Deportes", "Ciencia", "España", "Economía", "Música", "Cine", "Europa", "Justicia"];

    foreach ($oXML->channel->item as $item) {
        $categoriaFiltro = "";

        for ($i = 0; $i < count($item->category); $i++) {
            for ($j = 0; $j < count($categoria); $j++) {
                if ($item->category[$i] == $categoria[$j]) {
                    $categoriaFiltro = "[" . $categoria[$j] . "]" . $categoriaFiltro;
                }
            }
        }

        $fPubli = strtotime($item->pubDate);
        $new_fPubli = date('Y-m-d', $fPubli);

        $content = $item->children("content", true);
        $encoded = $content->encoded;

        $Repit = false;

        $sql = "SELECT link FROM elpais";
        $result = mysqli_query($link, $sql);

        if ($result) {
            while ($sqlCompara = mysqli_fetch_array($result)) {
                if ($sqlCompara['link'] == $item->link) {
                    $Repit = true;
                    break;
                }
            }
        }

        if ($Repit == false && $categoriaFiltro != "") {
            
            $titulo = mysqli_real_escape_string($link, $item->title);
            $enlace = mysqli_real_escape_string($link, $item->link);
            $desc   = mysqli_real_escape_string($link, $item->description);
            $cont   = mysqli_real_escape_string($link, $encoded);
            $cat    = mysqli_real_escape_string($link, $categoriaFiltro);

            $sql = "INSERT INTO elpais VALUES(NULL, '$titulo', '$enlace', '$desc', '$cat', '$new_fPubli', '$cont')";
            mysqli_query($link, $sql);
        }
    }
}
?>