<?php
namespace App\Classe;

class Sms
{
    public function send($Receivernumber,$sms)
    {
        $api_key = 'MjAxNTAwOTQ6dHA0NjQ5VDIwIw==';
        $url = 'https://app.techsoft-web-agency.com/sms/api';

        /*
            Inclure les deux première étape ici (authentification et lien api)
        */
// Etape 3: Le sender ID ou nom d'envoi (11 caractères, espaces compris). ATTENTION: Le sender id doit être enregistré et validé (Menu ID expediteur / Nom d'envoi) dans votre compte sinon une erreur sera généré.
        $from = 'TECHSOF-SMS';

//Etape 4: precisez le numéro de téléphone (Format international)


// Construire le corps de la requête
        $sms_body = array(
            'action' => 'send-sms',
            'api_key' => $api_key,
            'to' => $Receivernumber,
            'from' => $from,
            'sms' => $sms
        );

        $send_data = http_build_query($sms_body);
        $gateway_url = $url . "?" . $send_data;


        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $gateway_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            $output = curl_exec($ch);

            if (curl_errno($ch)) {
                $output = curl_error($ch);
            }
            curl_close($ch);

            var_dump($output);

        }catch (Exception $exception){
            echo $exception->getMessage();
        }

    }

}
