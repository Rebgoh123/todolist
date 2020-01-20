<?php

namespace App\Http\Controllers;

use App\category;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Storage;
use DB;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::User();
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try
        {
            DB::beginTransaction();
            $category = category::create([
                'user_id' => $user->id,
                'name' => $data['name'],
            ]);

            DB::commit();

            return $this->sendResponse($category, 'Category created successfully!');
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->sendError('Error', $e->getMessage());
        }
    }

    public function update(Request $request, category $category)
    {
        //
    }

    public function destroy(category $category)
    {
        //
    }
}
