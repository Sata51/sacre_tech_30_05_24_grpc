
PROTO_ROOT_DIR = /home/vscode/.local/include

proto:
	@mkdir -p grpc_client/src/gen
	@cd .. && \
	protoc -I$(PROTO_ROOT_DIR) \
	--proto_path=proto_files \
	--php_out=php/grpc_client/src/gen \
	--grpc_out=generate_server:php/grpc_client/src/gen \
	--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin proto_files/*.proto