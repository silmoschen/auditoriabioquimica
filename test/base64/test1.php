<?php

$str = 'Hello World!!';
$b64 = base64_encode($str);

if ($b64 === false) {
  echo 'Invalid input';
} else {
  echo $b64; //-> "SGVsbG8gV29ybGQhIQ=="
}

?>
