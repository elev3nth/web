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
        'tabs'    => [
          'sorting'  => 'Sorting',
          'auditing' => 'Auditing'
        ],
        'content' => [
          'errors' => [
            'no_app_found'       => 'No [%APP%] Found',
            'no_records_found'   => 'No Records Found',
            'not_configured'     => 'Is Not Configured',
            'app_not_configured' => 'Application Is Not Configured'
          ],
          'legend' => [
            'title'    => 'Legend',
            'required' => '* Required Field',
            'unique'   => '** Unique Field'
          ],
          'crud' => [
            'create' => [
              'created'  => 'Successfully Created [%RECORD%] in [%APP%]',
              'validate' => 'Creation Error For Fields In Red',
              'error'    => 'Failed Creating [%RECORD%] in [%APP%]',
            ],
            'edit' => [
              'success'  => 'Successfully Updated [%RECORD%] in [%APP%]',
              'error'    => 'Failed Updating [%RECORD%] in [%APP%]',
              'validate' => 'Update Error For Fields In Red',
              'nochange' => 'No Changes to Update for [%RECORD%] in [%APP%]',
            ]
          ]
        ],
        'footer'  => []
      ]
    ];
  }
}
