FROM node:boron

# Global install yarn package manager
RUN apt-get update && apt-get install -y curl apt-transport-https && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn
RUN npm install -g gulp-cli
RUN mkdir /app
WORKDIR /app
COPY ./init.sh /node-init.sh
RUN chmod 755 /node-init.sh
ENTRYPOINT ["/node-init.sh"]
