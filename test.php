<?php

$password = 'sample';
$passwordHashed = '$2y$12$bU0Tom3knZdKKgd1Qt5VpOYfwf418IeeqWbNzaHiRu2m4rXTYwI3a';

$a1 = 'Sample';
$b1 = '$2y$12$mXX7k9iQguCZX.sGcfRHZeA5wxhKe2.ndrrf1CAcBUdKlkC5qaI1.';

$a2 = 'Sample';
$b2 = '$2y$12$grknv5HrfOsd3A7WwidzR.ADQlUyVF1/baMg110iohSwyudzIOo82';

$a3 = 'Sample';
$b3 = '$2y$12$HPNkshuwnejYL915DYw89eNwRCjWB8izeCF5dEUc.jMsm3WN991Ze';

// Array of password/hash pairs
$passwords = [
    ['password' => $a1, 'hash' => $b1],
    ['password' => $a2, 'hash' => $b2],
    ['password' => $a3, 'hash' => $b3]
];

foreach ($passwords as $pair) {
    $password = $pair['password'];
    $passwordHashed = $pair['hash'];
    
    echo "Checking password: '$password'\n";

    if (password_verify($password, $passwordHashed)) {
        echo "Password match\n";
    } else {
        echo "Password does not match\n";
    }
}