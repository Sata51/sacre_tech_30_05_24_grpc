const PROTO_PATH = __dirname + "/../proto_files/service.proto";

const grpc = require("@grpc/grpc-js");
const protoLoader = require("@grpc/proto-loader");
const packageDefinition = protoLoader.loadSync(PROTO_PATH, {
  keepCase: true,
  longs: String,
  enums: String,
  defaults: true,
  oneofs: true,
});

const service_proto = grpc.loadPackageDefinition(packageDefinition).service;

const main = () => {
  const helloClient = new service_proto.HelloService(
    "localhost:50051",
    grpc.credentials.createInsecure()
  );
  helloClient.sayHello({ name: "Sata" }, (err, response) => {
    if (err) {
      console.error(err);
      return;
    }
    console.log(response.message);
  });

  const calculatorClient = new service_proto.CalculatorService(
    "localhost:50051",
    grpc.credentials.createInsecure()
  );
  calculatorClient.calculate({ a: 10, b: 12 }, (err, response) => {
    if (err) {
      console.error(err);
      return;
    }
    console.log(
      `Addition: ${response.addition}\nSubtraction: ${response.subtraction}\nMultiplication: ${response.multiplication}\nDivision: ${response.division}`
    );
  });
};

main();
