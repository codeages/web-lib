<?php
namespace Codeages\Weblib\Auth;

interface Token
{
    public function parse($token);

    public function check($token, $secretKey = '', $signingText = '');
}
