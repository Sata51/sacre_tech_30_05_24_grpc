
PROTO_ROOT_DIR = /home/vscode/.local/include


create_app:
	@curl -s "https://laravel.build/grpc_client?with=redis&devcontainer" | bash

build_proto_maker:
	@docker build -t grpc_php_proto_maker -f proto-maker.Dockerfile .

run_proto_maker:
	@docker run --rm -v $(PWD)/../proto_files:/proto_files:ro -v $(PWD)/grpc_client/app:/app grpc_php_proto_maker

proto:
	@mkdir -p grpc_client/app/gen
	@cd .. && \
	protoc -I$(PROTO_ROOT_DIR) \
	--proto_path=proto_files \
	--php_out=php/grpc_client/app/gen \
	--grpc_out=generate_server:php/grpc_client/app/gen \
	--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin proto_files/*.proto