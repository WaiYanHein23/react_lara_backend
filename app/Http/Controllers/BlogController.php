<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::orderBy("created_at","desc")->get();
        return response()->json([
            "status"=>true,
            "data"=>$blogs
        ]);
    }

    //show blogs
public function show($id)
{
    $blogs = Blog::find($id);

   if($blogs == null){
    return response()->json([
        "status"=>false,
        "data"=>"Blog does not exit"
    ]);

   }

    return response()->json([
        "status"=>true,
        "data"=>$blogs

    ]);
}

     //create blogs
     public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'title'=>'required|max:20',
            'author'=>['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>"unprocessable",
                'error'=>$validator->errors()
            ],422);
        }

        $blogs=Blog::create([
            'title'=>request('title'),
            'author'=>request('author'),
            'description'=>request('description'),
            'shortDesc'=>request('shortDesc'),
        ]);

        //Save Image
        $tempImage = TempImage::find($request->image_id);
        if ($tempImage != null) {
            $imageExtArray = explode('.', $tempImage->image);
            $ext = last($imageExtArray);
            $imageName = time() . '-' . $blogs->id . '.' . $ext;
            $blogs->image = $imageName;
            $blogs->save();

            $sourcePath = public_path('uploads/temp/' . $tempImage->image);
            $destPath = public_path('uploads/blogs/' . $imageName);
            File::copy($sourcePath, $destPath);
        }


        return response()->json([
            'message'=>'Create Successful',
            'blogs'=>$blogs

        ]);
}

//update blogs
public function update($id,Request $request){

    $blogs=Blog::find($id);

    if($blogs == null){

        return response()->json([
            "status"=>false,
            "data"=>"Blog does not found"
            ],0);

    }


    $validator=Validator::make($request->all(),[
        'title'=>'required|max:20',
        'author'=>['required']
    ]);

    if($validator->fails()){
        return response()->json([
            'message'=>"unprocessable",
            'error'=>$validator->errors()
        ],422);
    }

    $blogs->update([
        'title'=>request('title'),
        'author'=>request('author'),
        'description'=>request('description'),
        'shortDesc'=>request('shortDesc'),
    ]);

    //Save Image
    $tempImage = TempImage::find($request->image_id);
    if ($tempImage != null) {
        //delete old image here
        File::delete(public_path('uploads/blogs/'.$blogs->image));
        $imageExtArray = explode('.', $tempImage->image);
        $ext = last($imageExtArray);
        $imageName = time() . '-' . $blogs->id . '.' . $ext;
        $blogs->image = $imageName;
        $blogs->save();

        $sourcePath = public_path('uploads/temp/' . $tempImage->image);
        $destPath = public_path('uploads/blogs/' . $imageName);
        File::copy($sourcePath, $destPath);
    }


    return response()->json([
        'message'=>'Update Successful',
        'blogs'=>$blogs

    ]);

}

//delete blog
public function destroy($id){
    $blogs = Blog::find($id);
    if($blogs == null){

        return response()->json([
            "status"=>false,
            "data"=>"Blog does not found"
            ],0);
    }

    //delete image
    File::delete(public_path("uploads/blogs/".$blogs->image));
    $blogs->delete();

    return response()->json([
        "status"=>true,
        'message'=>'Delete Successful',


    ]);
}
}
