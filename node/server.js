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

const sayHello = (call, callback) => {
  const t = new Timestamp();
  t.setSeconds(call.request.request_info.timestamp.seconds);
  t.setNanos(call.request.request_info.timestamp.nanos);

  callback(null, {
    message: `Hello from node, ${call.request.name}!`,
    response_info: {
      request_time: t,
      response_time: Timestamp.fromDate(new Date()),
    },
  });
};

const calculate = (call, callback) => {
  const A = call.request.a;
  const B = call.request.b;

  const t = new Timestamp();
  t.setSeconds(call.request.request_info.timestamp.seconds);
  t.setNanos(call.request.request_info.timestamp.nanos);

  callback(null, {
    addition: A + B,
    subtraction: A - B,
    multiplication: A * B,
    division: B === 0 ? 0 : A / B,

    response_info: {
      request_time: t,
      response_time: Timestamp.fromDate(new Date()),
    },
  });
};

const main = () => {
  const server = new grpc.Server();
  server.addService(service_proto.HelloService.service, { sayHello: sayHello });
  server.addService(service_proto.CalculatorService.service, {
    calculate: calculate,
  });
  server.bindAsync(
    "0.0.0.0:50051",
    grpc.ServerCredentials.createInsecure(),
    (err, port) => {
      if (err) {
        console.error(err);
        return;
      }
      console.log(`server listening on port ${port}`);
    }
  );
};

main();
