<?php
$test_password = 'test123';
$test_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
var_dump(password_verify($test_password, $test_hash));
