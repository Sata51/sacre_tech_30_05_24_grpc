const { Timestamp } = require("google-protobuf/google/protobuf/timestamp_pb");
const request = require("./gen/request_pb");
const service = require("./gen/service_grpc_pb");

const grpc = require("@grpc/grpc-js");

const main = () => {
  const helloClient = new service.HelloServiceClient(
    "localhost:50051",
    grpc.credentials.createInsecure()
  );

  const helloRequest = new request.HelloRequest();
  helloRequest.setName("Sata");
  const helloRequestInfo = new request.ClientRequestInfo();
  helloRequestInfo.setTimestamp(Timestamp.fromDate(new Date()));
  helloRequest.setRequestInfo(helloRequestInfo);
  helloClient.sayHello(helloRequest, (err, response) => {
    if (err) {
      console.error(err);
      return;
    }
    console.log(response.getMessage());

    console.log(
      "elapsed time: ",
      response.getResponseInfo().getResponseTime().toDate() -
        response.getResponseInfo().getRequestTime().toDate()
    );
  });

  const calculatorClient = new service.CalculatorServiceClient(
    "localhost:50051",
    grpc.credentials.createInsecure()
  );

  const calculatorRequest = new request.CalculatorRequest();
  calculatorRequest.setA(10);
  calculatorRequest.setB(25);
  const calculatorRequestInfo = new request.ClientRequestInfo();
  calculatorRequestInfo.setTimestamp(Timestamp.fromDate(new Date()));

  calculatorRequest.setRequestInfo(calculatorRequestInfo);

  calculatorClient.calculate(calculatorRequest, (err, response) => {
    if (err) {
      console.error(err);
      return;
    }
    console.log(
      `Addition: ${response.getAddition()}\nSubtraction: ${response.getSubtraction()}\nMultiplication: ${response.getMultiplication()}\nDivision: ${response.getDivision()}`
    );

    console.log(
      "elapsed time: ",
      response.getResponseInfo().getResponseTime().toDate() -
        response.getResponseInfo().getRequestTime().toDate()
    );
  });
};

main();
