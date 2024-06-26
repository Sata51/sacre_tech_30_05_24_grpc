<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: request.proto

namespace Service;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>service.CalculatorRequest</code>
 */
class CalculatorRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>float a = 1;</code>
     */
    protected $a = 0.0;
    /**
     * Generated from protobuf field <code>float b = 2;</code>
     */
    protected $b = 0.0;
    /**
     * Generated from protobuf field <code>.service.ClientRequestInfo request_info = 3;</code>
     */
    protected $request_info = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type float $a
     *     @type float $b
     *     @type \Service\ClientRequestInfo $request_info
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Request::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>float a = 1;</code>
     * @return float
     */
    public function getA()
    {
        return $this->a;
    }

    /**
     * Generated from protobuf field <code>float a = 1;</code>
     * @param float $var
     * @return $this
     */
    public function setA($var)
    {
        GPBUtil::checkFloat($var);
        $this->a = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>float b = 2;</code>
     * @return float
     */
    public function getB()
    {
        return $this->b;
    }

    /**
     * Generated from protobuf field <code>float b = 2;</code>
     * @param float $var
     * @return $this
     */
    public function setB($var)
    {
        GPBUtil::checkFloat($var);
        $this->b = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.service.ClientRequestInfo request_info = 3;</code>
     * @return \Service\ClientRequestInfo|null
     */
    public function getRequestInfo()
    {
        return $this->request_info;
    }

    public function hasRequestInfo()
    {
        return isset($this->request_info);
    }

    public function clearRequestInfo()
    {
        unset($this->request_info);
    }

    /**
     * Generated from protobuf field <code>.service.ClientRequestInfo request_info = 3;</code>
     * @param \Service\ClientRequestInfo $var
     * @return $this
     */
    public function setRequestInfo($var)
    {
        GPBUtil::checkMessage($var, \Service\ClientRequestInfo::class);
        $this->request_info = $var;

        return $this;
    }

}

