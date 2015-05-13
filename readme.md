# Qusdis comment server

Qusdis (no hints on pronunciation provided) is a comment server built on Lumen, using a SQLite backend for storing comment data.

It is built for use on http://savaslabs.com although you could easily adapt it for your own site, with a bit of time and server to spare.

### Installation

`cd /path/to/qusdis`
`composer install`

#### Local development

`cd /path/to/qusdis`
`php artisan serve`

The server is now listening at `http://localhost:8000`

## API

### GET

The following routes are defined:

- `api/comments` - Returns an array of all comments stored on the server
- `api/comments/id/{id}` - Get a comment by numeric ID
- `api/comments/post/{slug}` - Get a comment by the URL of the post it's associated with
- `api/comments/count` - Get an array of comment count per posts

### POST

The follwoing route is defined:

- `api/comments/new` - Save a new comment.

Example POST request:

``` bash
curl -i -H 'Content-Type: application/json' -XPOST 'http://localhost:8000/api/comments/new' -d '{
    "name": "Some author",
    "title": "The subject line of the comment",
    "email": "a@b.com",
    "comment": "A comment would go here",
    "slug": "2015/04/27/durham-restaurant-time-machine.html"
}' 
```
