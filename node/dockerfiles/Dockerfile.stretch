FROM debian:stretch

# Build image from this Dockerfile
# docker build -t stretch_npm .
#
# Run build on CI system or docker
# docker run --rm -t -i -v $(pwd):/build/ stretch_npm:latest /usr/bin/npm install .
#
# To run in a CI like Jenkins
# cp dockerfiles/Dockerfile.${OSVERSION} Dockerfile
# docker run --rm -i -v $(pwd):/build/ ${OSVERSION}_npm:latest /usr/bin/npm install .

ENV DEBIAN_FRONTEND=noninteractive
ENV HTTP_PROXY="http://proxy.master.dns:8080"
ENV HTTPS_PROXY="http://proxy.master.dns:8080"
ENV NO_PROXY=localhost,127.0.0.0/8,127.0.0.1,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,172.16.166.0/24,172.16.76.0/24,jenkins.oitc.itn,apt.oitc.itn,proxmox.oitc.itn,.oitc.itn
ENV http_proxy="http://proxy.master.dns:8080"
ENV https_proxy="http://proxy.master.dns:8080"
ENV no_proxy=localhost,127.0.0.0/8,127.0.0.1,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,172.16.166.0/24,172.16.76.0/24,jenkins.oitc.itn,apt.oitc.itn,proxmox.oitc.itn,.oitc.itn

RUN echo "deb http://172.16.166.5:3142/ftp.de.debian.org/debian/ stretch main" > /etc/apt/sources.list \
&& echo "deb-src http://172.16.166.5:3142/ftp.de.debian.org/debian/ stretch main" >> /etc/apt/sources.list \
&& echo "deb http://172.16.166.5:3142/security.debian.org/ stretch/updates main" >> /etc/apt/sources.list \
&& echo "deb-src http://172.16.166.5:3142/security.debian.org/ stretch/updates main" >> /etc/apt/sources.list \
&& echo "deb http://172.16.166.5:3142/ftp.de.debian.org/debian/ stretch-updates main" >> /etc/apt/sources.list \
&& echo "deb-src http://172.16.166.5:3142/ftp.de.debian.org/debian/ stretch-updates main" >> /etc/apt/sources.list \
&& apt-get update \
&& apt-get dist-upgrade -y \
&& apt-get clean

RUN apt-get update && apt-get install -y curl gnupg apt-transport-https \
&& curl https://packages.openitcockpit.com/072783CB.txt | apt-key add -\
&& echo 'deb http://jenkins.oitc.itn/stretch/ stretch main' > /etc/apt/sources.list.d/openitcockpit.list \
&& apt-get update

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y nodejs \
&& apt-get clean

#Install build depends
RUN apt-get update && apt-get install -y libfreetype6-dev libcairo2-dev libpangox-1.0-dev libgif-dev libpng-dev libjpeg62-turbo-dev build-essential \
&& apt-get clean

RUN mkdir -p /build
WORKDIR /build
