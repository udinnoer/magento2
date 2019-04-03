#!/bin/bash

NOW=$(date +"%T")

printf "%25s\n" | tr " " -

sudo php bin/magento maintenance:enable
echo "maintenance enable"

sudo php bin/magento setup:upgrade
NOW=$(date +"%T")
echo "setup:upgrade done! $NOW"

printf "%25s\n" | tr " " -

# sudo php -dmemory_limit=5G bin/magento setup:di:compile
NOW=$(date +"%T")
echo "setup:di:compile done $NOW"

printf "%25s\n" | tr " " -

sudo php -dmemory_limit=5G bin/magento setup:static-content:deploy -f
NOW=$(date +"%T")
echo "deploy done! $NOW"

printf "%25s\n" | tr " " -

echo "Ready mas bro!!!"

printf "%25s\n" | tr " " -

echo "check permission issue bro!!!"

printf "%25s\n" | tr " " -

sudo php bin/magento maintenance:disable

printf "%25s\n" | tr " " -

sudo php bin/magento maintenance:status

printf "%25s\n" | tr " " -

sudo chmod -R 777 generated/*
sudo chmod -R 777 var/*
sudo chmod -R 777 pub/*

NOW=$(date +"%T")
echo "deploy & set permision  done! $NOW"
