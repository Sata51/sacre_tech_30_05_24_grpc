<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Service;

/**
 */
class CalculatorServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Service\CalculatorRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Calculate(\Service\CalculatorRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/service.CalculatorService/Calculate',
        $argument,
        ['\Service\CalculatorResponse', 'decode'],
        $metadata, $options);
    }

}
