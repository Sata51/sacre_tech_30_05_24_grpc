package main

import (
	"context"

	pb "github.com/Sata51/sacre_tech_30_05_24_grpc/go/gen/service"
)

type calcServiceServer struct {
	pb.UnimplementedCalculatorServiceServer
}

func newCalculatorServer() *calcServiceServer {
	s := &calcServiceServer{}
	return s
}

func (c *calcServiceServer) Calculate(ctx context.Context, req *pb.CalculatorRequest) (*pb.CalculatorResponse, error) {

	var divResult float32
	if req.B == 0 {
		divResult = 0.0
	} else {
		divResult = req.A / req.B
	}

	return &pb.CalculatorResponse{
		Addition:       req.A + req.B,
		Subtraction:    req.A - req.B,
		Multiplication: req.A * req.B,
		Division:       float32(divResult),
	}, nil
}
