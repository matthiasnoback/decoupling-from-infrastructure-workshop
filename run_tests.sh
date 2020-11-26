#!/usr/bin/env bash

set -e

vendor/bin/phpstan analyse
vendor/bin/phpunit --verbose --testdox --testsuite unit
vendor/bin/phpunit --verbose --testdox --testsuite use_cases
vendor/bin/phpunit --verbose --testdox --testsuite adapter
vendor/bin/behat --suite use_cases --tags="~@ignore" -vvv
vendor/bin/behat --suite end_to_end --tags="~@ignore" -vvv
