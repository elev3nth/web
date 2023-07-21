<?php

declare(strict_types=1);

namespace Web\Models;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class Contact {

  private $api;

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

  public function SendMail() {

    if ($this->api['payload']['body']['nme'] &&
        $this->api['payload']['body']['eml'] &&
        $this->api['payload']['body']['sbj'] &&
        $this->api['payload']['body']['msg'] &&
        $this->api['payload']['body']['tkn']) {

      $recaptchav2 = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=".
        $this->api['env']['gr_secretk']."&response=".
        $this->api['payload']['body']['tkn']
      );
      $recaptchav2 = json_decode($recaptchav2, true);

      if ($recaptchav2['success'] === true) {

        $mail = new PHPMailer(true);
        try {
            if (trim(strtolower($this->api['client']['client_domain'])) == 'fbac.basketball') {
              $mail->setFrom('enquiry@fbac.basketball', 'Enquiry');
              $mail->addAddress('president@fbac.basketball', 'President FBA Christchurch');
              $mail->addCC('vp@fbac.basketball', 'Vice-President FBA Christchurch');
              $mail->addReplyTo(trim($this->api['payload']['body']['eml']), trim($this->api['payload']['body']['nme']));
            }else{
              $mail->setFrom('enquiry@celedonio.digital', 'Enquiry');
              $mail->addAddress('allan@celedonio.digital', 'Allan Celedonio');
              $mail->addReplyTo(trim($this->api['payload']['body']['eml']), trim($this->api['payload']['body']['nme']));
            }
            $mail->isHTML(true);
            $mail->Subject = $this->api['payload']['body']['sbj'];
            $mail->Body    = '<pre>'.$this->api['payload']['body']['msg'].'</pre>';
            $mail->AltBody = $this->api['payload']['body']['msg'];
            $mail->send();
            return [
              'success' => true,
              'message' => 'Enquiry Sent. We will reply as soon as possible, Thank you.'
            ];
        } catch (Exception $e) {
          return [
            'error'   => true,
            'message' => 'Failed Sending Enquiry Message'
          ];
        }

      }else{
        return [
          'error'   => true,
          'message' => 'Recaptcha Was Incorrect'
        ];
      }

    }

    return [
      'error'   => true,
      'message' => 'All Fields Are Required'
    ];

  }

}
