<?php

 
namespace App\Classe;
use \Mailjet\Client;
use \Mailjet\Resources;

class Mail
{
    private $api_key='97fdef6953ff5d9f30680cfe93459619';
    private $api_key_secret='b1e7821e7031595c0781186d4f8d76dc';

    public function send($to_email,$to_name,$subject,$content)
    {
        $mj=new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@djsem-electronics.com",
                        'Name' => "DJSEM-ELECTRONICS"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2621356,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    "Variables"=> [
                        'content'=> $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() ;
        
    }
}