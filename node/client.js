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
      console.log("From language: ", response.response_info.language);

      console.log(response.message);

      const responseTime = new Timestamp();
      responseTime.setSeconds(response.response_info.response_time.seconds);
      responseTime.setNanos(response.response_info.response_time.nanos);

      const requestTime = new Timestamp();
      requestTime.setSeconds(response.response_info.request_time.seconds);
      requestTime.setNanos(response.response_info.request_time.nanos);

      console.log(
        "elapsed time: ",
        responseTime.toDate() - requestTime.toDate()
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
      console.log("From language: ", response.response_info.language);

      console.log(
        `Addition: ${response.addition}\nSubtraction: ${response.subtraction}\nMultiplication: ${response.multiplication}\nDivision: ${response.division}`
      );

      const responseTime = new Timestamp();
      responseTime.setSeconds(response.response_info.response_time.seconds);
      responseTime.setNanos(response.response_info.response_time.nanos);

      const requestTime = new Timestamp();
      requestTime.setSeconds(response.response_info.request_time.seconds);
      requestTime.setNanos(response.response_info.request_time.nanos);

      console.log(
        "elapsed time: ",
        responseTime.toDate() - requestTime.toDate()
      );
    }
  );
};

main();
