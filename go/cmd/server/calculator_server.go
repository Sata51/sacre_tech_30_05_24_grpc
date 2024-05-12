package main

import (
	"context"
	"math"

	pb "github.com/Sata51/sacre_tech_30_05_24_grpc/go/gen/service"
	"google.golang.org/protobuf/types/known/timestamppb"
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
		Power:          float32(math.Pow(float64(req.A), float64(req.B))),
		Mod:            float32(math.Mod(float64(req.A), float64(req.B))),
		SqrtA:          float32(math.Sqrt(float64(req.A))),
		SqrtB:          float32(math.Sqrt(float64(req.B))),
		FactorialA:     float32(math.Gamma(float64(req.A + 1))),
		FactorialB:     float32(math.Gamma(float64(req.B + 1))),
		ResponseInfo: &pb.ClientResponseInfo{
			RequestTime:  req.GetRequestInfo().GetTimestamp(),
			ResponseTime: timestamppb.Now(),
			Language:     "go",
		},
	}, nil
}
