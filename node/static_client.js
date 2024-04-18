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
  helloClient.sayHello(helloRequest, (err, response) => {
    if (err) {
      console.error(err);
      return;
    }
    console.log(response.getMessage());
  });

  const calculatorClient = new service.CalculatorServiceClient(
    "localhost:50051",
    grpc.credentials.createInsecure()
  );

  const calculatorRequest = new request.CalculatorRequest();
  calculatorRequest.setA(10);
  calculatorRequest.setB(25);

  calculatorClient.calculate(calculatorRequest, (err, response) => {
    if (err) {
      console.error(err);
      return;
    }
    console.log(
      `Addition: ${response.getAddition()}\nSubtraction: ${response.getSubtraction()}\nMultiplication: ${response.getMultiplication()}\nDivision: ${response.getDivision()}`
    );
  });
};

main();
