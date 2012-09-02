<?php

// Staging alias
$aliases['s'] = array(
  'uri' => 'staging.durhambicyclecoalition.org',
  'root' => '/home/ubuntu/s',
  'path-aliases' => array(
    '%files' => 'sites/default/files/',
   ),
   'remote-host' => 'durhambicyclecoalition.org',
   'remote-user' => 'ubuntu',
  '%dump-dir' => '~/dumps/',
  '%dump' => '~/dumps/',
);

// Production alias
$aliases['p'] = array(
  'uri' => 'durhambicyclecoalition.org',
  'root' => '/home/ubuntu/p',
  'path-aliases' => array(
    '%files' => 'sites/default/files/',
   ),
   'remote-host' => 'durhambicyclecoalition.org',
   'remote-user' => 'ubuntu',
  '%dump-dir' => '~/dumps/',
  '%dump' => '~/dumps/',
);
