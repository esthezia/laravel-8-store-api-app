### A Laravel 8 Store API application

To get started, configure your database connection in the `.env` file, as well as the test database in the `phpunit.xml` file, then open the console and run:

```
php artisan migrate
php artisan db:seed
```

For running the tests, open the console and run:

```
php artisan test
```

**NOTE 1**: For endpoints that require authentication, a custom `AuthToken` header was used. This is the token associated with the user making the request.

**NOTE 2**: In the root folder, you will also find a Postman collection export file (`Store API.postman_collection.json`), to be easier to test the `POST`, `PATCH`, and `DELETE` requests included in the app. Just import it in your workspace, and you're good to go! But make sure you edit the parameters (the custom `AuthToken` header, and the product IDs) to match your specific data, as was generated by the seeder.

Enjoy! :)
