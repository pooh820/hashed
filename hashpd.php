<?php
$hashed_password = password_hash("test123", PASSWORD_DEFAULT);
echo $hashed_password;
