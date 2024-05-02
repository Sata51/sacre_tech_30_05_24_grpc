sudo chmod a+x "$(pwd)/public_html"
sudo rm -rf /var/www/html
sudo ln -s "$(pwd)/public_html" /var/www/html
sudo a2enmod rewrite
echo 'error_reporting=0' | sudo tee /usr/local/etc/php/conf.d/no-warn.ini
sudo apache2ctl start

# Install symfony cli
wget https://get.symfony.com/cli/installer -O - | bash
sudo mv /home/vscode/.symfony5/bin/symfony /usr/local/bin/symfony

# Install bazelisk
sudo wget https://github.com/bazelbuild/bazelisk/releases/download/v1.19.0/bazelisk-linux-arm64 -O /usr/local/bin/bazel
sudo chmod +x /usr/local/bin/bazel

sudo apt-get update && sudo apt-get install -y \
    autoconf \
    automake \
    libtool \
    make \
    g++ \
    gcc \
    unzip \
    curl \
    git \
    libssl-dev \
    pkg-config \
    zlib1g-dev \
    wget

export CC=/usr/bin/gcc
export CXX=/usr/bin/g++

cd /tmp
git clone https://github.com/grpc/grpc.git --depth 1 --single-branch --branch v1.63.0 --recurse-submodules --shallow-submodules
cd grpc

# Install protobuf
# bazel build :protoc :protobuf
bazel build @com_google_protobuf//:protoc //src/compiler:all

# sudo cp bazel-bin/protoc /usr/local/bin
sudo cp bazel-bin/external/com_google_protobuf/protoc /usr/local/bin
sudo chmod +x /usr/local/bin/protoc
sudo cp bazel-bin/src/compiler/grpc_php_plugin /usr/local/bin
sudo chmod +x /usr/local/bin/grpc_php_plugin

cd /tmp/buf
curl -LO https://github.com/protocolbuffers/protobuf/releases/download/v26.1/protoc-26.1-linux-aarch_64.zip
unzip protoc-26.1-linux-aarch_64.zip -d $HOME/.local
export PATH="$PATH:$HOME/.local/bin"