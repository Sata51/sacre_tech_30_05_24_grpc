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

git clone https://github.com/protocolbuffers/protobuf.git
cd protobuf
git submodule update --init --recursive

# Install protobuf
bazel build :protoc :protobuf

cp bazel-bin/protoc /usr/local/bin

# Cleanup
cd ..
rm -rf protobuf