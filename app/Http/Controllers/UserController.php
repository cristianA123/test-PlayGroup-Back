<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
* @OA\Info(
*             title="API Usuarios", 
*             version="1.0",
*             description="Usuarios"
* )
*
* @OA\Server(url="http://localhost:8000/")
*/


class UserController extends Controller
{
    /**
     * Listado de todos los registros de los usuarios
     * @OA\Get (
     *     path="/api/users",
     *     tags={"Usuarios"},
     *   @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer token"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar los usuarios",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="users",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Aderson Felix"
     *                     ),
     *                     @OA\Property(
     *                         property="lastName",
     *                         type="string",
     *                         example="Jara Lazaro"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="Jara@gmail.com"
     *                     ),
     *                     @OA\Property(
     *                         property="password",
     *                         type="string",
     *                         example="123456"
     *                     ),
     *                     @OA\Property(
     *                         property="role",
     *                         type="string",
     *                         example="Administrador"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             ),
     *        @OA\Property(
     *                 type="boolean",
     *                 property="success",
     *                 example="true"
     *             )
     *         )
     *     )
     * )
     */
    public function getUsers()
    {

        $users = User::all();
        return response()->json(['success' => true, 'users' => $users],200);
    }

/**
 * Login
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Usuarios"},
 *     summary="Iniciar sesión",
 *     description="Inicia sesión de un usuario",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Credenciales de inicio de sesión",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="Jara@gmail.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="123456"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Inicio de sesión exitoso",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 type="string",
 *                 property="token",
 *                 example="asdasdasdasdasdasdasd"
 *             ),
 *             @OA\Property(
 *                 type="array",
 *                 property="users",
 *                 @OA\Items(
 *                     @OA\Property(
 *                         property="email",
 *                         type="string",
 *                         example="Jara@gmail.com"
 *                     ),
 *                     @OA\Property(
 *                         property="password",
 *                         type="string",
 *                         example="123456"
 *                     )
 *                 )
 *             ),
 *          )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Credenciales inválidas",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 type="string",
 *                 property="error",
 *                 example="invalid_credentials"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al crear el token",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 type="string",
 *                 property="error",
 *                 example="could_not_create_token"
 *             )
 *         )
 *     )
 * )
 */

public function authenticate(Request $request)
{
    $credentials = $request->only('email', 'password');
    try {
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 400);
        }
        $user = auth()->user();

    } catch (JWTException $e) {
        return response()->json(['error' => 'could_not_create_token'], 500);
    }
    return response()->json(['token'=>$token, 'user'=> $user]);
}

  /**
     * Crear usuario
     * @OA\Post (
     *     path="/api/user/create",
     *     tags={"Usuarios"},
     *   @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer token"
     *         )
     *     ),
     *   @OA\RequestBody(
    *         required=true,
    *         description="Body para crear usuario",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="name",
    *                 type="string",
    *                 example="Luis"
    *             ),
    *             @OA\Property(
    *                 property="lastName",
    *                 type="string",
    *                 example="Rojas"
    *             ),
    *             @OA\Property(
    *                 property="password",
    *                 type="string",
    *                 example="123456"
    *             ),
    *             @OA\Property(
    *                 property="email",
    *                 type="string",
    *                 example="Jara@gmail"
    *             ),
    *             @OA\Property(
    *                 property="role",
    *                 type="string",
    *                 example="Administrador"
    *             )
    *         )
    *       ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar los usuarios",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="users",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Aderson Felix"
     *                     ),
     *                     @OA\Property(
     *                         property="lastName",
     *                         type="string",
     *                         example="Jara Lazaro"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="Jara@gmail.com"
     *                     ),
     *                     @OA\Property(
     *                         property="password",
     *                         type="string",
     *                         example="123456"
     *                     ),
     *                     @OA\Property(
     *                         property="role",
     *                         type="string",
     *                         example="Administrador"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             ),
     *        @OA\Property(
     *                 type="boolean",
     *                 property="success",
     *                 example="true"
     *             )
     *         )
     *     )
     * )
     */
public function createuser(Request $request)
{

    Log::info($request);
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'role' => 'required|string',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);

    if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
    }

    
    $user = User::create([
        'name' => $request->get('name'),
        'email' => $request->get('email'),
        'lastName' => $request->get('lastName'),
        'role' => $request->get('role'),
        'simple_password' => $request->get('password'),
        'password' => Hash::make($request->get('password')),
    ]);

    $data = [
        'email' => $user->email,
        'simple_password' => $request->get('password'),
        ];

        $email = $user->email;

    Mail::send('correo.email', $data, function ($msg) use ($email) {
        $msg->from('cristian@gmail.com', "Soy cristian");
        $msg->subject('Credenciales de usuario');
        $msg->to($email);
        });

    $token = JWTAuth::fromUser($user);

    return response()->json(['success'=> true ,'user' => $user,'token' => $token],201);
}

 /**
     * Modificar Usuario
     * @OA\Put (
     *     path="/api/user/",
     *     tags={"Usuarios"},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="1"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer token"
     *         )
     *     ),
     *   @OA\RequestBody(
    *         required=true,
    *         description="Body para crear usuario",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="name",
    *                 type="string",
    *                 example="Luis"
    *             ),
    *             @OA\Property(
    *                 property="lastName",
    *                 type="string",
    *                 example="Rojas"
    *             ),
    *             @OA\Property(
    *                 property="password",
    *                 type="string",
    *                 example="123456"
    *             ),
    *             @OA\Property(
    *                 property="email",
    *                 type="string",
    *                 example="Jara@gmail"
    *             ),
    *             @OA\Property(
    *                 property="role",
    *                 type="string",
    *                 example="Administrador"
    *             )
    *         )
    *       ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar los usuarios",
     *         @OA\JsonContent(
     *          @OA\Property(
     *                 type="boolean",
     *                 property="success",
     *                 example="true"
     *             )
     *          ),
     *          @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Se modifico usuario correctamente"
     *             )
     *          )
     *     )
     * )
     */
public function updateUser(Request $request, $id){
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'role' => 'required|string',
        'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        'password' => 'nullable|string|min:6',
    ]);

    $user->name = $request->name;
    $user->lastName = $request->lastName;
    $user->role = $request->role;
    $user->email = $request->email;
    $user->simple_password = $request->password;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json(['success'=> true,'message' => 'Usuario actualizado correctamente'], 200);
}

    /**
     * Eliminar usuario
     * @OA\Delete (
     *     path="/api/user/create",
     *     tags={"Usuarios"},
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="1"
     *         )
     *     ),
     *   @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer token"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar los usuarios",
     *         @OA\JsonContent(
     *       
     *          @OA\Property(
     *                 type="boolean",
     *                 property="success",
     *                 example="true"
     *             )
     *          ),
     *          @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Elimino un usuario"
     *             )
     *          ),
     *     )
     * )
     */
public function deleteUser(Request $request, $id){
    $user = User::find($id);

    if (!$user) {
        return response()->json(['success'=> false,'message' => 'Usuario no encontrado'], 404);
    }

    $user->delete();

    return response()->json(['success'=> true,'message' => 'Usuario eliminado correctamente'], 200);
}
}

