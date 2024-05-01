FROM ruby:3.3-slim

WORKDIR /app

COPY ./ruby/server.rb ./server.rb
COPY ./ruby/gen ./gen

RUN gem install grpc grpc-tools

EXPOSE 50051
CMD [ "ruby", "server.rb" ]