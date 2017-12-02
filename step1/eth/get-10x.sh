#! /bin/bash

OUTFILE_SEC=secretlist.json
OUTFILE_PUB=publiclist.json

echo [ > $OUTFILE_SEC
echo [ > $OUTFILE_PUB

for i in `seq 1 10000`;
do
    res=$(./ethereum-wallet-generator.sh)
    address="$(echo ${res} | awk '/address:/ {print $4}')"
    privkey="$(echo ${res} | awk '/private:/ {print $2}')"
    echo [ \"address\": \"${address}\" ], >> $OUTFILE_PUB
    echo [ \"address\": \"${address}\", \"private\": \"${privkey}\" ], >> $OUTFILE_SEC
done

echo ] >> $OUTFILE_SEC
echo ] >> $OUTFILE_PUB
