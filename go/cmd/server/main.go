package main

import (
	"flag"
	"fmt"
	"net"

	pb "github.com/Sata51/sacre_tech_30_05_24_grpc/go/gen/service"
	"google.golang.org/grpc"
)

var port = flag.Int("port", 50051, "The server port")

func main() {
	flag.Parse()

	lis, err := net.Listen("tcp", fmt.Sprintf(":%d", *port))
	if err != nil {
		panic(err)
	}
	var opts []grpc.ServerOption
	grpcServer := grpc.NewServer(opts...)
	pb.RegisterHelloServiceServer(grpcServer, newHelloServer())
	pb.RegisterCalculatorServiceServer(grpcServer, newCalculatorServer())
	grpcServer.Serve(lis)

}
