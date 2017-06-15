<?php
namespace Codeages\Weblib\Auth;

use PHPUnit\Framework\TestCase;

class SignatureTokenTest extends TestCase
{
    public function testParse_GoodToken()
    {
        $strategy = new SignatureToken();
        $token = $strategy->parse('test_key_id:test_signature');

        $this->assertEquals('test_key_id', $token['key_id']);
        $this->assertEquals('test_signature', $token['signature']);
    }

    /**
     * @expectedException \Codeages\Weblib\Auth\AuthException
     */
    public function testParse_badTokenFormat1()
    {
        $strategy = new SignatureToken();
        $strategy->parse('test_key_id');
    }

    /**
     * @expectedException \Codeages\Weblib\Auth\AuthException
     */
    public function testParse_badTokenFormat2()
    {
        $strategy = new SignatureToken();
        $strategy->parse('test_key_id:');
    }

    /**
     * @expectedException \Codeages\Weblib\Auth\AuthException
     */
    public function testParse_badTokenFormat3()
    {
        $strategy = new SignatureToken();
        $strategy->parse('test_key_id:test_signature:other');
    }

    public function testCheck_Success()
    {
        $signingText = "/me?t1=1&t2=2\n{\"test\":\"value\"}";

        $key = array(
            'key_id' => 'test_key_id',
            'key_secret' => 'test_key_secret',
        );

        $token = array(
            'key_id' => 'test_key_id',
            'signature' => $this->makeSignature($signingText, $key['key_secret']),
        );

        $strategy = new SignatureToken();
        $checked = $strategy->check($token, $key['key_secret'], $signingText);

        $this->assertTrue($checked);
    }

    /**
     * @expectedException \Codeages\Weblib\Auth\AuthException
     */
    public function testCheck_Failed_ErrorSecretKey()
    {
        $signingText = "/me?t1=1&t2=2\n{\"test\":\"value\"}";

        $key = array(
            'key_id' => 'test_key_id',
            'key_secret' => 'test_key_secret',
        );

        $token = array(
            'key_id' => 'test_key_id',
            'signature' => $this->makeSignature($signingText, 'test_error_secret'),
        );

        $strategy = new SignatureToken();
        $strategy->check($token, $key['key_secret'], $signingText);
    }

    /**
     * @expectedException \Codeages\Weblib\Auth\AuthException
     */
    public function testCheck_Failed_ErrorSigningText()
    {
        $signingText = "/me?t1=1&t2=2\n{\"test\":\"value\"}";

        $key = array(
            'key_id' => 'test_key_id',
            'key_secret' => 'test_key_secret',
        );

        $token = array(
            'key_id' => 'test_key_id',
            'signature' => $this->makeSignature($signingText, $key['key_secret']),
        );

        $strategy = new SignatureToken();
        $checked = $strategy->check($token, $key['key_secret'], '/error');

        $this->assertTrue($checked);

    }

    protected function makeSignature($signingText, $secretKey)
    {
        $signature = hash_hmac('sha1', $signingText, $secretKey, true);
        $signature = str_replace(array('+', '/'), array('-', '_'), base64_encode($signature));

        return $signature;
    }
}