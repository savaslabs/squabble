# squabble comment server

[![Build Status](https://travis-ci.org/savaslabs/squabble.svg)](https://travis-ci.org/savaslabs/squabble)

Squabble is a comment server built on Lumen, using a SQLite backend for storing comment data.

It is built for use on http://savaslabs.com although you could easily adapt it for your own site, with a bit of time and server to spare.

### Installation

``` bash
cd /path/to/squabble
composer install
touch /path/to/squabble/storage/database.sqlite
php artisan migrate
```

Make sure you have a `.env` file in the root of the repo with these contents:

```
APP_ENV=local
APP_DEBUG=true
APP_KEY=abracadabra

CACHE_DRIVER=file
SESSION_DRIVER=file

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

DB_CONNECTION=sqlite
DB_HOST=localhost
```

#### Local development

``` bash
cd /path/to/squabble
php artisan serve
```

The server is now listening at `http://localhost:8000`

## API

### GET

The following routes are defined:

- `api/comments` - Returns an array of all comments stored on the server
- `api/comments/id/{id}` - Get a comment by numeric ID
- `api/comments/post/{slug}` - Get a comment by the URL of the post it's associated with
- `api/comments/count` - Get an array of comment count per posts

### POST

The following route is defined:

- `api/comments/new` - Save a new comment.

Example POST request:

``` bash
curl -i -H 'Content-Type: application/json' -XPOST 'http://localhost:8000/api/comments/new' -d '{
    "name": "Some author",
    "email": "a@b.com",
    "comment": "A comment would go here",
    "slug": "2015/04/27/durham-restaurant-time-machine.html"
}' 
```
