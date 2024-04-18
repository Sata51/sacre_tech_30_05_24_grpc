
PROTO_ROOT_DIR = /opt/homebrew/Cellar/protobuf/26.1/include

proto: proto_go proto_dart proto_node proto_ruby proto_python

proto_go:
	@mkdir -p go/gen/service
	@GO111MODULE=on protoc --go_out=go/gen/service --go_opt=paths=source_relative \
		--go-grpc_out=go/gen/service --go-grpc_opt=paths=source_relative,require_unimplemented_servers=false \
		--proto_path=proto_files proto_files/*.proto

proto_dart:
	@mkdir -p dart/lib/gen
	@protoc --dart_out=grpc:dart/lib/gen --proto_path=proto_files proto_files/*.proto

proto_node:
	@mkdir -p node/gen
	@grpc_tools_node_protoc --js_out=import_style=commonjs,binary:./node/gen/ --grpc_out=grpc_js:./node/gen/ --proto_path=proto_files proto_files/*.proto

proto_ruby:
	@mkdir -p ruby/gen
	@grpc_tools_ruby_protoc --ruby_out=ruby/gen --grpc_out=ruby/gen --proto_path=proto_files proto_files/*.proto

proto_python:
	@mkdir -p python/gen
	@python3 -m grpc_tools.protoc -Iproto_files --python_out=python/gen --grpc_python_out=python/gen proto_files/*.proto