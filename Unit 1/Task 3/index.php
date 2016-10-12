<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

$filePath = __DIR__.'/data/counter.txt';
// начало критической области
$num = file_get_contents($filePath);
echo "Hello counter! " . $num;
usleep(6000);
file_put_contents($filePath, ++$num);
// окончание критической области
file_put_contents(__DIR__."/data/uniq", uniqid("prefix")."\n", FILE_APPEND);
