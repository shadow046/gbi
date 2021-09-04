FROM ubuntu:20.04
ENV TZ=Asia/Manila
ENV DEBIAN_FRONTEND=noninteractive
RUN 	ln -fs /usr/share/zoneinfo/Asia/Manila /etc/localtime
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN 	apt-get update -y && \
	apt-get upgrade -y && \
	apt-get dist-upgrade -y
RUN apt-get install software-properties-common -y 
RUN	add-apt-repository ppa:ondrej/php -y
RUN apt-get update -y
RUN apt-get install php7.4 php7.4-fpm php7.4-curl php7.4-ldap php7.4-mysql php7.4-gd \
	php7.4-xml php7.4-mbstring php7.4-zip php7.4-bcmath composer curl wget nano php \
	php8.0 php8.0-dev php8.0-fpm php8.0-xml -y
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql17
RUN ACCEPT_EULA=Y apt-get install -y mssql-tools
RUN echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
RUN source ~/.bashrc
RUN apt-get install -y unixodbc-dev
RUN apt-get upgrade -y
RUN apt-get purge apache2 apache* -y
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/8.0/mods-available/sqlsrv.ini
RUN printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/8.0/mods-available/pdo_sqlsrv.ini
RUN phpenmod -v 8.0 sqlsrv pdo_sqlsrv
RUN systemctl restart php8.0-fpm
WORKDIR /home/
COPY . .
RUN composer install
#RUN php artisan key:generate
RUN chmod 777 -R .
EXPOSE 8011
CMD php artisan serve --host 0.0.0.0 --port 8011
