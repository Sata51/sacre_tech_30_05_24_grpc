FROM python:3.12-slim

RUN apt-get update && \
  apt-get install -y \
  curl \
  build-essential \
  && rm -rf /var/lib/apt/lists/*

ADD --chmod=755 https://astral.sh/uv/install.sh /install.sh
RUN /install.sh && rm /install.sh

# Set the working directory
WORKDIR /app

COPY ./python/server.py ./server.py
COPY ./python/gen ./gen

RUN /root/.cargo/bin/uv pip install --system --no-cache grpcio grpcio-tools

# Expose the port
EXPOSE 50051
CMD [ "python", "server.py" ]