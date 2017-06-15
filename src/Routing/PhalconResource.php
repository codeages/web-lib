<?php

namespace Codeages\Weblib\Routing;

use Phalcon\Mvc\Controller;

abstract class PhalconResource extends Controller
{
    use ResourceTrait;

    public function getResource($name)
    {
        // TODO: Implement getResource() method.
    }

}