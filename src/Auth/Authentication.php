<?php

namespace Codeages\Weblib\Auth;

use Codeages\Weblib\Error\ErrorCode;

class Authentication
{
    /**
     * @var KeyProvider
     */
    protected $keyProvider;

    /**
     * @var TokenFactory
     */
    protected $tokenFactory;

    protected $tokenHeaderKey = 'Authorization';

    public function __construct(KeyProvider $keyProvider, TokenFactory $tokenFactory = null,  array $options = array())
    {
        $this->keyProvider = $keyProvider;

        if (empty($tokenFactory)) {
            $tokenFactory = new TokenFactory();
        }
        $this->tokenFactory = $tokenFactory;

        if (isset($options['token_header_key'])) {
            $this->tokenHeaderKey = $options['token_header_key'];
        }
    }

    public function auth($request)
    {
        $header = $this->getTokenHeader($request);
        $header = explode(' ', $header);
        if (count($header) !== 2) {
            throw new AuthException("Authorization header is invalid.", ErrorCode::INVALID_CREDENTIAL);
        }

        list($strategy, $token) = $header;

        $strategy = $this->tokenFactory->factory($strategy);

        $token = $strategy->parse($token);

        $key = $this->keyProvider->get($token['key_id']);

        if (empty($key)) {
            throw new AuthException("Key id is not exist.", ErrorCode::INVALID_CREDENTIAL);
        }

        $strategy->check($token, $key['key_secret'], $this->getRequestText($request));

        if ($key['status'] == 'inactive') {
            throw new AuthException("Key is banned.", ErrorCode::BANNED_CREDENTIALS);
        }

        if ($key['status'] == 'deleted') {
            throw new AuthException("Key is deleted.", ErrorCode::BANNED_CREDENTIALS);
        }

        if ($key['expired_time'] > 0 && $key['expired_time'] < time()) {
            throw new AuthException("Key is expired.", ErrorCode::EXPIRED_CREDENTIAL);
        }

        return $key;
    }

    public function getTokenHeader($request)
    {
        if ($request instanceof \Phalcon\Http\Request) {
            return $request->getHeader('Authorization');
        } elseif ($request instanceof \Symfony\Component\HttpFoundation\Request) {
            return $request->headers->get('Authorization');
        }
        throw new \InvalidArgumentException("Request class is not supported.");
    }

    public function getRequestText($request)
    {
        if ($request instanceof \Phalcon\Http\Request) {
            $uri = $request->getURI();
            $body = $request->getRawBody();
        } elseif ($request instanceof \Symfony\Component\HttpFoundation\Request) {
            $uri = $request->getRequestUri();
            $body = $request->getContent();
        } else {
            throw new \InvalidArgumentException("Request class is not supported.");
        }

        return "{$uri}\n{$body}";
    }
}