<?php

$password = 'sample';
$passwordHashed = '$2y$12$bU0Tom3knZdKKgd1Qt5VpOYfwf418IeeqWbNzaHiRu2m4rXTYwI3a';

$a1 = 'sample';
$b1 = '$2y$12$PeuWJcEeoOXtc5wKU7x1..PeaqGOXcQMrDJ1Q27K/r264GWqafCs2';

$a2 = 'sample';
$b2 = '$2y$12$Wgy1sVpWCx.7HYC87.EZJObfXSc3MeRg7HzIx/yhgvk1qRjcZ9pvK';

$a3 = 'sample';
$b3 = '$2y$12$08URDRqoSnL4a5SIQPZqoerW9oucfYwDGiBo94/wmTdcBR9271bRK';

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
