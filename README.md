# Sinch Voice API PHP [![License](https://img.shields.io/:license-apache-blue.svg)](https://opensource.org/licenses/Apache-2.0)

Sinch PHP wrapper example for Voice API making a text to speech phone call with application signing.

## Overview

Covers Sinch Callout API

https://developers.sinch.com/docs/voice/api-reference/voice/tag/Callouts/

## Project setup

### PHP with CURL support

Download php for your platform with curl support enabled

## Supported PHP versions

8.1

### Use composer to install phpdotenv

To read your configuration from the .env file
Composer requires the following PHP packages:

    ```bash
    $ composer require vlucas/phpdotenv
    ```

### Configuring with your own application

Configure the `.env` file with your application credentials as well as your Sinch phone number assigned to your Voice app from your [dashboard](https://dashboard.sinch.com/voice/apps/) and the number you want to call:

    ```bash
    KEY="YOUR_application_key"
    SECRET="YOUR_application_secret"
    CLI="YOUR_Sinch_number"
    TO="YOUR_destination_phone_number"
    ```

### Running the script

Run your PHP script by executing one of the following commands:

    ```shell
    php callout.php
    php conference.php
    ```

The call-id will be returned.

    ```json
    {"callId":"8259ba9c-a7c7-464c-b63b-c9388addd0f2"}
    ```
