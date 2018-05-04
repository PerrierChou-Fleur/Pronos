<?php
 #test.php#

echo $_GET['param']."<br>";
echo $_SERVER['REQUEST_URI']."<br>";
echo __FILE__."<br>";
echo $_SERVER['PATH_TRANSLATED']."<br>";
echo $_SERVER['PATH_INFO']."<br>";
echo $_SERVER['ORIG_PATH_INFO']."<br>";
?>