# JSONL to CSV stream downloader

## Description
A symfony console app for download remote JSONL to CSV format

## Prerequisites
* PHP 8
* Composer
* symfony-cli (optional)

## Installation
After clone this repo, run this command to install and run it
```shell
$ composer install 
```

For symfony-cli installation, please refer to this documentation : https://symfony.com/download

## Run
Within the document root, run this command to start download
```shell
$ php bin/console app:order-download 
```

The output file will be written to ./out.csv