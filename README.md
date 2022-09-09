sempai

```
sudo apt-get update
sudo apt-get -y install php php-zip php-mysql

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
*/5 8-21 * * * /usr/bin/php /path/to/cron.php

# each 5min since 8am until 9pm 
*/5 13-23 * * * /usr/bin/php /path/to/cron.php
*/5 0-2 * * * /usr/bin/php /path/to/cron.php
```