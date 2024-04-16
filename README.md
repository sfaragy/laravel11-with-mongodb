To build the application:
``` make build ```

To start the application:
``` make start ```

To test the broadcasts:
``` 
make redis-login
//subscribe [channel name]
subscribe realtime-test-channel

```
App URL: lmw.local.com / localhost

add this to the hosts file in your system

# Test event API endpoint: (post request with json body {"message": "Test Message"})
```
/api/realtime-test-event
```






# In the future we can write some entrypoint files and add in Dockerfile to copy it
```
COPY ./docker/docker-php-* /usr/local/bin/
RUN dos2unix /usr/local/bin/docker-php-entrypoint
RUN dos2unix /usr/local/bin/docker-php-entrypoint-dev
```


# Example: docker-php-entrypoint-dev

```
#!/bin/sh
set -e

# run last minute build tools just for local dev
# this file should just be used to override on local dev in a compose file

# run default entrypoint first
/usr/local/bin/docker-php-entrypoint

# ensure bind mount permissions are what we need
chown -R :www-data /var/www/http

chmod -R 775 /var/www/http/storage /var/www/http/bootstrap/cache
  
# run last minute build tools just for local dev
cd /var/www/http
composer dump-autoload
cd /var/www/http/public

exec "$@"
```

# In docker-compose we have to add the following line to add this entrypoint in PHP container

```
   entrypoint: /usr/local/bin/docker-php-entrypoint-dev
```