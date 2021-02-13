# hotel-hamlet

A Laravel Monolith project powered by [InertiaJS](https://inertiajs.com/) and [Laravel Livewire](https://laravel-livewire.com/).

## Features
- Admin pages (powered by InertiaJS)
  - Manage hotels and rooms types
  - Set room availability
  - Modify/Cancel reservations
- Customer pages (powered by Livewire)
  - Hotel Landing Page
  - Room reservation
    - Create new reservations
    - View/Cancel existing reservation

## Tech Stack
- Laravel
- MySQL
- InertiaJS
- Laravel Livewire
- TailwindCSS

- Unit Testing powered by PHPUnit
- CI powered by Github Actions
- Hosted on Heroku

## Installation

```bash
$ git clone git@github.com:adwinying/hotel-hamlet.git
$ cd hotel-hamlet
$ composer install
$ npm ci
$ cp .example.env .env
$ php artisan key:generate
$ npm run prod
```
