#! /bin/bash

echo '['
for i in `seq 1 10000`;
do
    ./ethereum-wallet-generator.sh
    echo ','
done
echo ']'