<?php

namespace Codeages\Weblib\Auth;

class MockKeyProvider implements KeyProvider
{
    public function get($id)
    {
        $keys = array(
            'test_key_id_1' => array(
                'key_id' => 'test_key_id_1',
                'key_secret' => 'test_key_secret_1',
                'status' => 'active',
                'expired_time' => time() + 3600,
            ),
            'test_key_id_2' => array(
                'key_id' => 'test_key_id_2',
                'key_secret' => 'test_key_secret_2',
                'status' => 'active',
                'expired_time' => time() + 3600,
            ),
        );

        if (!isset($keys[$id])) {
            return null;
        }

        return $keys[$id];
    }
}