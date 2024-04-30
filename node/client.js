const PROTO_PATH = __dirname + "/../proto_files/service.proto";

const grpc = require("@grpc/grpc-js");
const protoLoader = require("@grpc/proto-loader");
const { Timestamp } = require("google-protobuf/google/protobuf/timestamp_pb");
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
  helloClient.sayHello(
    {
      name: "Sata",
      request_info: {
        timestamp: Timestamp.fromDate(new Date()),
      },
    },
    (err, response) => {
      if (err) {
        console.error(err);
        return;
      }
      console.log(response.message);

      console.log(
        "elapsed time: ",
        response.response_info.response_time.toDate() -
          response.request_info.timestamp.toDate()
      );
    }
  );

  const calculatorClient = new service_proto.CalculatorService(
    "localhost:50051",
    grpc.credentials.createInsecure()
  );
  calculatorClient.calculate(
    {
      a: 10,
      b: 12,
      request_info: {
        timestamp: Timestamp.fromDate(new Date()),
      },
    },
    (err, response) => {
      if (err) {
        console.error(err);
        return;
      }
      console.log(
        `Addition: ${response.addition}\nSubtraction: ${response.subtraction}\nMultiplication: ${response.multiplication}\nDivision: ${response.division}`
      );

      console.log(
        "elapsed time: ",
        response.response_info.response_time.toDate() -
          response.request_info.timestamp.toDate()
      );
    }
  );
};

main();
