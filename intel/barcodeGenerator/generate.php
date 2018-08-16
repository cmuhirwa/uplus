<?php
require "vendor/autoload.php";
$Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
$code = $Bar->getBarcode($_GET['text'], $Bar::TYPE_CODE_128);
echo $code;
?>