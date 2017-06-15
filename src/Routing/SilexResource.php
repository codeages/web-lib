<?php

namespace Codeages\Weblib\Routing;

use Biz\AppBiz;
use Silex\Application;

abstract class SilexResource
{
    use ResourceTrait;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var AppBiz
     */
    protected $biz;

    public function setApp(Application $app)
    {
        $this->app = $app;
    }

    public function setBiz(AppBiz $biz)
    {
        $this->biz = $biz;
    }

    protected function getResource($name)
    {
        return $this->app[__NAMESPACE__.'\\'.$name];
    }
}
