#Build via docker-compose for context/path to be correct
FROM ubuntu:focal

MAINTAINER lee@leeblackwell.com

#NODEVERSION is defined empty, to be picked up as a build arg
ARG NODEVERSION

ARG UPDATEPKGS
ENV UPDATEPKGS ${UPDATEPKGS}

COPY common/* console/configure* console/init.sh /root/

RUN /root/configure.sh

CMD ["/usr/bin/dumb-init","-v","/root/init.sh"]

#docker container run -it -p80:80 --mount type=bind,source="$(pwd)"/_BINDMOUNT,target=/app container:tag