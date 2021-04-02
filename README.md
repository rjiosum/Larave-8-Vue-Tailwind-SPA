# Laravel Vue SPA boiler plate

[![API Tests](https://github.com/rjiosum/Larave-8-Vue-Tailwind-SPA/actions/workflows/api-test.yml/badge.svg)](https://github.com/rjiosum/Larave-8-Vue-Tailwind-SPA/actions/workflows/api-test.yml)

Laravel Vue Tailwind single page application boiler plate using laravel passport authentication with passport cookies.

Laravel 8.12, laravolt avatar 4.1, intervention image 2.5, laravel passport 10.1, vue 2.6.12, vue-router 3.5.1, 
vuelidate 0.7.6, vuex 3.6.2, tailwindcss 2.0.4.
  
 
### Prerequisites
```
 Make sure to use a version of php >= 7.3.9 (php -v).
 Make sure you have composer installed.
 Make sure you have npm installled.   
```

### Features

- Frontend built with [Tailwind](https://tailwindcss.com/) utility-first CSS framework.
- Pages - landing, login, register, forgot password. 
- Email verification (To enable email verification verify that App\Models\User model implements the Illuminate\Contracts\Auth\MustVerifyEmail contract.) 
- User dashboard, user update profile, user update password.
- User update avatar using package [laravolt avatar](https://github.com/laravolt/avatar) and [intervention image](http://image.intervention.io/) 
- User address book and articles CRUD with [CKEditor](https://ckeditor.com/ckeditor-5/) 
- Client-side form validation with [vuelidate](https://github.com/vuelidate/vuelidate)
- Laravel Passport Authentication.
- PHPUnit test for all the features.
- Laravel dusk test for frontend UI. 


### How to use
- Download (as zip) and extract or git clone the project under your web server's root directory.
 
 - Create a file .env using .env.example. Update the files - set app url, database connection, mail connection and laravel passport details.
 
- Run `php artisan key:generate`

- Install dependencies with `Composer` first:
  ```bash
  $ composer install
  ```

- Create two databases one for app (e.g boilerplate) and one for app testing (e.g boilerplate_testing).

- Create a file .env.dusk.local using .env. Set 'DEBUGBAR_ENABLED=false' and add DUSK_HEADLESS_DISABLED=true.

- Run `php artisan storage:link` and `php artisan passport:install`.
   
- Install front-end dependencies with `npm`:
  ```bash
  $ npm install
  ``` 

- Run `php artisan db:seed`, this will create two users and few related articles for each user. 
  You can use `email: john@gmail.com`, `pwd: password` to login and play with user section of the app.
  
- To run the tests use `phpunit`:   
  ```bash
  $ ./vendor/bin/phpunit --testdox tests
  ```

- To run the ui tests use `dusk`:   
    ```bash
    $ php artisan dusk --testdox
    ```

 ### DEMO
 ![Laravel Vue SPA boiler plate Demo](Demo01.gif) 
