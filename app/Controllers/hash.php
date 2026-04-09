<?php
$password = "zai";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword;