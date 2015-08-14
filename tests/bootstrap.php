<?php
define('DS', DIRECTORY_SEPARATOR);
define('TMP', __DIR__ . DS . '..' . DS . 'tmp');

if (!is_dir(TMP)) {
    mkdir(TMP);
}
