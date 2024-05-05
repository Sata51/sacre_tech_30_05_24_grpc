from __future__ import print_function

import logging

import grpc
from gen import request_pb2, service_pb2_grpc
from google.protobuf.timestamp_pb2 import Timestamp
from datetime import datetime


def run():
    with grpc.insecure_channel("localhost:50051") as channel:
        hello_stub = service_pb2_grpc.HelloServiceStub(channel)
        hello_ts = Timestamp()
        hello_ts.FromDatetime(datetime.now())
        hello_info = request_pb2.ClientRequestInfo(
            timestamp=hello_ts
        )
        hello_response = hello_stub.SayHello(
            request_pb2.HelloRequest(name="Sata", request_info=hello_info)
        )

        calc_stub = service_pb2_grpc.CalculatorServiceStub(channel)
        calc_ts = Timestamp()
        calc_ts.FromDatetime(datetime.now())
        calc_info = request_pb2.ClientRequestInfo(
            timestamp=calc_ts
        )
        calc_response = calc_stub.Calculate(
            request_pb2.CalculatorRequest(a=22, b=31, request_info=calc_info)
        )
    print("From language: %s" % hello_response.response_info.language)
    print("HelloResponse: %s" % hello_response.message)
    print(
        f"Elapsed time: {hello_response.response_info.response_time.ToMilliseconds() - hello_response.response_info.request_time.ToMilliseconds()} ms"
    )

    print("CalculatorResponse:")
    print("From language: %s" % hello_response.response_info.language)
    print("  addition:", calc_response.addition)
    print("  subtraction:", calc_response.subtraction)
    print("  multiplication:", calc_response.multiplication)
    print("  division:", calc_response.division)
    print("  power:", calc_response.power)
    print("  mod:", calc_response.mod)
    print("  Square root of A:", calc_response.sqrtA)
    print("  Square root of B:", calc_response.sqrtB)
    print("  Factorial A:", calc_response.factorialA)
    print("  Factorial B:", calc_response.factorialB)
    print(
        f"Elapsed time: {calc_response.response_info.response_time.ToMilliseconds() - calc_response.response_info.request_time.ToMilliseconds()} ms"
    )


if __name__ == "__main__":
    logging.basicConfig()
    run()
