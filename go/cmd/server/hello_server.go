package main

import (
	"context"
	"fmt"

	pb "github.com/Sata51/sacre_tech_30_05_24_grpc/go/gen/service"
	"google.golang.org/protobuf/types/known/timestamppb"
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
		Message: fmt.Sprintf("Hello from go, %s", req.GetName()),
		ResponseInfo: &pb.ClientResponseInfo{
			RequestTime:  req.GetRequestInfo().GetTimestamp(),
			ResponseTime: timestamppb.Now(),
			Language:     "go",
		},
	}, nil
}
