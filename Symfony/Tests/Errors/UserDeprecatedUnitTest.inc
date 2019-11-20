<?php

trigger_error('deprecation', E_USER_DEPRECATED);

@trigger_error('deprecation', E_USER_DEPRECATED);

trigger_error('An error occurred.');

\trigger_error('deprecation', E_USER_DEPRECATED);

@\trigger_error('deprecation', E_USER_DEPRECATED);

\trigger_error('An error occurred.');

trigger_error('warning', E_WARNING);
