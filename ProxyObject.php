<?php
/**
 * User: jichan (development@jc-lab.net)
 * Date: 2019-02-28
 * Time: 오전 11:52
 */

namespace jclab;

class ProxyObject
{
    private $factory;

    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    public function __call($methodName, $args) {
        return $this->factory->doExecute($methodName, $args);
    }
}
