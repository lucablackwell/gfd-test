#Build via docker-compose for context/path to be correct
FROM ubuntu:jammy

MAINTAINER lee@leeblackwell.com

ARG UPDATEPKGS
ENV UPDATEPKGS ${UPDATEPKGS}

COPY common/* web/configure* web/init.sh web/cert.sh /root/

RUN /root/configure.sh

CMD ["/usr/bin/dumb-init","-v","/root/init.sh"]

#Run everything else as www-data (which is what nginx is expecting)
#USER www-data

#docker container run -it -p80:80 --mount type=bind,source="$(pwd)"/_BINDMOUNT,target=/app container:tag
