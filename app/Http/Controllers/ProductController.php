<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Helpers\UploadHelper;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Article\Entities\Category;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($isTrashed = false)
    {

        if ( is_null($this->user) || ! $this->user->can('product.view') ) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }

        if (request()->ajax()) {
            if ($isTrashed) {
                $products = Product::orderBy('id', 'desc')
                    ->where('status', 0)
                    ->get();
            } else {
                $products = Product::orderBy('id', 'desc')
                    ->where('deleted_at', null)
                    ->where('status', 1)
                    ->get();
            }

            $datatable = DataTables::of($products, $isTrashed)
                ->addIndexColumn()
                ->addColumn(
                    'action',
                    function ($row) use ($isTrashed) {
                        $csrf = "" . csrf_field() . "";
                        $method_delete = "" . method_field("delete") . "";
                        $method_put = "" . method_field("put") . "";
                        $html = "";

                        if ($row->deleted_at === null) {
                            $deleteRoute =  route('admin.products.destroy', [$row->id]);
                            if( $this->user->can('category.edit')) {
                                $html = '<a class="btn waves-effect waves-light btn-success btn-sm btn-circle" title="Edit Category Details" href="' . route('admin.products.edit', $row->id) . '"><i class="fa fa-edit"></i></a>';
                            }
                            if( $this->user->can('category.delete')) {
                                $html .= '<a class="btn waves-effect waves-light btn-danger btn-sm btn-circle ml-2 text-white" title="Delete Admin" id="deleteItem' . $row->id . '"><i class="fa fa-trash"></i></a>';
                            }
                        } elseif($this->user->can('category.delete')) {
                            $deleteRoute =  route('admin.products.trashed.destroy', [$row->id]);
                            $revertRoute = route('admin.products.trashed.revert', [$row->id]);

                            $html = '<a class="btn waves-effect waves-light btn-warning btn-sm btn-circle" title="Revert Back" id="revertItem' . $row->id . '"><i class="fa fa-check"></i></a>';
                            $html .= '
                            <form id="revertForm' . $row->id . '" action="' . $revertRoute . '" method="post" style="display:none">' . $csrf . $method_put . '
                                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-success"><i
                                        class="fa fa-check"></i> Confirm Revert</button>
                                <button type="button" class="btn waves-effect waves-light btn-rounded btn-secondary" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Cancel</button>
                            </form>';
                            $html .= '<a class="btn waves-effect waves-light btn-danger btn-sm btn-circle ml-2 text-white" title="Delete Category Permanently" id="deleteItemPermanent' . $row->id . '"><i class="fa fa-trash"></i></a>';
                        }



                        $html .= '<script>
                            $("#deleteItem' . $row->id . '").click(function(){
                                swal.fire({ title: "Are you sure?",text: "Category will be deleted as trashed !",type: "warning",showCancelButton: true,confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, delete it!"
                                }).then((result) => { if (result.value) {$("#deleteForm' . $row->id . '").submit();}})
                            });
                        </script>';

                        $html .= '<script>
                            $("#deleteItemPermanent' . $row->id . '").click(function(){
                                swal.fire({ title: "Are you sure?",text: "Category will be deleted permanently, both from trash !",type: "warning",showCancelButton: true,confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, delete it!"
                                }).then((result) => { if (result.value) {$("#deletePermanentForm' . $row->id . '").submit();}})
                            });
                        </script>';

                        $html .= '<script>
                            $("#revertItem' . $row->id . '").click(function(){
                                swal.fire({ title: "Are you sure?",text: "Category will be revert back from trash !",type: "warning",showCancelButton: true,confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, Revert Back!"
                                }).then((result) => { if (result.value) {$("#revertForm' . $row->id . '").submit();}})
                            });
                        </script>';

                        $html .= '
                            <form id="deleteForm' . $row->id . '" action="' . $deleteRoute . '" method="post" style="display:none">' . $csrf . $method_delete . '
                                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-success"><i
                                        class="fa fa-check"></i> Confirm Delete</button>
                                <button type="button" class="btn waves-effect waves-light btn-rounded btn-secondary" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Cancel</button>
                            </form>';

                        $html .= '
                            <form id="deletePermanentForm' . $row->id . '" action="' . $deleteRoute . '" method="post" style="display:none">' . $csrf . $method_delete . '
                                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-success"><i
                                        class="fa fa-check"></i> Confirm Permanent Delete</button>
                                <button type="button" class="btn waves-effect waves-light btn-rounded btn-secondary" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Cancel</button>
                            </form>';
                        return $html;
                    }
                )

                ->editColumn('name', function ($row) {
                    return $row->name;
                    // . ' <br /><a href="' . route('category.show', $row->slug) . '" target="_blank"><i class="fa fa-link"></i> View</a>';
                })
                ->editColumn('image', function ($row) {
                    if ($row->banner_image != null) {
                        return "<img src='" . asset('public/assets/images/products/' . $row->image) . "' class='img img-display-list' />";
                    }
                    return '-';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge badge-success font-weight-100">Active</span>';
                    } else if ($row->deleted_at != null) {
                        return '<span class="badge badge-danger">Trashed</span>';
                    } else {
                        return '<span class="badge badge-warning">Inactive</span>';
                    }
                });
            $rawColumns = ['action', 'name', 'image','status'];
            return $datatable->rawColumns($rawColumns)
                ->make(true);
        }

        $count_products = count(Product::select('id')->get());
        $count_active_products = count(Product::select('id')->where('status', 1)->get());
        $count_trashed_products = count(Product::select('id')->where('deleted_at', '!=', null)->get());
        return view('backend.pages.products.index', compact('count_products', 'count_active_products', 'count_trashed_products'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ( is_null($this->user) || ! $this->user->can('product.create') ) {
            $message = 'You are not allowed to access this page !';
            return view('errors.403', compact('message'));
        }
        $categories=Category::pluck('name','id');
        return view('backend.pages.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('category.create')) {
            return abort(403, 'You are not allowed to access this page !');
        }

        $request->validate([
            'name'  => 'required|max:100',
            'slug'  => 'nullable|max:100|unique:categories,slug',
            'logo_image'  => 'nullable|image',
            'banner_image'  => 'nullable|image'
        ]);

        try {
            DB::beginTransaction();
            $category = new Category();
            $category->name = $request->name;
            if ($request->slug) {
                $category->slug = $request->slug;
            } else {
                $category->slug = StringHelper::createSlug($request->name, 'Modules\Article\Entities\Category', 'slug', '-', true);
            }

            if (!is_null($request->banner_image)) {
                $category->banner_image = UploadHelper::upload('banner_image', $request->banner_image, $request->name . '-' . time() . '-banner', 'public/assets/images/categories');
            }

            if (!is_null($request->logo_image)) {
                $category->logo_image = UploadHelper::upload('logo_image', $request->logo_image, $request->name . '-' . time() . '-logo', 'public/assets/images/categories');
            }

            $category->parent_category_id = $request->parent_category_id;
            $category->status = $request->status;
            if (!is_null($request->enable_bg)) {
                $category->enable_bg = 1;
                $category->bg_color = $request->bg_color;
                $category->text_color = $request->text_color;
            }

            $category->description = $request->description;
            $category->meta_description = $request->meta_description;
            $category->priority = $request->priority ? $request->priority : 1;
            $category->created_at = Carbon::now();
            $category->created_by = Auth::id();
            $category->updated_at = Carbon::now();
            $category->save();

            // Update priority column
            if(!$request->priority){
                $category->priority = $category->id;
                if($request->parent_category_id){
                    $lastCategoryPriorityValue = Category::where('parent_category_id', $request->parent_category_id)
                    ->orderBy('priority', 'desc')
                    ->value('priority');
                    if(!is_null($lastCategoryPriorityValue)){
                        $category->priority = (int) $lastCategoryPriorityValue + 1;
                    }
                }
                $category->save();
            }

            $data = [
                'category' => $category->id,
                'en' => $category->name,
                'key' => $category->slug
            ];

            DB::commit();
            session()->flash('success', 'New Category has been created successfully !!');
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            session()->flash('sticky_error', $e->getMessage());
            DB::rollBack();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
