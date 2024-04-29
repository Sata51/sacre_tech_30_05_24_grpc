FROM dart:stable as build

WORKDIR /app
COPY ./dart/pubspec.* ./
RUN dart pub get

COPY ./dart ./
RUN dart pub get --offline
RUN dart compile exe bin/server.dart -o bin/server


FROM scratch
COPY --from=build /runtime/ /
COPY --from=build /app/bin/server /app/bin/server

EXPOSE 50051
CMD ["/app/bin/server"]