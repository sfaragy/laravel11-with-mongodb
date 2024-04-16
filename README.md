
This is a boilerplate for a test exercise:
``` 
Using Laravel 11
Mongodb
Redis
PHP8.3-fpm
Nginx
```

MongoDB is running in a different container as per my exercise. If anyone want to add it in the same project then please add the following in docker-compose.yml

```
  mongo:
    image: mongo:latest
    ports:
      - '27017:27017'
    volumes:
      - mongo-data:/data/db
    networks:
      - my-network

volumes:
  mongo-data:
```

To build the application:
``` make build ```

To start the application:
``` make start ```

App URL: lmw.local.com / localhost

add this to the hosts file in your system

# Test event API endpoint: (post request with json body {"message": "Test Message"})
```
/api/realtime-test-event
```

To test the realtime api from another next.js app:

Create a folder and creat a new next.js app:
```
npx create-next-app my-nextjs-app
cd my-nextjs-app
npm install axios
npm run dev
```

Add this following code to page.tsx
```
"use client"
import { useEffect, useState } from 'react';

import axios from 'axios';

const RealtimePage = () => {

    const [updates, setUpdates] = useState([]);

    useEffect(() => {
        const fetchUpdates = async () => {
            try {
                const response = await axios.post('http://lmw.local.com/api/realtime-test-event');
                setUpdates(response.data.message);
                console.log(response.data.message);
            } catch (error) {
                console.error('Error fetching updates:', error);
            }
        };

        const interval = setInterval(fetchUpdates, 5000);
       
        return () => {
            clearInterval(interval);
        };

    }, []);

    return (
        <div>
            <h1>Real-Time Page</h1>
            <p>
           {updates}
            </p>
        </div>
    );
};

export default RealtimePage;

```
Based on this project the endpoint is http://lmw.local.com/api/realtime-test-event but if you use your own domain alias of localhost then please update it as usual. 

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