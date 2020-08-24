<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;




class ApiController extends Controller
{
    //user registration
    public function userReg(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $success['token'] = $user->createToken('MyApp')->accessToken;
        return response()->json(['success' => $success], 200);
    }

    //user login
    public function userLogin(Request $request){

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }

    }

    //add product
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products|string',
            'detail' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->detail = $request->detail;
        $product->save();
        return response()->json("Product Saved Successfully", 200);
    }

    //update product
    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'detail' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $product = Product::find($request->pid);
        $product->name = $request->name;
        $product->detail = $request->detail;
        $product->save();
        return response()->json("Product Updated Successfully", 200);
    }

    //delete product
    public function deleteProduct(Request $request){
        $data= Product::where('id', $request->pid)->delete();
            if($data){
                return response()->json("Product Deleted Successfully", 200);
            }else{
                return response()->json("Product not Deleted Successfully", 200);
            }
    }

    //forget password
    public function forgotPassword(Request $request)
    {
        $pass_reset = new PasswordReset();
        $pass_reset->email = $request->email;
        $pass_reset->token = mt_rand(10000, 200000000);
        $pass_reset->save();

        //Send this token to email
        return response()->json("Password reset code sent to ur email. please click the link to reset", 200);
    }


}
