<?php
namespace Codeages\Weblib\Auth;

class TokenFactory
{
    /**
     *
     * @param $strategy
     *
     * @return Token
     */
    public function factory($strategy)
    {
        $class = __NAMESPACE__ . "\\" . ucfirst($strategy).'Token';

        return new $class();
    }
}