from __future__ import print_function

import logging

import grpc
from gen import request_pb2, service_pb2_grpc


def run():
    with grpc.insecure_channel('localhost:50051') as channel:
        hello_stub = service_pb2_grpc.HelloServiceStub(channel)
        hello_response = hello_stub.SayHello(request_pb2.HelloRequest(name='Sata'))

        calc_stub = service_pb2_grpc.CalculatorServiceStub(channel)
        calc_response = calc_stub.Calculate(request_pb2.CalculatorRequest(a=22, b=31))
    print('HelloResponse: %s' % hello_response.message)
    print('CalculatorResponse:')
    print('  addition:', calc_response.addition)
    print('  subtraction:', calc_response.subtraction)
    print('  multiplication:', calc_response.multiplication)
    print('  division:', calc_response.division)


if __name__ == '__main__':
    logging.basicConfig()
    run()