#Example payments app

## instructions for running


1. Copy config/env.example to config/.env
1. Add STRIPE_ACCOUNT_ID environment variable in .env file
1. Add STRIPE_SECRET environment variable in .env file
1. Run `docker-compose pull`
1. Run `docker-compose build`
1. Run `docker-compose up -d`
1. Run `docker exec app composer install`
1. The app should be available at http://localhost/users 
