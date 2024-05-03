PROTO_ROOT_DIR = /usr/.local/protobuf/include

proto:
	mkdir -p /app/gen
	cd / && \
	protoc -I$(PROTO_ROOT_DIR) \
	--proto_path=proto_files \
	--php_out=/app/gen \
	--grpc_out=generate_server:/app/gen \
	--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin proto_files/*.proto