package main

import (
	"context"

	pb "github.com/Sata51/sacre_tech_30_05_24_grpc/go/gen/service"
)

type helloServiceServer struct {
	pb.UnimplementedHelloServiceServer
}

func newHelloServer() *helloServiceServer {
	s := &helloServiceServer{}
	return s
}

func (h *helloServiceServer) SayHello(ctx context.Context, req *pb.HelloRequest) (*pb.HelloResponse, error) {
	return &pb.HelloResponse{
		Name: "Hello " + req.GetName(),
	}, nil
}