<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TempImageController extends Controller
{
    //images store
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'image'=>'required|image'
        ]);
        if($validator->fails()){
            return response()->json([
                "status"=>false,
                "message"=>"Something Wrong",
                "errors"=> $validator->errors()
            ]);

        }
//upload image here
$image = request('image');
$ext = $image->getClientOriginalExtension();
$imageName = time() . "." . $ext;

// Store image name into the database
$tempImage = TempImage::create([
    "image" => $imageName
]);

// Move image to the temp directory
$image->move(public_path('uploads/temp'), $imageName);

return response()->json([
    "status"=>true,
    "message" => "Create Successful",
    "data" => $tempImage
]);



    }
}
