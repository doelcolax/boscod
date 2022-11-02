<?php

/**
 *
 */

namespace App\Responses;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ResponseSTD {

    function error($info, $code = "201") {
        $response = array(
            'response_status' => false,
            "response_code" => "$code",
            'response_message' => $info

        );
        return $response;
    }

    function generalResponse(){
        $response = array(
            "response_code" => "00",
            "response_message" =>'SUKSES'

        );
        return $response;

    }
    function errorResponse($info, $code = ""){
        $response = array(
            'response_status' => false,
            "response_code" => "$code",
            "response_message" => $info

        );
        return $response;

    }
    function success($data, $info = "Success", $code = "200") {
        $response = array(
            'response_status' => true,
            'response_message' => $info,
            "response_code" => $code,
            'response_data' => $data

        );
        return $response;
    }
    function successnotif($cunread,$data, $info = "Success", $code = "00") {
        $response = array(
            'response_status' => true,
            'response_data' => $data,
            'response_message' => $info,
            'notif_unread'=> $cunread,
            "response_code" => $code
        );
        return $response;
    }




     function decodeContentArray(Request $request, $toArray = true) {
        $data = json_decode($request->getContent(), $toArray);
        return $data;
    }

     function convertStdToArray($value) {
        return json_decode(json_encode($value), true);
    }

}
