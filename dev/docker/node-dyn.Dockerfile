FROM node:22-slim AS base
ENV PNPM_HOME="/pnpm"
ENV PATH="$PNPM_HOME:$PATH"
RUN corepack enable

WORKDIR /app

COPY ./node/package.json ./
COPY ./node/pnpm-lock.yaml ./

RUN pnpm install --frozen-lockfile

COPY ./node/server.js ./

EXPOSE 51051
CMD [ "node", "/app/server.js" ]