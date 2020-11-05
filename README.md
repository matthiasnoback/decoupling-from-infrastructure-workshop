# Decoupling from infrastructure

[![Build Status](https://travis-ci.org/matthiasnoback/decoupling-from-infrastructure-workshop.svg?branch=master)](https://travis-ci.org/matthiasnoback/decoupling-from-infrastructure-workshop)

### Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Getting started

- Set up environment variables `HOST_UID` and `HOST_GID`:

    ~~~bash
    export HOST_UID=$(id -u)
    export HOST_GID=$(id -g)
    ~~~

- Clone this repository and `cd` into it.
- Copy `.env.dist` to `.env` (set the API key to `undefined` for now)
- Run `docker-compose pull`.
- Run `bin/composer install --prefer-dist` to install the project's dependencies.

### Usage

- Run `bin/composer` to use Composer (e.g. `bin/composer require --dev symfony/var-dumper`).
- Run `bin/run_tests` to run the tests.

## Sharing patches

If you want to share your work with the group, make sure you _stage_ any file you want to share. You can do this with `git add [filename]` on the command line. Make sure to also stage renamed and deleted files that you want to share. When you're done, run:

```bash
git diff > [name-of-the-patch].patch
```

Now share this patch via email, Slack, or whatever.

Someone else who has push rights (the workshop leader?) should apply this patch to the latest version of the code:

```bash
git apply [name-of-the-patch].patch
```

They should then add everything, commit it, and push it to the main branch.
