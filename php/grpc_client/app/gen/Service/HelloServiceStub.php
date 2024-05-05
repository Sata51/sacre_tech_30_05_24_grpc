<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Service;

/**
 */
class HelloServiceStub {

    /**
     * @param \Service\HelloRequest $request client request
     * @param \Grpc\ServerContext $context server request context
     * @return \Service\HelloResponse for response data, null if if error occured
     *     initial metadata (if any) and status (if not ok) should be set to $context
     */
    public function SayHello(
        \Service\HelloRequest $request,
        \Grpc\ServerContext $context
    ): ?\Service\HelloResponse {
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
            '/service.HelloService/SayHello' => new \Grpc\MethodDescriptor(
                $this,
                'SayHello',
                '\Service\HelloRequest',
                \Grpc\MethodDescriptor::UNARY_CALL
            ),
        ];
    }

}
