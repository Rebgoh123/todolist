<?php

namespace App\Http\Controllers;

use App\todolist;
use App\category;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Storage;
use DB;
use Carbon\Carbon;


class TodolistController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::User();
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try
        {
            DB::beginTransaction();
            $category = category::firstOrCreate(
                ['name' => trim(strtolower($data['category'])),
                'user_id' => $user->id]
            );

            $new_file = $data['file'];
            $file = null;

            if($new_file)
            {
                if (!Storage::has("public/uploads"))
                    Storage::makeDirectory('public/uploads', $mode = 0755);
                $file = substr($new_file, strpos($new_file, ',') + 1);
                $file = base64_decode($file);
                Storage::put('public/uploads/'.  date('YmdHis') .$data['file_name'], $file);
            }

            $todolist = todolist::create([
                'title' =>$data['title'],
                'category_id' => $category->id,
                'description' => '',
                'file' =>$new_file ? date('YmdHis') .$data['file_name'] : null,
                'upload_file' => $new_file ? 1:0,
                'due_on' => Carbon::parse($data['due_on']),
                'checked' => 0,
                'user_id' =>$user->id
            ]);

            DB::commit();

            return $this->sendResponse($todolist, 'Task created successfully!');
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->sendError('Error', $e->getMessage());
        }
    }

    public function storeFile(Request $request, $id)
    {
        $user = Auth::User();
        $data = $request->all();

        $validator = Validator::make($data, [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try
        {
            DB::beginTransaction();

            $new_file = $data['file'];
            $file = null;

            if($new_file)
            {
                if (!Storage::has("public/uploads"))
                    Storage::makeDirectory('public/uploads', $mode = 0755);
                $file = substr($new_file, strpos($new_file, ',') + 1);
                $file = base64_decode($file);
                Storage::put('public/uploads/'.  date('YmdHis') .$data['file_name'], $file);
            }

            $todolist = todolist::where('id',$id)->first();
            $todolist->file = date('YmdHis') .$data['file_name'];
            $todolist->upload_file = 1;
            $todolist->save();

            DB::commit();

            return $this->sendResponse($todolist, 'File uploaded successfully!');
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->sendError('Error', $e->getMessage());
        }
    }

    public function getBlobFile($id){
        $user = Auth::User();

        $file = todolist::select('file')->where([['id', $id], ['user_id', $user->id]])->first();

        if(!$file){
            return $this->sendError('No permission to download');
        }

        return response()->download(storage_path('app/public/uploads/' . $file->file));
    }

    public function show($id)
    {
        $user = Auth::User();

        $todolist = todolist::with('category')->where([['id', $id], ['user_id', $user->id]])->first();

        return $this->sendResponse($todolist, 'Task retrieved successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::User();
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try
        {
            DB::beginTransaction();
            $category = category::firstOrCreate(
                ['name' => trim(strtolower($data['category'])),
                    'user_id' => $user->id]
            );

            $new_file = $data['file'];
            $file = null;

            $todolist = todolist::where([['id', $id], ['user_id', $user->id]])->first();

            if($new_file)
            {
                if (!Storage::has("public/uploads"))
                    Storage::makeDirectory('public/uploads', $mode = 0755);
                $file = substr($new_file, strpos($new_file, ',') + 1);
                $file = base64_decode($file);
                Storage::put('public/uploads/'.  date('YmdHis') .$data['file_name'], $file);

                if($todolist->file){
                    unlink(storage_path('app/public/uploads/'.$todolist->file));
                }
            }

            if( $data['remove_file'] == 1){
                unlink(storage_path('app/public/uploads/'.$todolist->file));
            }

            $remove_value = $data['remove_file'] == "1" ? 0 : ($new_file ? 1 :  $todolist->upload_file);
            $file_value =$data['remove_file'] == "1" ? null : ($new_file ? date('YmdHis') .$data['file_name']  :  $todolist->file);

            $todolist->title = $data['title'];
            $todolist->category_id = $category->id;
            //unlink from storage too....
            $todolist->file = $file_value;
            $todolist->upload_file = $remove_value;
            $todolist->due_on = Carbon::parse($data['due_on']);
            $todolist->save();

            DB::commit();

            return $this->sendResponse($todolist, 'Task updated successfully!');
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->sendError('Error', $e->getMessage());
        }
    }

    public function checked(Request $request, $id)
    {
        $user = Auth::User();
        $data = $request->all();

        try
        {
            DB::beginTransaction();

            //remove image too
            $todolist = todolist::where([['id', $id], ['user_id', $user->id]])->first();

            if(!$todolist){
                return $this->sendError('Not found');
            }

            $todolist->checked = $data['check'] == 'true' ? 1: 0;
            $todolist->save();

            DB::commit();

            return $this->sendResponse($todolist, 'Task checked successfully!');
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->sendError('Error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = Auth::User();

        try
        {
            DB::beginTransaction();

            //remove image too
           $todolist = todolist::where([['id', $id], ['user_id', $user->id]])->first();
            unlink(storage_path('app/public/uploads/'.$todolist->file));
            $todolist->delete();

            DB::commit();

            return $this->sendResponse($todolist, 'Task deleted successfully!');
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->sendError('Error', $e->getMessage());
        }
    }
}
