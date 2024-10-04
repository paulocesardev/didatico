#!/bin/bash

if [[ $(docker ps -aq --filter expose=80) ]]; then 
    docker stop $(docker ps -aq --filter expose=80)
fi

if [[ $(docker ps -aq --filter expose=5432) ]]; then 
    docker stop $(docker ps -aq --filter expose=5432)
fi

