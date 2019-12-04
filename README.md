# laravel-passport-auth

### Laravel Passport Auth API, authentication using email and username

1. Composer install
2. rename .env.example to .env and update credentials
3. Run migration
3. Install laravel [passport](https://laravel.com/docs/master/passport)
4. Again run migration
5. Create passport client

#### What you can do using this repo

1. You can register user [/api/register](http://localhost:8000/api/register)

2. You can login using email and username [/api/login](http://localhost:8000/api/login)

3. You can change password [/api/change-password](http://localhost:8000/api/change-password)

4. You can logout user [/api/logout](http://localhost:8000/api/logout)

4. You can get logged in user using token [/api/user](http://localhost:8000/api/user)

5. You can add user using artisan command [add:user](#) with email and username
	- Ex. php artisan add:user useremail@email.com userusername

6. You can add user using seeder or factory [db:seed](#) 
	- Ex. php artisan db:seed
	
7. You can test using unit testing (php vendor/phpunit/phpunit/phpunit)