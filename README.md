# **phplx** Prize Raffle Console Application

[![Build Status](https://secure.travis-ci.org/phplx/prize-raffle-console-app.png?branch=master)](http://travis-ci.org/phplx/prize-raffle-console-app) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/phplx/prize-raffle-console-app/badges/quality-score.png?s=72524ae87dea871365424192e3d6c3c545f538f5)](https://scrutinizer-ci.com/g/phplx/prize-raffle-console-app/)

What this application do:

 * Get attendees by event from a Provider (actually only Eventbrite is available)
 * Load prizes from a **file**
 * Save **Event** and **Winners**
 * Raffle prizes
 * Tweet the winner with the respective prize

## How to use

```
# clone the repo
git clone git@github.com:phplx/prize-raffle-console-app.git
cd prize-raffle-console-app

# [Optional] Using Vagrant
vagrant up
vagrant ssh
cd /vagrant

# download composer
curl -sS https://getcomposer.org/installer | php
php composer.phar install -o --dev

# Run
php bin/phplx.php
# or
./bin/phplx

# Run tests
./vendor/bin/phpunit
```

## TODO

 * Add new commands like **listing all events**, **send email to winner**.
 * Add more **DataAdapters** and **Providers**

[![phplx](https://secure.gravatar.com/avatar/c67d21c0c2ba2be3bfe2c550039fc5d3?s=100)](http://phplx.net)

## LICENSE

Licensed under the [BSD LICENSE](https://github.com/phplx/prize-raffle-console-app/blob/master/LICENSE)
