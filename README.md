# Time tracking app

A Symfony TEST TASK

-----------------------

**Initial Setup**

* git clone

```
composer install
setup DATABASE_URL in your .env file according your mysql host username and password.
create database : `timetracking` in your phpmyadmin
php bin/console doctrine:schema:update --force
```

**How to start the project**


go to the path of project in your terminal

run **`symfony server:start`** command

than in your terminal you can see that you should have to access the project using : https://127.0.0.1:8000/
