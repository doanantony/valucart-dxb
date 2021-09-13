#!/bin/bash

cp -av env/${1:-local}.env .env
cp -av api/env/${1:-local}.env api/.env