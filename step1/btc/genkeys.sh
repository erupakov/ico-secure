#! /bin/bash
OUTFILE_SEC=secretlist.json
OUTFILE_PUB=publiclist.json

echo [ > $OUTFILE_SEC
echo [ > $OUTFILE_PUB
for i in `seq 1 10000`; do
    res=`./vanitygen 1`
    address="$(echo ${res} | awk '/Address:/ {print $5}')"
    privkey="$(echo ${res} | awk '/Privkey:/ {print $7}')"
    echo [ \"address\": \"${address}\" ], >> $OUTFILE_PUB
    echo [ \"address\": \"${address}\", \"private\": \"${privkey}\" ], >> $OUTFILE_SEC
done

echo ] >> $OUTFILE_SEC
echo ] >> $OUTFILE_PUB
