<?php

use Pinata\Pinata;

include 'vendor/autoload.php';

$apiKey = 'x';
$secretKey = 'y';
$pinata = new Pinata($apiKey, $secretKey);
//$hash = $pinata->pinJSONToIPFS(['test' => 'moo2']);
// $hash = $pinata->removePinFromIPFS('QmT7Ce9iW9P8ATw2y5ZSYdqhrKEwZify6DPUT9DJVXYutB');
// $hash = $pinata->removePinFromIPFS('QmT7Ce9iW9P8ATw2y5ZSYdqhrKEwZify6DPUT9DJVXYutB');
// $hash = $pinata->pinHashToIPFS('QmT7Ce9iW9P8ATw2y5ZSYdqhrKEwZify6DPUT9DJVXYutB');
print_r($hash);