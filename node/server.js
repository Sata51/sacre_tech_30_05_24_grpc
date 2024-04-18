const grpc = require("@grpc/grpc-js");

const service = require("./gen/service_grpc_pb");
const response = require("./gen/response_pb");

const sayHello = (call, callback) => {
  const reply = new response.HelloResponse();
  reply.setMessage(`Hello from node, ${call.request.getName()}!`);
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
