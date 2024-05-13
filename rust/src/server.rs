use std::time::SystemTime;

use hello_service::{
    calculator_service_server::{CalculatorService, CalculatorServiceServer},
    hello_service_server::{HelloService, HelloServiceServer},
    CalculatorRequest, CalculatorResponse, ClientResponseInfo, HelloRequest, HelloResponse,
};
use prost_types::Timestamp;
use spfunc::gamma::gamma;
use tonic::{transport::Server, Request, Response, Status};

pub mod hello_service {
    tonic::include_proto!("service");
}

#[derive(Debug, Default)]
pub struct MyHelloService {}

#[tonic::async_trait]
impl HelloService for MyHelloService {
    async fn say_hello(
        &self,
        request: Request<HelloRequest>,
    ) -> Result<Response<HelloResponse>, Status> {
        // Copy the request time to a temporary variable
        let req = request.into_inner();

        let response = HelloResponse {
            message: format!("Hello, {}!", req.name),
            response_info: Some(ClientResponseInfo {
                language: "Rust".to_string(),
                request_time: req.request_info.unwrap().timestamp,
                response_time: Some(Timestamp::from(SystemTime::now())),
            }),
        };

        Ok(Response::new(response))
    }
}

#[derive(Debug, Default)]
pub struct MyCalcService {}

#[tonic::async_trait]
impl CalculatorService for MyCalcService {
    async fn calculate(
        &self,
        request: Request<CalculatorRequest>,
    ) -> Result<Response<CalculatorResponse>, Status> {
        // Copy the request time to a temporary variable
        let req = request.into_inner();

        let response = CalculatorResponse {
            addition: req.a + req.b,
            subtraction: req.a - req.b,
            multiplication: req.a * req.b,
            division: req.a / req.b, // Division by zero will panic
            power: req.a.powf(req.b),
            r#mod: req.a % req.b,
            sqrt_a: req.a.sqrt(),
            sqrt_b: req.b.sqrt(),
            factorial_a: gamma(req.a + 1.0),
            factorial_b: gamma(req.b + 1.0),
            response_info: Some(ClientResponseInfo {
                language: "Rust".to_string(),
                request_time: req.request_info.unwrap().timestamp,
                response_time: Some(Timestamp::from(SystemTime::now())),
            }),
        };

        Ok(Response::new(response))
    }
}

#[tokio::main]
async fn main() -> Result<(), Box<dyn std::error::Error>> {
    let addr = "[::]:50051".parse().unwrap();
    let hello_service = MyHelloService::default();
    let calc_service = MyCalcService::default();

    println!("Server listening on {}", addr);

    Server::builder()
        .add_service(HelloServiceServer::new(hello_service))
        .add_service(CalculatorServiceServer::new(calc_service))
        .serve(addr)
        .await?;

    Ok(())
}
