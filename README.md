# Form server

This is a PHP app that generates HTML forms from YAML descriptions.

It can save and/or email the submitted data.

## Use cases

### Save data

This is the default behavior of the app. The goal is to gather user-specific data. The initial submissions may be incomplete and can be updated at a later time without the need for additional authentication mechanisms or databases.

Each form is accessed via a direct link, which contains the form ID (form directory).

### Do not persist, only send

If the save button is disabled in [form configuration](doc/meta.md), no data is persisted. Each submission  is completely independent of each other. Multiple users can safely access the same form.

## Usage

All forms are served from subdirectories of `data/`.

Create a `config.yaml` file in a `<data-subdirectory>`. The form will be available at: `https://your.server/<data-subdirectory>`

When the user submits the form, all input values are saved in the form directory as `values.yaml` If the user clicked on `send` and all the inputs are valid, an email is sent to configured addresses.

The [reference](doc/index.md) details all available options. A demo form is provided as `EXAMPLE` directory.

## Requirements

* PHP 7.2
* mail server

## Current limitations

 * The YAML files are not validated. You will see no warnings if your configuration cannot be translated into an HTML form.

## Install

End users can simply download the [current release](https://github.com/cosmocode/formserver/releases) `formserver.zip`.

Unzip the file and point your PHP enabled webserver to the `public` directory. For Apache a rewrite configuration is provided in a `.htaccess` file. Users of other webservers may need to setup URL rewriting manually.



## Development

Clone the repository and install the dependencies with

```bash
composer install
```

You can test run on the built-in PHP server

```bash
composer start
```
