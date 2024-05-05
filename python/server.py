from concurrent import futures
import logging

import grpc
from gen import response_pb2, service_pb2_grpc
from google.protobuf.timestamp_pb2 import Timestamp
from datetime import datetime
import math


class HelloService(service_pb2_grpc.HelloServiceServicer):
    def SayHello(self, request, context):
        response_time = Timestamp()
        response_time.FromDatetime(datetime.now())
        info = response_pb2.ClientResponseInfo(
            request_time=Timestamp(
                seconds=request.request_info.timestamp.seconds,
                nanos=request.request_info.timestamp.nanos,
            ),
            response_time=response_time,
            language="Python",
        )
        return response_pb2.HelloResponse(
            message=f"Hello from python, {request.name}!",
            response_info=info,
        )


class CalculatorService(service_pb2_grpc.CalculatorServiceServicer):
    def Calculate(self, request, context):
        response_time = Timestamp()
        response_time.FromDatetime(datetime.now())

        info = response_pb2.ClientResponseInfo(
            request_time=Timestamp(
                seconds=request.request_info.timestamp.seconds,
                nanos=request.request_info.timestamp.nanos,
            ),
            response_time=response_time,
            language="Python",
        )
        return response_pb2.CalculatorResponse(
            addition=request.a + request.b,
            subtraction=request.a - request.b,
            multiplication=request.a * request.b,
            division=request.a / request.b if request.b != 0 else 0,
            power=request.a ** request.b,
            mod=request.a % request.b,
            sqrtA=math.sqrt(request.a) if request.a >= 0 else 0,
            sqrtB=math.sqrt(request.b) if request.b >= 0 else 0,
            factorialA=math.gamma(request.a + 1),
            factorialB=math.gamma(request.b + 1),
            response_info=info,
        )


def serve():
    port = 50051
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    service_pb2_grpc.add_HelloServiceServicer_to_server(HelloService(), server)
    service_pb2_grpc.add_CalculatorServiceServicer_to_server(
        CalculatorService(), server
    )
    server.add_insecure_port(f"[::]:{port}")
    server.start()
    print(f"Server started on port {port}")
    server.wait_for_termination()


if __name__ == "__main__":
    logging.basicConfig()
    serve()
