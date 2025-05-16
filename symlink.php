
<?php
 $target = $_SERVER['DOCUMENT_ROOT'] . '/zopa/storage/app/public';
 $link = $_SERVER['DOCUMENT_ROOT'] . '/zopa/public/storage';





 symlink($target, $link);
?>
