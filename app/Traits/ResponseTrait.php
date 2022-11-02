<?php

/**
 *
 */

namespace App\Traits;

use Illuminate\Http\Request;

trait ResponseTrait{

    protected function error($message, $code = "201") {
        return response()->json([
			'status'=> 'Error',
            'code'=>$code,
			'message' => $message,
		], $code);
    }


    function success($data, $code = "200") {

        return response()->json([
			'status'=> 'Success',
            'code'=>$code,
			'data' => $data
		], $code);
    }

    function successToken($data, $code = "200") {

        return response()->json([
			'status'=> 'Success',
            'code'=>$code,
			'data' => $data
		], $code);
    }



     function decodeContentArray(Request $request, $toArray = true) {
        $data = json_decode($request->getContent(), $toArray);
        return $data;
    }

     function convertStdToArray($value) {
        return json_decode(json_encode($value), true);
    }

}
