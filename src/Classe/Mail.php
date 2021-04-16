<?php

 
namespace App\Classe;
use \Mailjet\Client;
use \Mailjet\Resources;

class Mail
{
    private $api_key='6f8c564aa319cbf71d3f423240d5f8f2';
    private $api_key_secret='e18823f2b6859e46422bbc92c319fe45';

    public function send($to_email,$to_name,$subject,$content)
    {



  $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
  $body = [
    'Messages' => [
      [
        'From' => [
          'Email' => "contact@djsem-electronic.com",
          'Name' => "DJSEM Electronic"
        ],
        'To' => [
          [
            'Email' => $to_email,
            'Name' => $to_name
          ]
        ],
        'TemplateID' => 2814487,
        'TemplateLanguage' => true,
        'Subject' => "DJSEM Electronic",
        'Variables' =>[
            'content'=>$content
        ]
      ]
    ]
  ];
  $response = $mj->post(Resources::$Email, ['body' => $body]);
  $response->success();







    }
}