<?php

$seconds = 60 * 60; // 1 hour

header("HTTP/1.1 503 Service Temporarily Unavailable");
header("Status: 503 Service Temporarily Unavailable");
header("Retry-After: $seconds");

?>

<h1>Looks like the Sky is falling.</h1>
<p>Service Unavailable</p>
