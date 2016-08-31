# squabble comment server

[![Build Status](https://travis-ci.org/savaslabs/squabble.svg)](https://travis-ci.org/savaslabs/squabble)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/savaslabs/squabble/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/savaslabs/squabble/?branch=master)

Squabble is a comment server built on Lumen, using a SQLite backend for storing comment data.

It is built for use on http://savaslabs.com although you could easily adapt it for your own site, with a bit of time and server to spare.

### Installation for local development

Copy `docker-compose.local.example.yml` to `docker-compose.local.yml`. Add overrides for any environment variables which you need to override by copying the defaults set in `docker-compose.yml` and changing them in your local configuration file.

Run `docker build -t savaslabs/squabble:latest .` to rebuild the container if you've made any changes to `Dockerfile` or the files in the `docker` directory.

Run `make install`

### Installation for production

TBD

#### Local development

`make up`

The server is now listening at `http://localhost:8000`

#### Testing

`make test` will run phpunit tests

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
