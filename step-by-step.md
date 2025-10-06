## Intro

Once upon a time, web development was productive and didn't require a steep learning curve.
A brave developer could get a project running within a couple days.
The workshop is aimed to developers who are starting with the framework, or never used it.

## The problem

* the problem: Hotel reservation system
* database schema
  * rooms, bookings, etc

## Project setup

* project setup, composer

```
composer create-project cakephp/app cakefest2025
```

* ddev setup
  * PHP 8.4
* let's check the folder structure
dbdump import

```
ddev import-db FILE
ddev add-on get ddev/ddev-phpmyadmin
ddev phpmyadmin
```

