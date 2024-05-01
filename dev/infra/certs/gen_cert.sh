#!/bin/bash

# Check if the cert is already generated
if [ -f local-cert.pem ]; then
    if [ -f local-key.pem ]; then
        echo "Certificate already generated!"
        exit 0
    else
        echo "Certificate is missing key, please delete local-cert.pem and try again"
        exit 1
    fi
else
    echo "Generating certificate..."
fi

# Check if brew is installed
echo "Checking if brew is installed..."
if ! [ -x "$(command -v brew)" ]; then
    echo "brew could not be found, please install it -> https://brew.sh/"
    exit 1
fi

echo "Checking if mkcert is installed..."
# Check if mkcert is installed
if ! [ -x "$(command -v mkcert)" ]; then
    echo "mkcert could not be found, installing (requires sudo)..."
    sudo apt install libnss3-tools # Install certutil
    brew install mkcert # Install mkcert
    mkcert -install # Install the root certificate
fi

mkcert -cert-file local-cert.pem -key-file local-key.pem localhost '*.sacre-tech.local' 'sacre-tech.local'
