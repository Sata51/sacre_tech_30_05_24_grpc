FROM rust:1.78.0 as build

# Install protobuf
RUN apt-get update && apt-get install -y protobuf-compiler

WORKDIR /usr/src/sacre_tech_30_05_24_grpc

# Copy the protobuf files
COPY ./proto_files ./proto_files

WORKDIR /usr/src/sacre_tech_30_05_24_grpc/rust

COPY ./rust/Cargo.toml ./Cargo.toml
COPY ./rust/Cargo.lock ./Cargo.lock

# Add the build script
COPY ./rust/build.rs ./build.rs

# Add the source code
COPY ./rust/src ./src

RUN cargo build --release --bin rust-server

FROM rust:1.78.0-slim-bookworm

COPY --from=build /usr/src/sacre_tech_30_05_24_grpc/rust/target/release/rust-server .

EXPOSE 50051
CMD [ "./rust-server" ]