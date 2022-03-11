# The Slim Framework Markdown-powered Blog

This is a, small, blog application powered by Markdown files with Yaml frontmatter. It uses the Slim Framework to glue everything together, as Slim is a light, nimble, and extremely flexible framework for web-based applications.

It was written as part of [a two-part series for the Twilio blog](https://www.twilio.com/blog/create-markdown-blog-php-slim-4).

## Prerequisites

Depending on how you use the application, your prerequisites will differ.

### Using Docker Compose

If you're using Docker Compose (I recommend [version 2](https://docs.docker.com/compose/cli-command/)), you only need, naturally Docker Compose, and Docker Engine. If you're not, then you will need the following:

- A [Memcached](https://memcached.org/) server
- [Composer](https://getcomposer.org/) installed globally.
- PHP 7.4+ (ideally version 8) with [the Memcached extension](https://www.php.net/manual/en/book.memcached.php) installed and enabled.

## Usage

To use the application, clone it locally and change into the cloned directory, by running the following commands.

```bash
git clone https://github.com/settermjd/slimphp-markdown-blog.git slim-framework-markdown-blog
cd slim-framework-markdown-blog
```

### Running the application

#### Using Docker Compose

To start the application using Docker Compose, run the command below.

```bash
docker-compose up -d
```

#### Not Using Docker Compose

If you're not using Docker Compose, you'll have to set up a virtual host in your web server of choice, which is outside the scope of this README.
