<?php

namespace Codeages\Weblib\Auth;

use Codeages\Weblib\Error\ErrorCode;

class SignatureToken implements Token
{
    public function parse($token)
    {
        $token = explode(':', $token);
        if (count($token) !== 2 || empty($token[0]) || empty($token[1])) {
            throw new AuthException('Auth token format is invalid.', ErrorCode::INVALID_CREDENTIAL);
        }

        $token = array(
            'key_id' => $token[0],
            'signature' => $token[1],
        );

        return $token;
    }

    public function check($token, $secretKey = '', $signingText = '')
    {
        $signature = hash_hmac('sha1', $signingText, $secretKey, true);
        $signature = str_replace(array('+', '/'), array('-', '_'), base64_encode($signature));

        if (empty($token['signature']) || $token['signature'] != $signature) {
            throw new AuthException("Signature is invalid.", ErrorCode::INVALID_CREDENTIAL);
        }

        return true;
    }
}