# Nova Slack Channel Select Field Package

This Nova Package is a searchable select for storing your slack channel information in a database field.

## Installation

You can install the package in to a Laravel app that uses Nova via composer:

```bash
composer require brandonjbegle/nova4-slack-channel-field
```

Now publish config file:

```shell
php artisan vendor:publish --provider="BrandonJBegle\SlackChannel\FieldServiceProvider"
```

Create a Slack App, install it to your workspace, and retrieve your bot token.
[Complete Instructions Here](./docs/SLACK.md)

Add the key and token to your `.env` file

```shell
SLACK_OAUTH_TOKEN=############################
```


## Usage

Add the field to your Nova Resource:

```php
use BrandonJBegle\SlackChannel\SlackChannel;
// ....

SlackChannel::make('Slack Channel'),

// Specify channel types https://api.slack.com/methods/conversations.list#arg_types
SlackChannel::make('Slack Channel')
          ->types('public_channel,private_channel'),
```


