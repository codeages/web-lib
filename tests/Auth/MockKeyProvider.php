<?php

namespace Codeages\Weblib\Auth;

class MockKeyProvider implements KeyProvider
{
    public function get($id)
    {
        if (!isset($keys[$id])) {
            return null;
        }

        $keys = array(
            'key1' => 'test_key_1',
            'key2' => 'test_secret',
        );

        return $keys[$id];
    }
}