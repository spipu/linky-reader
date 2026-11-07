#!/bin/sh

echo "Starting Service - SSH"
/etc/init.d/ssh     start > /dev/null
echo ""

echo "Starting Service - Apache2"
/etc/init.d/apache2 start > /dev/null
echo ""

echo "Starting Service - MySQL"
/etc/init.d/mysql   start > /dev/null
echo ""
