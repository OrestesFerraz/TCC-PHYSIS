<?php
session_start();
require 'authentication.php';

session_destroy();

header("Location: ../index.php")
?>