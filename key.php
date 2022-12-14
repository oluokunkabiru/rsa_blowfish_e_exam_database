<?php
$keyPair = openssl_pkey_new(array(
    "digest_alg" => 'sha512',
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA
));
// $privateKey = null;

print_r(openssl_pkey_export($keyPair, $privateKey));

// echo sprintf("Private key header: %s\n", current(explode("\n", $privateKey)));


?>