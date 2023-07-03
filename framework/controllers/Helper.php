<?php

declare(strict_types=1);

namespace Web\Controllers;

class Helper {

  public static function Nix($str) {
      if (!empty($str)) {
         $str = str_replace('\\', '/', $str);
         $str = str_replace(':/', ':\\', $str);
         $str = rtrim(trim($str), '/');
     }
     return $str;
  }

  public static function Decrypt($data, $key) {
      $data = base64_decode($data);
      if (substr($data, 0, 8) != "Salted__") {
        return false;
      }
      $salt = substr($data, 8, 8);
      $keyAndIV = static::AesEvpKDF($key, $salt);
      $decryptPassword = openssl_decrypt(
        substr($data, 16),
        "aes-256-cbc",
        $keyAndIV["key"],
        OPENSSL_RAW_DATA,
        $keyAndIV["iv"]
      );
      return $decryptPassword;
  }

  public static function Encrypt($data, $key) {
      $salted = "Salted__";
      $salt = openssl_random_pseudo_bytes(8);
      $keyAndIV = static::AesEvpKDF($key, $salt);
      $encrypt  = openssl_encrypt(
        $data,
        "aes-256-cbc",
        $keyAndIV["key"],
        OPENSSL_RAW_DATA,
        $keyAndIV["iv"]
      );
      return base64_encode($salted . $salt . $encrypt);
  }

  static function AesEvpKDF($password,
    $salt, $keySize = 8, $ivSize = 4, $iterations = 1,
    $hashAlgorithm = "md5") {
    $targetKeySize = $keySize + $ivSize;
    $derivedBytes = "";
    $numberOfDerivedWords = 0;
    $block = NULL;
    $hasher = hash_init($hashAlgorithm);
    while ($numberOfDerivedWords < $targetKeySize) {
        if ($block != NULL) {
            hash_update($hasher, $block);
        }
        hash_update($hasher, $password);
        hash_update($hasher, $salt);
        $block = hash_final($hasher, TRUE);
        $hasher = hash_init($hashAlgorithm);
        for ($i = 1; $i < $iterations; $i++) {
            hash_update($hasher, $block);
            $block = hash_final($hasher, TRUE);
            $hasher = hash_init($hashAlgorithm);
        }
        $derivedBytes .= substr($block, 0, min(strlen($block), ($targetKeySize - $numberOfDerivedWords) * 4));
        $numberOfDerivedWords += strlen($block) / 4;
    }
    return array(
        "key" => substr($derivedBytes, 0, $keySize * 4),
        "iv"  => substr($derivedBytes, $keySize * 4, $ivSize * 4)
    );
  }

}
