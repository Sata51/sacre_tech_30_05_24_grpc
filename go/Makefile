
PKG := "github.com/Sata51/sacre_tech_30_05_24_grpc/go"
PKG_LIST := $(shell go list ${PKG}/... | grep -v /vendor/)
GO_FILES := $(shell find . -name '*.go' | grep -v /vendor/ | grep -v _test.go)

PKG_SERVER := "${PKG}/cmd/server"
PKG_CLIENT := "${PKG}/cmd/client"

PKG_SERVER_OUTPUT := "bin/server"
PKG_CLIENT_OUTPUT := "bin/client"


dep: ## get dependency
	@go get -u ./...
	@go mod vendor
	@go mod tidy

build:
	@go build -mod=vendor -v -o ${PKG_SERVER_OUTPUT} $(PKG_SERVER)
	@go build -mod=vendor -v -o ${PKG_CLIENT_OUTPUT} $(PKG_CLIENT)

build_docker_server:
	@CGO_ENABLED=0 GOOS=linux go build -mod=vendor -v -o ${PKG_SERVER_OUTPUT} $(PKG_SERVER)

run-server: build
	@./bin/server

run-client: build
	@./bin/client