# creatiff-api
API for my bachelor thesis, created using Symfony and API Platform. It resembles social platform, where you can register 
and create posts in different format. You can also react and comment posts.

## Installation
1. Clone this repo
2. Run command `docker compose up -d`,which will pull and create containers with nginx, mysql and php with xdebug  
3. Create .env.local with proper mappings, especially for database 
4. Visit localhost:8080/api/docs, where you can see documentation for all the endpoints

## Tests
In app directory run:
* `composer test:unit` to run unit tests
* `composer test e2e` to run end to end tests
* `composer lint` to run static code analysis 
