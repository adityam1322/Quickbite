<?php

use Laravel\Fortify\Features;

return [

   'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
   ],


];
