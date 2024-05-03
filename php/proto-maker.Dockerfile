FROM alpine:latest

RUN apk add wget unzip make g++ gcc curl autoconf automake libtool git

ENV CC=/usr/bin/gcc
ENV CXX=/usr/bin/g++

RUN wget https://github.com/bazelbuild/bazel/releases/download/7.1.1/bazel-7.1.1-linux-arm64 -O /usr/local/bin/bazel && chmod +x /usr/local/bin/bazel

ENV PATH="/usr/local/bin:${PATH}"

WORKDIR /tmp
RUN git clone https://github.com/grpc/grpc.git --depth 1 --single-branch --branch v1.63.0 --recurse-submodules --shallow-submodules

WORKDIR /tmp/grpc
RUN which bazel
RUN bazel build @com_google_protobuf//:protoc //src/compiler:all

RUN cp bazel-bin/external/com_google_protobuf/protoc /usr/local/bin && chmod +x /usr/local/bin/protoc
RUN cp bazel-bin/src/compiler/grpc_php_plugin /usr/local/bin && chmod +x /usr/local/bin/grpc_php_plugin

WORKDIR /tmp
RUN mkdir -p /usr/.local/protobuf
RUN curl -LO https://github.com/protocolbuffers/protobuf/releases/download/v26.1/protoc-26.1-linux-aarch_64.zip && unzip protoc-26.1-linux-aarch_64.zip -d /usr/.local/protobuf
ENV PATH="/usr/.local/protobuf/bin:${PATH}"

COPY proto-maker.Makefile /tmp/builder/Makefile

WORKDIR /tmp/builder

ENTRYPOINT [ "make", "proto" ]