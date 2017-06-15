<?php

namespace Codeages\Weblib\Auth;

use Codeages\Weblib\Error\ErrorCode;

class SecretToken implements Token
{
    public function parse($token)
    {
        $token = explode(':', $token);
        if (count($token) !== 2) {
            throw new AuthException('Auth token format is invalid.', ErrorCode::INVALID_CREDENTIAL);
        }

        $token = array(
            'key_id' => $token[0],
            'key_secret' => $token[1],
        );

        return $token;
    }

    public function check($token, $secretKey = '', $signingText = '')
    {
        if ($token['key_secret'] != $secretKey) {
            throw new AuthException("Secret key is invalid.", ErrorCode::INVALID_CREDENTIAL);
        }

        return true;
    }
}