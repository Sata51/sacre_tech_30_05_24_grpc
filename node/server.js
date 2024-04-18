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

const sayHello = (call, callback) => {
  callback(null, { message: `Hello from node, ${call.request.name}!` });
};

const calculate = (call, callback) => {
  const A = call.request.a;
  const B = call.request.b;

  callback(null, {
    addition: A + B,
    subtraction: A - B,
    multiplication: A * B,
    division: B === 0 ? 0 : A / B,
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
