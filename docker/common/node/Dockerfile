FROM node:14

RUN #apk update && apk add git && apk add python make g++

WORKDIR /app

# To Fix Permissions for Packages
RUN yarn config set unsafe-perm true

#COPY . /app

RUN #chown -R node /app/node_modules

USER node
