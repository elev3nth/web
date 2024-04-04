<?php

namespace Web\Locales;

class English {
  public function __construct() {
    return [
      'frontend' => [

      ],
      'backend'  => [
        'navbar'  => [
          'logout' => 'Logout'
        ],
        'sidebar' => [],
        'content' => [
          'errors' => [
            'no_app_found'       => 'No [%APP%] Found',
            'no_records_found'   => 'No Records Found',
            'not_configured'     => 'Is Not Configured',
            'app_not_configured' => 'Application Is Not Configured'
          ],
          'crud' => [
            'edit' => [
              'success'  => 'Successfully Updated [%RECORD%] in [%APP%]',
              'error'    => 'Failed Updating [%RECORD%] in [%APP%]',
              'fields'   => 'Please Correct/Change Fields In Red',
              'nochange' => 'No Changes to Update for [%RECORD%] in [%APP%]',
            ]
          ]
        ],
        'footer'  => []
      ]
    ];
  }
}
