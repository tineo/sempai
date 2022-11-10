sempai

```
sudo apt-get update
sudo apt-get -y install php php-redis php-curl php-zip php-mysql libapache2-mod-php

https://getcomposer.org/download/

sudo mv composer.phar /usr/local/bin/composer

composer install
```

```
create table tucambista
(
	id int auto_increment,
	bidRate DECIMAL(6,4) default 0 not null,
	offerRate DECIMAL(6,4) default 0 not null,
	bidReferenceRate DECIMAL(6,4) default 0 not null,
	offerReferenceRate DECIMAL(6,4) default 0 not null,
	dateRate DATE NULL,
	timeRate TIME NULL,
	datetimeRate DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	timestampRate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	constraint tucambista_pk
		primary key (id)
);
```

```
sudo systemctl enable cron

crontab -e


# each 5min since 8am until 9pm 
*/5 8-21 * * * /usr/bin/php /path/to/sempai/cron.php

# each 5min since 8am until 9pm 
*/5 13-23 * * * /usr/bin/php /path/to/sempai/cron.php
*/5 0-2 * * * /usr/bin/php /path/to/sempai/cron.php
```

```
DocumentRoot /path/to/sempai
<Directory /path/to/sempai>
    AllowOverride All
    Require all granted
</Directory>
```
`chmod -R 755 /path/to/sempai/..`


`docker run --name redis -d -p 6379:6379 redis redis-server --requirepass "SUPER_SECRET_PASSWORD"`

```
sudo apt-get -y install redis-server
sudo nano  /etc/redis/redis.conf
- uncommnet #requirepass foobared

```
