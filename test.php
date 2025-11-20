<?php

$password = 'sample';
$passwordHashed = '$2y$12$bU0Tom3knZdKKgd1Qt5VpOYfwf418IeeqWbNzaHiRu2m4rXTYwI3a';

$a1 = 'Jimmy';
$b1 = '$2y$10$ygDJal6JFy6leH/jojrUZ.ZNnuFEKECtxZE0tnqWvr.8gSRg/jQr.';

$a2 = 'Dark';
$b2 = '$2y$10$QqTWnyzeBr5Djik7qApXRO3.y42lef7AWy50Tc14vLHZWhw4VgXI.';

$a3 = 'Large';
$b3 = '$2y$10$OO23cNiWN3osgjZA0AQRU.d7frNT5qShDnXlQb.3q3N5FlBirt9IG';

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