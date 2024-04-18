package main

import (
	"context"
	"flag"
	"fmt"

	pb "github.com/Sata51/sacre_tech_30_05_24_grpc/go/gen/service"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
)

var serverAddr = flag.String("server_addr", "localhost:50051", "The server address in the format of host:port")

func main() {
	flag.Parse()

	var opts []grpc.DialOption
	opts = append(opts, grpc.WithTransportCredentials(insecure.NewCredentials()))

	conn, err := grpc.Dial(*serverAddr, opts...)
	if err != nil {
		panic(err)
	}
	defer conn.Close()

	clientHello := pb.NewHelloServiceClient(conn)

	// Call the SayHello
	req := &pb.HelloRequest{
		Name: "Sata",
	}

	resp, err := clientHello.SayHello(context.TODO(), req)
	if err != nil {
		panic(err)
	}
	fmt.Println(resp.GetName())

	clientCalculate := pb.NewCalculatorServiceClient(conn)

	reqCalc := &pb.CalculatorRequest{
		A: 10,
		B: 20,
	}

	respCalc, err := clientCalculate.Calculate(context.TODO(), reqCalc)
	if err != nil {
		panic(err)
	}
	fmt.Printf("Add: %0.2f\n", respCalc.GetAddition())
	fmt.Printf("Sub: %0.2f\n", respCalc.GetSubtraction())
	fmt.Printf("Mul: %0.2f\n", respCalc.GetMultiplication())
	fmt.Printf("Div: %0.2f\n", respCalc.GetDivision())
}
