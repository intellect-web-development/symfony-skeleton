FROM node:14

WORKDIR /app

COPY ./package.json /app

# To Fix Permissions for Packages
RUN yarn config set unsafe-perm true

COPY . /app

RUN yarn

RUN mkdir -p /app/node_modules

RUN chown -R node /app/node_modules

USER node
