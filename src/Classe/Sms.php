<?php
namespace App\Classe;

class Sms
{
    public function send($content,$number)
    {

        //config
        $url        = 'https://api.allmysms.com/http/9.0/sendSms/';
        $login      = 'djsemelectronics';                              //votre identifiant allmysms
        $apiKey     = '70fef89e052bedf';                             //votre clé d'API allmysms
        $message    = $content;    //le message SMS
        $sender     = 'DJSEM';                               //l’expéditeur, attention pas plus de 11 caractères alphanumériques
        $msisdn     = $number;                            //numéro de téléphone du destinataire
        $smsData    = "<DATA>
           <MESSAGE><![CDATA[" . $message . "]]></MESSAGE>
           <TPOA>" . $sender . "</TPOA>
           <SMS>
              <MOBILEPHONE>" . $msisdn . "</MOBILEPHONE>
           </SMS>
        </DATA>";

        $fields = array(
            'login'    => $login,
            'apiKey'   => $apiKey,
            'smsData'  => $smsData,
        );

        $fieldsString = http_build_query($fields);

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);    // permet d’éviter le temps d'attente par défaut : 300 sec - optionnel
            curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024); // limite de detection des connexions lentes, en octets/sec (ici : 1 ko) - optionnel
            curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 1);     // coupe la connexion si en dessous de CURLOPT_LOW_SPEED_LIMIT pendant plus de CURLOPT_LOW_SPEED_TIME - optionnel

            $result = curl_exec($ch);

            //echo $result;

            curl_close($ch);

        } catch (Exception $e) {
            echo 'Api allmysms injoignable ou trop longue a repondre ' . $e->getMessage();
        }

    }

}
