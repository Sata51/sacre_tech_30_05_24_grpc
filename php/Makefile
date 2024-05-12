
PROTO_ROOT_DIR = /home/vscode/.local/include


create_app:
	@curl -s "https://laravel.build/grpc_client?with=redis&devcontainer" | bash

proto:
	@mkdir -p grpc_client/app/gen
	@cd .. && \
	protoc -I$(PROTO_ROOT_DIR) \
	--proto_path=proto_files \
	--php_out=php/grpc_client/app/gen \
	--grpc_out=generate_server:php/grpc_client/app/gen \
	--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin proto_files/*.proto