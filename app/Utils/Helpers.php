<?php

use Hashids\Hashids;
use ClickSend\Api\SMSApi;
use ClickSend\Configuration;
use ClickSend\Model\SmsMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use ClickSend\Model\SmsMessageCollection;
use App\Http\Controllers\SysLogController;
use App\Http\Controllers\NotificationController;
use Paydunya\Setup;
use Paydunya\Checkout\Store;
use Paydunya\Checkout\CheckoutInvoice;


const UPPER_TOKEN = 'upper';
const LOWER_TOKEN = 'lower';
const CAMEL_TOKEN  = 'camel';
const DIGIT_TOKEN = 'digits';

const SYS_LOG_INFO = 'INFO';
const SYS_LOG_WARNING = 'WARNING';
const SYS_LOG_ERROR = 'ERROR';
const SYS_LOG_SUCCESS = 'SUCCESS';

const STRING_DAY_DIGIT_DAY_MONTH_YEAR = 0; // Vendredi, 15 Novembre XXX
const DIGIT_DAY_MONTH_YEAR = 1; // 12 Novembre XXX
const DIGIT_DAY_MONTH = 6; // 12 Novembre
const MONTH_YEAR = 5; // Novembre XXXX
const STRING_DAY = 2; // Vendredi
const STRING_MONTH = 3; // Novembre
const STRING_DAY_DIGIT_DAY_MONTH = 4; // Vendredi, 15 Novembre


if (!function_exists('getTimeAgo')) {
    /**
     * @param $date
     * @param string $prefix
     * @return string
     */
    function getTimeAgo($date, string $prefix = "Il y a"): string
    {
        $timestamp = strtotime($date);
        $formated_timeAgo = $date;

        $fr_strTime = ["seconde", "minute", "heure", "jour", "mois", "an"];
        $length = ["60", "60", "24", "30", "12", "10"];

        $currentTime = time();
        if ($currentTime >= $timestamp) {
            $diff = time() - $timestamp;
            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) $diff = $diff / $length[$i];

            $diff = round($diff);

            if ($diff > 1) $formated_timeAgo = $prefix . " " . $diff . " " . ($fr_strTime[$i] != 'mois' ? $fr_strTime[$i] . "s" : $fr_strTime[$i]);
            else $formated_timeAgo = $prefix . " " . $diff . " " . $fr_strTime[$i];
        }

        return $formated_timeAgo;
    }
}

if (!function_exists('getAppName')) {
    /**
     * @param false $uppercase
     * @param null $suffix
     * @return false|string|string[]
     */
    function getAppName(bool $uppercase = false, $suffix = null)
    {
        return $uppercase ? mb_strtoupper(config('app.name') . $suffix) : config('app.name') . $suffix;
    }
}

if (!function_exists('isActiveLink')) {
    /**
     * @param $routes
     * @param string $class
     * @return mixed|string
     */
    function isActiveLink($routes, string $class = "current"): string
    {
        $isActive = '';
        $route = Route::current();
        if ($route) for ($i = 0; $i < sizeof($routes); $i++) if ($route->getName() == $routes[$i]) $isActive = $class;
        return $isActive;
    }
}

if (!function_exists('generateToken')) {
    /**
     * @param int $length
     * @param string $case
     * @return false|string
     */
    function generateToken(int $length = 10, string $case = CAMEL_TOKEN)
    {
        $chars = [UPPER_TOKEN => 'AZERTYUIOPQSDFGHJKLMWXCVBN0123456789', LOWER_TOKEN => 'azertyuiopqsdfghjklmwxcvbn0123456789', CAMEL_TOKEN => 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789', DIGIT_TOKEN => '0123456789'];
        return substr(str_shuffle(str_repeat($chars[$case], $length)), 0, $length);
    }
}

if (!function_exists('encodeId')) {
    /**
     * @param $integerId
     * @return string
     */
    function encodeId($integerId): string
    {
        try {
            $hashids = new Hashids('Youpigoo', 10);
            return $hashids->encode($integerId);
        } catch (Exception $ignore) {
            return '';
        }
    }
}


if (!function_exists('decodeId')) {
    /**
     * @param $hashedId
     * @param int $index
     * @return array|mixed|null
     */
    function decodeId($hashedId, int $index = 0): int
    {
        if (is_null($hashedId)) return 0;
        try {
            $hashids = new Hashids('Youpigoo', 10);
            return !is_null($index) ? $hashids->decode($hashedId)[$index] : $hashids->decode($hashedId);
        } catch (Exception $ignore) {
            return 0;
        }
    }
}


if (!function_exists('formatDate')) {
    /**
     * @param $format
     * @param null $date
     * @param false $getAlsoTime
     * @return string
     */
    function formatDate($format, $date = null, bool $getAlsoTime = false): string
    {
        $date = is_null($date) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($date));

        $months = explode('_', 'Janvier_Février_Mars_Avril_Mai_Juin_Juillet_Août_Septembre_Octobre_Novembre_Décembre');
        $weekdays = explode('_', 'Lundi_Mardi_Mercredi_Jeudi_Vendredi_Samedi_Dimanche');
        $hours = ' à ' . date('H:i:s', strtotime($date));

        $formatedDate = null;
        $month_position = date('n', strtotime($date)) - 1;
        $day_position = date('N', strtotime($date)) - 1;
        $input_year = date('Y', strtotime($date));
        $input_day = date('d', strtotime($date));

        switch ($format) {
            case STRING_DAY_DIGIT_DAY_MONTH_YEAR:
                $formatedDate = $weekdays[$day_position] . ', ' . $input_day . ' ' . $months[$month_position] . ' ' . $input_year;
                break;
            case DIGIT_DAY_MONTH_YEAR:
                $formatedDate = $input_day . ' ' . $months[$month_position] . ' ' . $input_year;
                break;
            case STRING_DAY:
                $formatedDate = $weekdays[$day_position];
                break;
            case STRING_MONTH:
                $formatedDate = $months[$month_position];
                break;

            case STRING_DAY_DIGIT_DAY_MONTH:
                $formatedDate = $weekdays[$day_position] . ', ' . $input_day . ' ' . $months[$month_position];
                break;

            case MONTH_YEAR:
                $formatedDate = $months[$month_position] . ' ' . $input_year;
                break;

            case DIGIT_DAY_MONTH:
                $formatedDate = $input_day . ' ' . $months[$month_position];
                break;
        }

        return $formatedDate . ($getAlsoTime ? $hours : '');
    }
}

// if (!function_exists('sendNotificationInApp')) {
//     /**
//      * @param string $title
//      * @param string $content
//      * @param string $receiver
//      * @param string $sender = null
//      * @param string $callback = null
//      */
//     function sendNotificationInApp($title, $content, $receiver, $sender = null, $callback = null)
//     {
//         NotificationController::storeNotification($title, $content, $receiver, $sender, $callback);
//     }
// }

if (!function_exists('payementByCinetPay')) {
    /**
     * @param string $title
     */
    function payementByCinetPay($amount, $transaction_id, $user_id)
    {
        try {
            //$depot = Depot::where('token_depot', $transaction_id)->first();
            $url = "https://api-checkout.cinetpay.com/v2/payment";
            $params = [
                "amount" => $amount,
                "currency" => "XOF",
                "apikey" => "13760649056213b99a565d58.80647257",
                "site_id" => "915591",
                "transaction_id" => $transaction_id,
                "description" => "TRANSACTION DESCRIPTION",
                "return_url" => route('view.retour.traitement', [
                    'auth_identifier' => Auth::id(),
                    'transaction_id' => $transaction_id
                ]),
                "notify_url" => route('view.retour.traitement', [
                    'auth_identifier' => Auth::id(),
                    'transaction_id' => $transaction_id
                ]),
                "customer_id" => $user_id,
                "channels" => "ALL"
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "content-type:application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            throw new Exception("Error :" . $e->getMessage());
        }
        return null;
    }
}

if (!function_exists('payementMobileByCinetPay')) {
    /**
     * @param string $title
     */
    function payementMobileByCinetPay($amount, $transaction_id, $user_id)
    {
        try {
            //$depot = Depot::where('token_depot', $transaction_id)->first();
            $url = "https://api-checkout.cinetpay.com/v2/payment";
            $params = [
                "amount" => $amount,
                "currency" => "XOF",
                "apikey" => "13760649056213b99a565d58.80647257",
                "site_id" => "915591",
                "transaction_id" => $transaction_id,
                "description" => "TRANSACTION DESCRIPTION",
                "return_url" => route('redirection.apres.payement', [
                    'transaction_id' => $transaction_id
                ]),
                "notify_url" => route('redirection.apres.payement', [
                    'transaction_id' => $transaction_id
                ]),
                "customer_id" => $user_id,
                "channels" => "ALL"
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "content-type:application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            throw new Exception("Error :" . $e->getMessage());
        }
        return null;
    }
}

//
if (!function_exists('checkPaymentViaCinetPay')) {
    /**
     * @param int $transaction_id
     */
    function checkPaymentViaCinetPay($payment_token = null, $transaction_id = null): bool
    {
        if (is_null($transaction_id) && is_null($payment_token)) {
            //throw new Exception("Error : TRansaction Id & Payment token can't be both null");
            return false;
        }

        try {
            $url = "https://api-checkout.cinetpay.com/v2/payment/check";

            $checkingData = $transaction_id ? ["transaction_id" => $transaction_id] : ["token" => $payment_token];

            $params = [
                "apikey" => "13760649056213b99a565d58.80647257",
                "site_id" => "915591",
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(array_merge($params, $checkingData)),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "content-type:application/json"
                ),
            ));

            $raw_response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                $response = json_decode($raw_response);

                //dd($response);
                return $response->data->status == 'ACCEPTED';
            }
        } catch (\Exception $e) {
            // throw new Exception("Error :" . $e->getMessage());
        }
        return false;
    }
}

//Pour generer le token avec l'api de cinetpay pour le tranfert (Comment générer un token)
if (!function_exists('authLoginCinetPay')) {
    function authLoginCinetPay()
    {
        try {
            $url = "https://client.cinetpay.com/v1/auth/login";
            $params = [
                "apikey" => "13760649056213b99a565d58.80647257",
                "password" => "Oturu0710@.",
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "Content-type:application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                $raw_response = json_decode($response);
                //dd($raw_response->data->token);
                return $raw_response->data->token;
            }
        } catch (\Exception $e) {
            // throw new Exception("Error :" . $e->getMessage());
        }
        return false;
    }
}

//Recuperer le solde du compte de tranfert de cinetPay (Information solde du compte transfert)
if (!function_exists('checkBalanceAccountCinetPay')) {
    /**
     * @param $token text
     */
    function checkBalanceAccountCinetPay($token)
    {
        try {
            $url = "https://client.cinetpay.com/v1/transfer/check/balance?token=" . $token;
            $params = [
                "token" => $token
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "Content-type:application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                $raw_response = json_decode($response);
                //dd($raw_response);
                return $raw_response->data->amount;
            }
        } catch (\Exception $e) {
            // throw new Exception("Error :" . $e->getMessage());
        }
        return false;
    }
}

//Ajouter un ou plusieurs contacts sur CinetPay
if (!function_exists('addNewContactInCinetPay')) {
    /**
     * @param string $prefix
     * @param string $phone
     * @param string $name
     * @param string $surname
     * @param string $email
     */
    function addNewContactInCinetPay($prefix, $phone, $name, $surname, $email, $token)
    {
        try {
            $url = "https://client.cinetpay.com/v1/transfer/contact?lang=fr&token=" . $token;
            $params = [
                'prefix' => $prefix,
                'phone' => $phone,
                'name' => $name,
                'surname' => $surname,
                'email' => $email
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_POST => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(["data" => json_encode($params)]),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                //$raw_response = json_decode($response);
                //dd($raw_response);
                return $response;
            }
        } catch (\Exception $e) {
            throw new Exception("Error :" . $e->getMessage());
        }
        return null;
    }
}

//Envoyer de l’argent d’un ou plusieurs de vos contacts CinetPay
if (!function_exists('sendMoneyByCinetPay')) {
    /**
     * @param int $transaction_id
     */
    function sendMoneyByCinetPay($prefix, $phone, $amount, $client_transaction_id, $token)
    {
        try {
            $url = "https://client.cinetpay.com/v1/transfer/money/send/contact?lang=fr&token=" . $token;

            $params = [
                "prefix" => $prefix,
                "phone" => $phone,
                "amount" => $amount,
                "client_transaction_id" => $client_transaction_id,
                "notify_url" => "http://1xbet.test:81/transfer/notify"
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(["data" => json_encode($params)]),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "Content-type:application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                //$raw_response = json_decode($response);
                //dd($raw_response);
                return $response;
            }
        } catch (\Exception $e) {
            throw new Exception("Error :" . $e->getMessage());
        }
        return null;
    }
}

//Obtenir les informations d’un transfert d’argent
if (!function_exists('checkInfoTransfereMoney')) {
    /**
     * @param string $client_transaction_id
     * @param string $transaction_id
     */
    function checkInfoTransfereMoney($transaction_id = null, $client_transaction_id = null)
    {
        if (is_null($client_transaction_id) && is_null($transaction_id)) {
            //throw new Exception("Error : TRansaction Id & Payment token can't be both null");
            return false;
        }

        try {

            $token = authLoginCinetPay();

            $url = "https://client.cinetpay.com/v1/transfer/check/money?lang=fr&token=" . $token;

            $params = [
                "lang" => "fr",
                "token" => $token,
                "transaction_id" => $transaction_id,
            ];

            //dd($params);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "Content-type:application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                $raw_response = json_decode($response);
                //dd($raw_response);
                return $raw_response;
            }
        } catch (\Exception $e) {
            // throw new Exception("Error :" . $e->getMessage());
        }
        return false;
    }
}


if (!function_exists('verificationTransaction')) {
    /**
     * @param int $transaction_id
     */
    function verificationTransaction($payment_token)
    {
        try {
            $url = "https://api-checkout.cinetpay.com/v2/payment/check";
            $params = [
                "apikey" => "13760649056213b99a565d58.80647257",
                "site_id" => "915591",
                "payment_token" => $payment_token,
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    "content-type:application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            throw new Exception("Error :" . $e->getMessage());
        }
        return null;
    }
}


if (!function_exists('transfertByCinetPay')) {
    /**
     * @param string $title
     */
    function transfertByCinetPay()
    {
    }
}

if (!function_exists('initFCM')) {
    /**
     * @param string 
     */
    function initFCM()
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $token = "edL4ukAZ4vY:APA91bFgz4DFVeP29MqVayuEUvs-7Qix8buB1vI10mthr2sBahe8t7tFxfJ5ogA6FgNw3Wfyo_HyORDzlpKURPpc4m942LdscyOWloX_2Kn2CR1nwEpMxPLI5kViRIT16t_K1sbPbdZQ";
        $from = "AIzaSyAvtcKESXzgF06fLcRo61Y8g1VQgSzLv_k";
        $msg = array(
            'body'  => "Testing Testing",
            'title' => "Hi, From Raj",
            'receiver' => 'erw',
            'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
            'sound' => 'mySound'/*Default sound*/
        );

        $fields = array(
            'to'        => $token,
            'notification'  => $msg
        );

        $headers = array(
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        dd($result);
        curl_close($ch);
    }
}

if (!function_exists('getVisitorAddressIp')) {
    function getVisitorAddressIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $ip;
    }
}

if (!function_exists('saveSysActivityLog')) {
    /**
     * @param $status
     * @param $content
     * @param $ip
     */
    function saveSysActivityLog($status, $content, $created_by = null)
    {
        return SysLogController::storeSyslog($status, $content, $created_by);
    }
}

if (!function_exists('getMonthOnApp')) {
    function getMonthOnApp()
    {
        $month = now()->monthName;
        switch ($month) {
            case "January":
                return "Janvier";
                break;
            case "February":
                return "Fevrier";
                break;
            case "March":
                return "Mars";
                break;
            case "April":
                return "Avril";
                break;
            case "May":
                return "Mai";
                break;
            case "June":
                return "Juin";
                break;
            case "July":
                return "Juillet";
                break;
            case "August":
                return "Août";
                break;
            case "September":
                return "Septembre";
                break;
            case "October":
                return "Octobre";
                break;
            case "November":
                return "Novembre";
                break;
            case "December":
                return "Decembre";
                break;
            default:
                return "Null";
                break;
        }
        return null;
    }
}

if (!function_exists('genereFcmFoken')) {
    /**
     * @param $status
     * @param $content
     * @param $ip
     */
    function genereFcmFoken($registrationId, $titre, $message, $logo = null, $image = null, $content = null)
    {
        $SERVER_API_KEY = "AAAAGwm-_iY:APA91bGlSTsleF-5lPhdMU_AvYskmFfjYRGJG7HPwKQiGwHS6qlENTyRfeQO64qe8DRRlgbuJ3nnr8KHKSsvZIlwmoMlJk71Ji1n-U5tb2hx-mo0QXpA6XcmyRVgVBrAsB0RQ6ZHamKk";
        $url = "https://fcm.googleapis.com/fcm/send";

        $data = [
            "registration_ids" => $registrationId,
            "notification" => [
                "title" => $titre,
                "body" => $message,
                "content_available" => true,
                "priority" => "high",
            ]
        ];

        //$dataString = json_encode($data);

        $headers = [
            "Authorization: key=" . $SERVER_API_KEY,
            "Content-Type: application/json",
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        //dd($response);
        return $response;
    }
}


if (!function_exists('checkConnectionFirebase')) {

    function checkConnectionFirebase($registrationId, $titre, $message)
    {
        $token = "AAAAGwm-_iY:APA91bGlSTsleF-5lPhdMU_AvYskmFfjYRGJG7HPwKQiGwHS6qlENTyRfeQO64qe8DRRlgbuJ3nnr8KHKSsvZIlwmoMlJk71Ji1n-U5tb2hx-mo0QXpA6XcmyRVgVBrAsB0RQ6ZHamKk";
        $firebase_url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . $token,
            'Content-Type: application/json'
        );

        $info['title'] = $titre;
        $info['body'] = $message;

        $data['notification'] = $info;

        $fields = array(
            'to' => $registrationId,
            'notification' => $info,
        );

        // $info['title'] = $titre;
        // $info['body'] = $message;

        //$data['notification'] = $info;

        // $fields = [
        //     'message' => [
        //         'token' => $registrationId,
        //         'notification' => [
        //             'title' => $titre,
        //             'body' => $message
        //         ],
        //     ],
        // ];

        // $data = [
        //     "registration_ids" => $registrationId,
        //     "notification" => [
        //         "title" => $titre,
        //         "body" => $message,
        //         "content_available" => true,
        //         "priority" => "high",
        //     ]
        // ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $firebase_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

if (!function_exists('payWithPayDunya')) {
    function payWithPayDunya($nom_produit)
    {
        $quantite = 1;
        $prix_produit = 200;
        $montant_total = 200;

        Setup::setMasterKey(env('PAYDUNYA_KEY_PRINCIPALE'));
        Setup::setPublicKey(env('PAYDUNYA_KEY_PUBLIC_LIVE'));
        Setup::setPrivateKey(env('PAYDUNYA_KEY_PRIVATE_LIVE'));
        Setup::setToken(env('PAYDUNYA_TOKEN_LIVE'));
        Setup::setMode("live");

        Store::setName("Mycartraders");
        Store::setTagline("Achetez une voiture d'occasion en toute confiance");
        Store::setPhoneNumber("22896854152");
        Store::setPostalAddress("Agoe Telessou");
        Store::setWebsiteUrl("https://www.mycartraders.com");
        Store::setLogoUrl("http://mycartraders.com/themes/img/logo-mct.png");

        Store::setCallbackUrl(route('payements.callback'));
        Store::setCancelUrl(route('payements.cancel'));
        Store::setReturnUrl(route('payements.return'));

        $transaction = new CheckoutInvoice();
        $transaction->addItem($nom_produit, $quantite, $prix_produit, $montant_total);
        $transaction->setDescription("Paiement de " . $montant_total . " FCFA pour la reservation de " . $nom_produit);
        $transaction->setTotalAmount($montant_total);

        if ($transaction->create()) {
            //header('Location: ' . $transaction->getInvoiceUrl());
            //exit();
            return $transaction;
        } else {
            //echo $transaction->response_text;
            return $transaction->response_text;
        }
    }
}


if (!function_exists('checkEtatPayementPaydunya')) {
    function checkEtatPayementPaydunya($token)
    {
        try {
            $url = "https://app.paydunya.com/api/v1/checkout-invoice/confirm/" . $token;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    'PAYDUNYA-MASTER-KEY: pfOztOxB-gNuu-vYxn-dWdj-MYBOYG55AjWJ',
                    'PAYDUNYA-PRIVATE-KEY: live_private_dvQPeAM0ooaputzstGbkfANOeZf',
                    'PAYDUNYA-TOKEN: WlBh3psLJvIiinmrdJeZ',
                ),
            ));

            $response = curl_exec($curl);

            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("Error :" . $err);
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            throw new Exception("Error :" . $e->getMessage());
        }
        return null;
    }
}
