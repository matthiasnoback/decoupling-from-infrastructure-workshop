#!/usr/bin/env bash

set -eu

# First, analyze the code and see if it contains any obvious problems
vendor/bin/phpstan analyse

# Then run the unit tests, which should finish really quickly
vendor/bin/phpunit --verbose --testdox --testsuite unit

vendor/bin/phpunit --verbose --testdox --testsuite use_cases

# The adapter tests will be slower, and more prone to errors that have external causes
vendor/bin/phpunit --verbose --testdox --testsuite adapter

# The end-to-end tests run at the end: any easy-to-debug issue should have surfaced by now
vendor/bin/phpunit --verbose --testdox --testsuite end_to_end
