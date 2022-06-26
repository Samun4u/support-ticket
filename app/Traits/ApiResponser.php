<?php

namespace App\Traits;

trait ApiResponser{
    protected function success($data,$message=null,$code=200)
    {
        return response()->json([
            'status' => 'Success',
            'data'  => $data,
            'message' =>  $message,
          
        ],$code);
    }

    protected function error($data=null,$message=null,$code)
    {
        return response()->json([
            'status' => 'Error',
            'data'  => $data,
            'message' =>  $message,
        ],$code);
    }
}