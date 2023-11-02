<?php

namespace Web\Locales;

class English {
  public function __construct() {
    return [
      'frontend' => [

      ],
      'backend'  => [
        'navbar'  => [],
        'sidebar' => [],
        'content' => [
          'errors' => [
            'no_app_found'       => 'No [%APP%] Found',
            'no_records_found'   => 'No Records Found',
            'not_configured'     => 'Is Not Configured',
            'app_not_configured' => 'Application Is Not Configured'
          ]
        ],
        'footer'  => []
      ]
    ];
  }
}
