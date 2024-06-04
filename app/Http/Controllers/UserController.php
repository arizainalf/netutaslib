<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\JsonResponder;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $users = User::all();
            if ($request->mode == "datatable") {
                return DataTables::of($users)
                    ->addColumn('action', function ($user) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/user/' . $user->id . '`, [`id`, `nama`,`email`,`role`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/user/' . $user->id . '`, `user-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addColumn('image', function ($user) {
                        return '<img src="/storage/img/user/' . $user->image . '" width="150px" alt="">';
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'image'])
                    ->make(true);
            }

            return $this->successResponse($user, 'Data user ditemukan.');
        }

        return view('pages.user.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $image = $request->file('image')->hashName();
        $request->file('image')->storeAs('public/img/user', $image);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'image' => $image ?? 'default.jpg',
        ]);

        return $this->successResponse($user, 'Data Buku Disimpan!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        return $this->successResponse($user, 'Data Buku Ditemukan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'password' => '',
            'role' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        if ($request->password) {
            $updateUser = [
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
            ];
        } else {
            $updateUser = [
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => $request->role,
            ];
        }

        if ($request->hasFile('image')) {
            if ($user->image != 'default.jpg' && Storage::exists('public/img/user/' . $user->image)) {
                Storage::delete('public/img/user/' . $user->image);
            }
            $image = $request->file('image')->hashName();
            $request->file('image')->storeAs('public/img/user', $image);
            $updateUser['image'] = $image;
        }
        $user->update($updateUser);

        return $this->successResponse($user, 'Data User Diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse(null, 'Data User Tidak Ada!');
        }

        if ($user->image != 'default.jpg' && Storage::exists('public/img/user/' . $user->image)) {
            Storage::delete('public/img/user/' . $user->image);
        }

        $user->delete();

        return $this->successResponse(null, 'Data User Dihapus!');
    }
}