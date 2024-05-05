
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

folders:
	@mkdir -p ./dev/infra/data/traefik-access-log
	@if ! test -f ./dev/infra/data/traefik-access-log/access.log; then \
		touch ./dev/infra/data/traefik-access-log/access.log; \
	fi

gen_cert:
	@cd ./dev/infra/certs && ./gen_cert.sh

netwk:
	@docker network create sacre_tech || true

infra_setup: gen_cert
	@docker compose build
	@docker compose pull

infra_up: folders netwk
	@docker compose up -d

infra_down:
	@docker compose down --remove-orphans
	@docker volume prune -f
	@docker network prune -f
	@rm -rf ./dev/infra/data