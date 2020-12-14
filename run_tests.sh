#!/usr/bin/env bash

set -e

preferred_tool="${1-phpunit}"

# First, analyze the code and see if it contains any obvious problems
vendor/bin/phpstan analyse

# Then run the unit tests, which should finish really quickly
vendor/bin/phpunit --verbose --testdox --testsuite unit

# The use case tests will take a little more time to complete, but they should still give us fast feedback
if [ "$preferred_tool" == "behat" ]; then
  vendor/bin/behat --suite use_cases --tags="~@ignore" -vvv
else
  vendor/bin/phpunit --verbose --testdox --testsuite use_cases
fi;

# The adapter tests will be slower, and more prone to errors that have external causes
vendor/bin/phpunit --verbose --testdox --testsuite adapter

# The end-to-end tests run at the end: any easy-to-debug issue should have surfaced by now
if [ "$preferred_tool" == "behat" ]; then
  vendor/bin/behat --suite end_to_end --tags="~@ignore" -vvv
else
  vendor/bin/phpunit --verbose --testdox --testsuite end_to_end
fi;
