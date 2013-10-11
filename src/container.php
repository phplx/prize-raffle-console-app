<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$container = new ContainerBuilder();

// Service for BuzzAdapter
$container->register('buzz.client', '%buzz.client%');
$container->register('buzz.message_factory', '%buzz.message_factory%');
$container->register('buzz', 'Buzz\Browser')
    ->setArguments(array(new Reference('buzz.client'), new Reference('buzz.message_factory')));

// Service for Eventbrite
$container->register('eventbrite.http_adapter', '%eventbrite.http_adapter.class%')
    ->setArguments(array('%eventbrite.http_adapter.argument%'));
$container->register('eventbrite', 'EventbriteApiConnector\Eventbrite')
    ->setArguments(array(new Reference('eventbrite.http_adapter'), '%eventbrite.api_keys%'));

// Service for DataAdapter
$container->register('data_adapter', '%data_adapter.class%');

// Service for Provider
$container->register('provider', '%provider.class%')
    ->setArguments(array('%provider.class.argument%'));

// Service for TwitterSocialHandler
$container->register('twitter_social_handler', '%twitter_social_handler.class%')
    ->setArguments(
        array(
             '%twitter_social_handler.consumer_key%',
             '%twitter_social_handler.consumer_secret%',
             '%twitter_social_handler.access_token%',
             '%twitter_social_handler.access_token_secret%'
        )
    );

return $container;