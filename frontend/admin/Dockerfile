# develop stage
FROM --platform=linux/amd64 node:18.6.0-alpine as develop-stage
WORKDIR /app
COPY package*.json ./

COPY . .

# build stage
FROM develop-stage as build-stage
RUN yarn
RUN yarn build

# production stage
FROM --platform=linux/amd64 nginx:1.25-alpine as production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
COPY ./docker/production/nginx/conf.d /etc/nginx/conf.d

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
