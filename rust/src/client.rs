use rand::Rng;
use std::time::{SystemTime, UNIX_EPOCH};

use hello_service::{
    calculator_service_client::CalculatorServiceClient, hello_service_client::HelloServiceClient,
    CalculatorRequest, CalculatorResponse, ClientRequestInfo, ClientResponseInfo, HelloRequest,
    HelloResponse,
};
use prost_types::Timestamp;
use tonic::client;

pub mod hello_service {
    tonic::include_proto!("service");
}

#[tokio::main]
async fn main() -> Result<(), Box<dyn std::error::Error>> {
    let mut client = HelloServiceClient::connect("http://[::1]:50051").await?;

    let request = tonic::Request::new(HelloRequest {
        name: "Sata".to_string(),
        request_info: Some(ClientRequestInfo {
            timestamp: Some(Timestamp::from(SystemTime::now())),
        }),
    });

    let response = client.say_hello(request).await?;
    let req = response.into_inner();

    println!(
        "From language: {}",
        req.response_info.clone().unwrap().language
    );
    println!("Message: {}", req.message);
    let req_time = req.response_info.clone().unwrap().request_time.unwrap();
    let req_time_st = match SystemTime::try_from(req_time) {
        Ok(st) => st,
        Err(e) => {
            println!("Error: {}", e);
            UNIX_EPOCH
        }
    };
    let resp_time = req.response_info.unwrap().response_time.unwrap();
    let resp_time_st = match SystemTime::try_from(resp_time) {
        Ok(st) => st,
        Err(e) => {
            println!("Error: {}", e);
            UNIX_EPOCH
        }
    };

    println!(
        "Elapsed time: {:?}",
        resp_time_st.duration_since(req_time_st).unwrap()
    );

    let mut client = CalculatorServiceClient::connect("http://[::1]:50051").await?;

    let mut rng = rand::thread_rng();

    let request = tonic::Request::new(CalculatorRequest {
        a: rng.gen_range(1.0..12.0),
        b: rng.gen_range(1.0..12.0),
        request_info: Some(ClientRequestInfo {
            timestamp: Some(Timestamp::from(SystemTime::now())),
        }),
    });

    let response = client.calculate(request).await?;
    let req = response.into_inner();

    println!(
        "From language: {}",
        req.response_info.clone().unwrap().language
    );
    println!("Addition: {}", req.addition);
    println!("Subtraction: {}", req.subtraction);
    println!("Multiplication: {}", req.multiplication);
    println!("Division: {}", req.division);
    println!("Power: {}", req.power);
    println!("Modulus: {}", req.r#mod);
    println!("Square root of a: {}", req.sqrt_a);
    println!("Square root of b: {}", req.sqrt_b);
    println!("Factorial of a: {}", req.factorial_a);
    println!("Factorial of b: {}", req.factorial_b);

    let req_time = req.response_info.clone().unwrap().request_time.unwrap();
    let req_time_st = match SystemTime::try_from(req_time) {
        Ok(st) => st,
        Err(e) => {
            println!("Error: {}", e);
            UNIX_EPOCH
        }
    };
    let resp_time = req.response_info.unwrap().response_time.unwrap();
    let resp_time_st = match SystemTime::try_from(resp_time) {
        Ok(st) => st,
        Err(e) => {
            println!("Error: {}", e);
            UNIX_EPOCH
        }
    };

    println!(
        "Elapsed time: {:?}",
        resp_time_st.duration_since(req_time_st).unwrap()
    );

    Ok(())
}
