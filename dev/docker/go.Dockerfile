FROM golang:1.22-alpine as build

RUN apk update && \
  apk add \
  ca-certificates \
  make \
  tzdata && \
  rm -rf /var/lib/apt/lists/*

# Set the Current Working Directory inside the container
WORKDIR /github.com/Sata51/sacre_tech_30_05_24_grpc/go

COPY ./go/go.mod ./go/go.sum ./
COPY ./go/Makefile ./Makefile

# Copy the source from the current directory to the Working Directory inside the container
COPY ./go/cmd/server ./cmd/server
COPY ./go/gen ./gen

RUN make dep

RUN make build_docker_server

FROM scratch

COPY --from=build /github.com/Sata51/sacre_tech_30_05_24_grpc/go/bin/server /server
# Import from builder.
COPY --from=build /usr/share/zoneinfo /usr/share/zoneinfo
COPY --from=build /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/

EXPOSE 50051
CMD ["/server"]
