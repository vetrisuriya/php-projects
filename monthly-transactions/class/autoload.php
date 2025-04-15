<?php

spl_autoload_register(function($val) {
    require "./class/".$val.".php";
});