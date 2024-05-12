<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Service;

/**
 */
class CalculatorServiceStub {

    /**
     * @param \Service\CalculatorRequest $request client request
     * @param \Grpc\ServerContext $context server request context
     * @return \Service\CalculatorResponse for response data, null if if error occured
     *     initial metadata (if any) and status (if not ok) should be set to $context
     */
    public function Calculate(
        \Service\CalculatorRequest $request,
        \Grpc\ServerContext $context
    ): ?\Service\CalculatorResponse {
        $context->setStatus(\Grpc\Status::unimplemented());
        return null;
    }

    /**
     * Get the method descriptors of the service for server registration
     *
     * @return array of \Grpc\MethodDescriptor for the service methods
     */
    public final function getMethodDescriptors(): array
    {
        return [
            '/service.CalculatorService/Calculate' => new \Grpc\MethodDescriptor(
                $this,
                'Calculate',
                '\Service\CalculatorRequest',
                \Grpc\MethodDescriptor::UNARY_CALL
            ),
        ];
    }

}
