<?php
//*Version : 0.0.1


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $file = __DIR__ . '/../configs.ini';
    file_put_contents($file, "[webinfo]\n");
    file_put_contents($file, "siteUrl = " . $_POST['siteUrl'] . "\n", FILE_APPEND);
    file_put_contents($file, "siteName = " . $_POST['siteName'] . "\n", FILE_APPEND);
    file_put_contents($file, "classify = " . $_POST['classify'] . "\n", FILE_APPEND);
    file_put_contents($file, "frompixiv = " . $_POST['frompixiv'] . "\n", FILE_APPEND);
    file_put_contents($file, "compression = " . $_POST['compression'] . "\n", FILE_APPEND);
    file_put_contents($file, "picturelistcache = " . $_POST['picturelistcache'] . "\n", FILE_APPEND);

    file_put_contents($file, "[tokensys]\n", FILE_APPEND);
    file_put_contents($file, "tokenEnable = " . $_POST['tokenEnable'] . "\n", FILE_APPEND);
    file_put_contents($file, "tokenSalt = " . $_POST['tokenSalt'] . "\n", FILE_APPEND);
}



?>

hello,im init.php
