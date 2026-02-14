<?php
$hash = '$2y$12$JxpAHVKYFFy75tIGWy.XZeZ0C0Er5bP.GQU0/H8nLwvkcbNkT/Uw.';

var_dump(password_verify("dakar2026", $hash));
var_dump(password_verify("Dakar2026", $hash));
