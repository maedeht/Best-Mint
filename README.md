# Best-Mint
Laravel JWT Authentication and product management
# Docker run
```docker-compose build```

```docker-compose up -d```
# Migrate and Seed
```docker-compose exec app php artisan migrate --seed```
# Run Phpunit tests
```docker-compose exec app php ./vendor/bin/phpunit```
# Endpoints
## Registration
POST `register`
### Requirements
`name`,

`email`,

`password`,

`password_confirmation`
### Response
#### Errors
`422`, if required parameters have not been sent
## Login
POST `login`
### Requirements
`email`,

`password`
### Response
#### Errors
`422`, if required parameters have not been sent

`401`, if email or password can not be found
## Products List
GET `products`
### Optional parameter
`filter`
## Import products list
POST `products`
### Requirements
`Bearer token`,

`file`
### Response
#### Errors
`422`, if required parameters have not been sent

`401`, if bearer token is not valid

`403`, if bearer token owner has admin role
## Save products
### Requirements
`Bearer token`,

`title`,

`price`,

`qty`,

`category`
### Response
#### Errors
`422`, if required parameters have not been sent

`401`, if bearer token is not valid
