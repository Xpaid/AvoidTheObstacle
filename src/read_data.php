<?php
$file = 'joystick_data.txt';

if (file_exists($file)) {
    echo trim(file_get_contents($file));
} else {
    echo 'No data';
}
