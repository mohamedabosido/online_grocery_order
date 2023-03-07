<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CateogryController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = 10;
        $categories = Category::paginate($paginate);
        foreach ($categories as $category) {
            $category->logo = Storage::url($category->logo);
        }
        return $this->success($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $logo = $request->file('logo');
        $logo_name =  time() + rand(1, 10000) . '.' . $logo->getClientOriginalExtension();
        $logo_path = 'categoires/' . $logo_name;
        Storage::put($logo_path, file_get_contents($logo));
        $category = Category::create([
            'name' => $request->name,
            'logo' => $logo_path,
        ]);
        if ($category) {
            $category->logo = Storage::url($category->logo);
            return $this->success($category);
        }
        return $this->error('', 'Please check the process', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->logo = Storage::url($category->logo);
            return $this->success($category);
        }
        return $this->error('', 'Please check the process', 400);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->update([
                'name' => $request->name,
                // 'logo' => $logo_path,  image can not change
            ]);

        if ($category) {
            $category->logo = Storage::url($category->logo);
            return $this->success($category);
        }
        return $this->error('', 'Please check the process', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::destroy($id);
        if ($category) {
            return $this->success('', 'Deleted successfully');
        }
        return $this->error('', 'Please check the process', 400);
    }
}
