<?php

// Autoload to /classes
spl_autoload_register(function ($className) {
    require_once("classes/$className.php");
});

?>