
PROTO_ROOT_DIR = /opt/homebrew/Cellar/protobuf/26.1/include


proto: proto_go


proto_go:
	@mkdir -p go/gen/service
	@GO111MODULE=on protoc --go_out=go/gen/service --go_opt=paths=source_relative \
		--go-grpc_out=go/gen/service --go-grpc_opt=paths=source_relative,require_unimplemented_servers=false \
		--proto_path=proto_files proto_files/*.proto