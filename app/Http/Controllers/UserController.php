<?php

namespace App\Http\Controllers;

//use App\Http\Requests\RegisterAuthRequest;

use App\Models\Evenement;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
    public $loginAfterSignUp = true;

    public function addProfil(Request $request)
    {
        try{  //ce Try catch permet de faire un rollback au cas où la transaction n'a pas abouti
            DB::beginTransaction();

            $profil = Profil::create([
            'name' => $request->get('name')
             ]);

        DB::commit();

         return response()->json([
            'success' => true,
            'profil' => $profil
        ]);

      }catch (\Exception $e){

        return response()->json([
            'success' => false,
        ]);

        DB::rollback();
       }

    }

    public function addEvenement(Request $request)
    {
        $evenement = new Evenement();
        $evenement->description = $request->description;
        $evenement->etat = $request->etat;
        $evenement->lieu =$request->lieu;
        $evenement->image = $request->image;
        $evenement->user_id = $request->user_id;

        if($evenement->save()){
            return response()->json([
                'success' => true,
                'evenement' => $evenement
            ]);
        }else{
            false;
        }




    }

    public function getProfil()
    {
        return DB::table('profils')->get();
    }


    public function addUser(Request $request)
    {
        try{  //ce Try catch permet de faire un rollback au cas où la transaction n'a pas abouti
            DB::beginTransaction();

            $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'nbr_signalement' => $request->get('nbr_signalement'),
            'profil_id' => $request->get('profil_id'),//Foreignekey
             ]);



        $token = JWTAuth::fromUser($user);

        DB::commit();

         return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);

      }catch (\Exception $e){

        return response()->json([
            'success' => false,
        ]);

        DB::rollback();
       }

    }

    public function getEvenement()
    {
        return DB::table('evenements')->get();
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or Password',
            ], 401);
        }

        $user = User::where([['email',$request->email],['deleted_at','=',null]])->first();

        if($user)
        {
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $jwt_token
            ]);
        }else{
            return response()->json(['success' => false]);
        }


    }


    public function getAllEvenementByUser($user_id)
   {
       return  $evenement = Evenement::where([['user_id','=',$user_id],['deleted_at','=',null]])->get();
   }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur deconnecte avec succes'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, l utilisateur ne peut pas etre deconnecte'
            ], 500);
        }
    }




    public function getAuthUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['utilisateur exit pas'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token expiré'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token invalide'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->nbr_signalement = $request->input('nbr_signalement');
        $user->profil_id = $request->input('profil_id');

        if($user->update()){
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }else{
            false;
        }
        //return redirect()->back()->with('status','Student Updated Successfully');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if($user->delete()){
            return response()->json([
                'success' => true
            ]);
        }else{
            false;
        }


    }


    public function deleteEvenement($id)
    {
        $evenement = Evenement::find($id);

        if($evenement->delete()){
            return response()->json([
                'success' => true
            ]);
        }else{
            false;
        }
    }


    public function updateEvenement(Request $request, $id)
    {
        $evenement = Evenement::find($id);
        $evenement->description = $request->input('description');
        $evenement->lieu = $request->input('lieu');
        $evenement->etat = $request->input('etat');
        $evenement->image = $request->input('image');
        $evenement->user_id = $request->input('user_id');
        if($evenement->update()){
            return response()->json([
                'success' => true,
                'evenement' => $evenement
            ]);
        }else{
            false;
        }

    }
}
