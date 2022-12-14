#Build via docker-compose for context/path to be correct
FROM ubuntu:jammy

MAINTAINER lee@leeblackwell.com

ENV DATABASENAME ${DATABASENAME}
ENV DATABASEUSER ${DATABASEUSER}

ARG UPDATEPKGS
ENV UPDATEPKGS ${UPDATEPKGS}

COPY common/* mysql/configure* mysql/init.sh mysql/mysqlbackup.sh /root/
RUN mkdir /mysqldump

RUN /root/configure.sh

CMD ["/usr/bin/dumb-init","-v","/root/init.sh"]

#docker container run -it -p3306:3306 --mount type=bind,source="$(pwd)"/_BINDMOUNT,target=/mysql container:tag