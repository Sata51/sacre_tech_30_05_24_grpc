const grpc = require("@grpc/grpc-js");

const service = require("./gen/service_grpc_pb");
const response = require("./gen/response_pb");
const { Timestamp } = require("google-protobuf/google/protobuf/timestamp_pb");
const { gamma } = require("mathjs");

const sayHello = (call, callback) => {
  const reply = new response.HelloResponse();
  reply.setMessage(`Hello from node (static), ${call.request.getName()}!`);

  const info = new response.ClientResponseInfo();
  info.setRequestTime(call.request.getRequestInfo().getTimestamp());
  info.setResponseTime(Timestamp.fromDate(new Date()));
  info.setLanguage("node-static");

  reply.setResponseInfo(info);

  callback(null, reply);
};

const calculate = (call, callback) => {
  const A = call.request.getA();
  const B = call.request.getB();

  const reply = new response.CalculatorResponse();
  reply.setAddition(A + B);
  reply.setSubtraction(A - B);
  reply.setMultiplication(A * B);
  reply.setDivision(B === 0 ? 0 : A / B);
  reply.setPower(Math.pow(A, B));
  reply.setMod(A % B);
  reply.setSqrta(Math.sqrt(A));
  reply.setSqrtb(Math.sqrt(B));
  reply.setFactoriala(gamma(A + 1));
  reply.setFactorialb(gamma(B + 1));

  const info = new response.ClientResponseInfo();
  info.setRequestTime(call.request.getRequestInfo().getTimestamp());
  info.setResponseTime(Timestamp.fromDate(new Date()));
  info.setLanguage("node-static");
  reply.setResponseInfo(info);

  callback(null, reply);
};

const main = () => {
  const server = new grpc.Server();
  server.addService(service.HelloServiceService, { sayHello: sayHello });
  server.addService(service.CalculatorServiceService, {
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
