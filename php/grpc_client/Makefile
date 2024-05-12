PROTO_ROOT_DIR = /usr/.local/protobuf/include/

start:
	@php artisan serve --port=8199 --host=0.0.0.0


bazel-build:
	@cd /tmp/grpc && sudo bazel build @com_google_protobuf//:protoc //src/compiler:all
	@sudo cp /tmp/grpc/bazel-bin/external/com_google_protobuf/protoc /usr/local/bin
	@chmod +x /usr/local/bin/protoc
	@sudo cp /tmp/grpc/bazel-bin/src/compiler/grpc_php_plugin /usr/local/bin
	@chmod +x /usr/local/bin/grpc_php_plugin

proto:
	@mkdir -p app/gen
	@protoc -I$(PROTO_ROOT_DIR) \
	--proto_path=/proto_files \
	--php_out=app/gen \
	--grpc_out=generate_server:app/gen \
	--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin /proto_files/*.proto
