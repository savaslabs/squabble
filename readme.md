# squabble comment server

[![Build Status](https://travis-ci.org/savaslabs/squabble.svg)](https://travis-ci.org/savaslabs/squabble)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/savaslabs/squabble/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/savaslabs/squabble/?branch=master)

Squabble is a comment server built on Lumen, using a SQLite backend for storing comment data.

It is built for use on http://savaslabs.com although you could easily adapt it for your own site, with a bit of time and server to spare.

### Installation

``` bash
cd /path/to/squabble
composer install
touch /path/to/squabble/storage/database.sqlite
php artisan migrate
```

**Important:**
 - Your site won't work without a `.env` file in the root of the repo. Please reference `env.example` for the contents of the `.env` file.
 - Make sure your `.env` and `database.sqlite` files are not named something other than `.env` and `database.sqlite`, as they will not fulfill their function otherwise.
 - Check if you need any other [requirements](https://lumen.laravel.com/docs/5.2) to run Lumen (e.g. If you're On Ubuntu, you may need to install php5-sqlite).
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
- `api/comments/delete/{id}/{token}` - Delete a comment by ID, using a unique token for that comment. The token is emailed out when the comment is saved.

### POST

The following route is defined:

- `api/comments/new` - Save a new comment.

Example POST request:

``` bash
curl -i -H 'Content-Type: application/json' -XPOST 'http://localhost:8000/api/comments/new' -d '{
    "name": "Some author",
    "email": "a@b.com",
    "comment": "A comment would go here",
    "slug": "2015/04/27/durham-restaurant-time-machine.html",
    "nocaptcha": "owl"
}' 
```
