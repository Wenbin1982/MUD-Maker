## Requirements
- - - -
1. Use php version **>=7.0.0**
2. Use node version **8.5.0**
3. Use redis server version  **3.0.5**

## Build
- - - -
1. Make **$> npm install** in terminal
2. Make **$> composer install** in terminal
3. Create in root folder **.env.php**
   a. Insert code in **.env** file:
```
   APP_NAME=MumMaker
   APP_ENV=local
   APP_KEY=base64:1lCj6ARwqspRA4OQRVV/SalSEPBpQ9s4mGAlxcRwQYo=
   APP_DEBUG=true
   APP_URL=http://localhost
   
   LOG_CHANNEL=stack
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mudmaker
   DB_USERNAME=root
   DB_PASSWORD=
   
   BROADCAST_DRIVER=log
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   QUEUE_DRIVER=redis
   SESSION_LIFETIME=120
   
   
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   
   MAIL_DRIVER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=
   MAIL_PASSWORD=
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="no-reply@mud-maker.cisco.com"
   MAIL_FROM_NAME="Mud maker"
   

```
4. $> php artisan config:clear



## Usage
- - - -
1. Make **$> php artisan serve** command in terminal
2. Make **$> php artisan queue:work** command in terminal
3. Make **$> npm run watch** command in terminal
4. Open **http://localhost:8000** in your browser
