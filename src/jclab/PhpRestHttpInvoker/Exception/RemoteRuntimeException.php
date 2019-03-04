<?php
/**
 * User: jichan (development@jc-lab.net)
 * Date: 2019-02-28
 * Time: 오후 2:07
 */

namespace jclab\PhpRestHttpInvoker\Exception;

class RemoteRuntimeException extends \Exception
{
    private $javaClassName;
    private $data;

    public function __construct($javaClassName, $data)
    {
        parent::__construct($data->message, 0);
        $this->javaClassName = $javaClassName;
        $this->data = $data;
    }

    public function getJavaClassName() {
        return $this->javaClassName;
    }

    public function getData() {
        return $this->data;
    }
}
