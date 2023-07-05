<?php

declare(strict_types=1);

namespace Utility;

use Libraries\BaseController;

class Encrypter extends BaseController
{
    /**
     * @param $data
     * @param $key
     * @return string
     */
  public function encryptArray(array $data, $key = ';alkjdf83io8'): string
  {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encryptedData = openssl_encrypt(json_encode($data), 'aes-256-cbc', $key, 0, $iv);
    $result = ['iv' => base64_encode($iv), 'data' => $encryptedData];
    return base64_encode(json_encode($result));
  }

    /**
     * @param $encryptedData
     * @param $key
     * @return array
     */
  public function decryptArray($encryptedData, $key = ';alkjdf83io8'): array
  {
    $data = json_decode(base64_decode($encryptedData), true);
    $decryptedData = openssl_decrypt($data['data'], 'aes-256-cbc', $key, 0, base64_decode($data['iv']));
    return json_decode($decryptedData, true);
  }
}
