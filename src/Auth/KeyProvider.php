<?php

namespace Codeages\Weblib\Auth;

interface KeyProvider
{
    public function get($id);
}