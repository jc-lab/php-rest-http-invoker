<?php
/**
 * User: jichan (development@jc-lab.net)
 * Date: 2019-02-28
 * Time: 오전 11:52
 */

namespace jclab\PhpRestHttpInvoker;

class RestHttpInvokerProxyFactory {
    private $serviceUrl;
    private $serviceInterfaceName;
    private $serviceInterfaceRef;
    private $defaultHeaders = null;

    public function setServiceUrl($url) {
        $this->serviceUrl = $url;
    }

    public function setServiceInterface($interface) {
        $this->serviceInterfaceName = $interface;
        $this->serviceInterfaceRef = new \ReflectionClass($interface);
    }

    public function addMethod($name, $argTypes) {

    }

    public function getProxyObject() {
        foreach($this->serviceInterfaceRef->getMethods() as $method) {

        }
        return new ProxyObject($this);
    }

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function doExecute($methodName, $args) {
        $api = new \RestClient();
        $headers = $this->defaultHeaders;
        if(!$headers)
            $headers = array();
        $headers['Content-Type'] = "application/json; charset=utf-8";

        $remoteInvocation = array(
            "methodName" => $methodName,
            "parameterTypes" => [],
            "arguments" => $args ? $args : [],
            "attributes" => null
        );

        if($args) {
            for ($i = 0, $count = count($args); $i < $count; $i++) {
                $type = \gettype($args[$i]);
                switch($type) {
                    case 'integer':
                        $type = 'int';
                        break;
                    case 'string':
                        $type = 'java.lang.String';
                        break;
                    case 'array':
                        if(self::isAssoc($args[$i])) {
                            $type = 'java.util.Map';
                        }else{
                            $type = 'java.util.List';
                        }
                        break;
                }
                $remoteInvocation['parameterTypes'][] = $type;
            }
        }

        $result = $api->execute($this->serviceUrl, 'POST', json_encode($remoteInvocation), $headers);
        $response = $result->decode_response();
        if($result->info->http_code == 200) {
            if($response->exception) {
                throw new Exception\RemoteRuntimeException($response->exception);
            }else{
                return $response->value;
            }
        }
    }
}
