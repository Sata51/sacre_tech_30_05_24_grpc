
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
	@protoc -I$(PROTO_ROOT_DIR) --dart_out=dart/lib/gen $(PROTO_ROOT_DIR)/google/protobuf/*.proto

proto_node:
	@mkdir -p node/gen
	@grpc_tools_node_protoc --js_out=import_style=commonjs,binary:./node/gen/ --grpc_out=grpc_js:./node/gen/ --proto_path=proto_files proto_files/*.proto

proto_ruby:
	@mkdir -p ruby/gen
	@grpc_tools_ruby_protoc --ruby_out=ruby/gen --grpc_out=ruby/gen --proto_path=proto_files proto_files/*.proto

proto_python:
	@mkdir -p python/gen
	@python3 -m grpc_tools.protoc -Iproto_files --python_out=python/gen --grpc_python_out=python/gen proto_files/*.proto


install:
	@dart pub global activate protoc_plugin
	@pnpm install -g grpc-tools
	@gem install grpc grpc-tools solargraph
	@python3 -m pip install --user grpcio-tools
	@cd python && uv venv && source .venv/bin/activate && uv pip install grpcio grpcio-tools


docker_build_dart_srv:
	@docker build -t st_grpc:dart-srv -f dart.Dockerfile .

docker_build_go_srv:
	@docker build -t st_grpc:go-srv -f go.Dockerfile .

docker_build_node_srv_dyn:
	@docker build -t st_grpc:node-dyn-srv -f node-dyn.Dockerfile .

docker_build_node_srv_static:
	@docker build -t st_grpc:node-static-srv -f node-static.Dockerfile .

docker_build_python_srv:
	@docker build -t st_grpc:python-srv -f python.Dockerfile .

docker_build_ruby_srv:
	@docker build -t st_grpc:ruby-srv -f ruby.Dockerfile .

docker_build: docker_build_dart_srv docker_build_go_srv docker_build_node_srv_dyn docker_build_node_srv_static docker_build_python_srv docker_build_ruby_srv

docker_run_dart_srv:
	@docker run -it --rm -p 50051:50051 st_grpc:dart-srv

docker_run_go_srv:
	@docker run -it --rm -p 50051:50051 st_grpc:go-srv

docker_run_node_srv_dyn:
	@docker run -it --rm -p 50051:50051 st_grpc:node-dyn-srv

docker_run_node_srv_static:
	@docker run -it --rm -p 50051:50051 st_grpc:node-static-srv

docker_run_python_srv:
	@docker run -it --rm -p 50051:50051 st_grpc:python-srv

docker_run_ruby_srv:
	@docker run -it --rm -p 50051:50051 st_grpc:ruby-srv