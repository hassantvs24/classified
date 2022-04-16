<?php

namespace App\Http\Controllers\Advertisement;

use App\Http\Controllers\Controller;
use App\Models\AdsPackage;
use App\Models\PurchasePackage;
use App\Models\Subscription;
use App\Notifications\SubscribeNotification;
use Illuminate\Http\Request;
use App\Http\Resources\AdsResource;
use App\Models\Ads;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Models\AdsItem;
use App\Models\AdsTag;
use App\Models\AdsAttribute;
use App\Models\AdsPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
    {
        $today = date('Y-m-d');
        try {
            if(Auth::user()->types == 'Admin'){
                $table = Ads::orderBy('id', 'DESC');
                if(isset($request->users_id)){
                    $table->where('users_id', $request->users_id);
                }
                if(isset($request->status)){
                    $table->where('status', $request->status);
                }
                if(isset($request->expire)){
                    $table->where('expire', '>', $today);
                }
                $ads =  $table->paginate(config('naz.paginate'));
            }else{
                $table = Ads::orderByRaw('DATE(created_at)', 'DESC')->orderByRaw('ISNULL(purchase_packages_id)')->where('users_id', Auth::id());

                if(isset($request->status)){
                    $table->where('status', $request->status);
                }

                if(isset($request->expire)){
                    $table->where('expire', '>', $today);
                }

                if(isset($request->companies_id)){
                    $table->where('companies_id', $request->companies_id);
                }

                $ads = $table->paginate(config('naz.paginate'));
            }

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return AdsResource::collection($ads);
    }

    public function store(Request $request)
    {
       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|min:3',
            'state'                 => 'sometimes|nullable|in:Weapon,Accessories,Other',
            'price'                 => 'required|numeric',
            'is_used'               => 'required|boolean',
            'is_shipping'           => 'required|boolean',
            'status'                => 'required|in:Published,Draft,Pending,Canceled,Expired',
            'photo'                 => 'sometimes|nullable|image',
            'attribute_vals'        => 'sometimes|nullable|array',
            'tags'                  => 'sometimes|nullable|array',
            'items'                 => 'sometimes|nullable|array',
            'galleries'             => 'sometimes|nullable|array',
            'seller'                => 'sometimes|nullable|string',
            'areas_id'              => 'sometimes|nullable|integer|exists:areas,id',
            'product_brands_id'     => 'sometimes|nullable|integer|exists:product_brands,id',
            'product_categories_id' => 'sometimes|nullable|integer|exists:product_categories,id',
            'product_types_id'      => 'sometimes|nullable|integer|exists:product_types,id',
            'companies_id'          => 'sometimes|nullable|integer|exists:companies,id',
            'purchase_packages_id'       => 'sometimes|nullable|integer|exists:purchase_packages,id',
            'products_id'           => 'sometimes|nullable|integer|exists:products,id',
            'email'                 => 'sometimes|nullable|email'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));


        DB::beginTransaction();

        try {
            $today   = date('Y-m-d');

            $user_id = Auth::user()->id;

            if(isset($request->purchase_packages_id)){
                $rem = PurchasePackage::find($request->purchase_packages_id);
                $remain = $rem->remaining();
                if($remain <= 0){
                    throw new \Exception('This package quantity limit exceeded!!');
                }

                $package = PurchasePackage::where('id', $request->purchase_packages_id)->where('users_id', '=', $user_id)->where('expire', '>', $today)->count(); //Check it is expire or not
                if($package <= 0){
                    throw new \Exception('This package was expired!!');
                }
            }

            $ads = new Ads();
            $ads->name        = $request->name;
            if (isset($request->state)){
                $ads->state       = $request->state;
            }
            $ads->price       = $request->price;
            $ads->is_used     = $request->is_used;
            $ads->is_shipping = $request->is_shipping;
            $ads->expire      = date('Y-m-d',  strtotime($today.'+60 days' )); //Expire date set to 60 based on current date
            $ads->users_id    = Auth::id();
            $ads->seller      = $request->seller;
            $ads->areas_id              = $request->areas_id;
            $ads->product_brands_id     = $request->product_brands_id;
            $ads->product_categories_id = $request->product_categories_id;
            $ads->product_types_id      = $request->product_types_id;
            if (isset($request->companies_id)) {
                $ads->companies_id          = $request->companies_id;
            }
            if (isset($request->purchase_packages_id)) {
                $ads->purchase_packages_id       = $request->purchase_packages_id;
                $ads->status      = $request->status;
            }else{
                //$ads->status       = 'Pending';
                $ads->status      = in_array( $request->status, [ 'Draft','Pending','Canceled','Expired' ] ) ? $request->status : 'Draft';
            }

            $ads->products_id           = $request->products_id;
            $ads->email         = $request->email;
            $ads->phone         = $request->phone;
            $ads->contact_time  = $request->contact_time;
            $ads->brand         = $request->brand;
            $ads->category      = $request->category;
            $ads->product_types = $request->product_types;
            $ads->descriptions  = $request->descriptions;

            if ($request->has('photo')) {
                if (isset($request->photo)) {
                    $image = $request->file('photo');
                    $photo_name = Str::slug($request->input('name')) . '_' . time();
                    $folder = '/uploads/ads/';
                    $filePath = $folder . $photo_name . '.' . $image->getClientOriginalExtension();

                    $this->uploadOne($image, $folder, 'public', $photo_name);

                    $ads->photo = $filePath;
                }
            }

            $ads->save();


            if( isset( $request->items ) && \is_array( $request->items ) ) {
                foreach( $request->items as $key => $item ) {
                    $ads_item = new AdsItem();

                    $ads_item->quantity     = $item['quantity'];
                    $ads_item->descriptions = $item['descriptions'];

                    $name          = $ads->id . '_item_' . $key . time();
                    $folder        = '/uploads/ads/';
                    $itemfilePath  = $folder . $name . '.' . $item['photo']->getClientOriginalExtension();
                    $this->uploadOne( $item['photo'], $folder, 'public', $name);

                    $ads_item->photo        = $itemfilePath;
                    $ads_item->ads_id       = $ads->id;
                    $ads_item->products_id  = $item['products_id'];

                    $ads_item->save();
                }
            }


            if( isset( $request->attribute_vals ) ) {
                foreach( $request->attribute_vals as $key => $attribute ) {
                    $ads_attribute          = new AdsAttribute();
                    $ads_attribute->name    = $key ;
                    $ads_attribute->values  = $attribute;
                    $ads_attribute->ads_id  = $ads->id;
                    $ads_attribute->save();
                }
            }

            if ( isset( $request->tags ) && \is_array( $request->tags ) ) {
                foreach( $request->tags as $tag ) {
                    $ads_tag          = new AdsTag();

                    $ads_tag->name    = $tag;
                    $ads_tag->ads_id  = $ads->id;

                    $ads_tag->save();
                }
            }

            if ($request->has('galleries')) {

                foreach( $request->file('galleries') as $key => $gallery ) {
                    $ads_photo = new AdsPhoto();
                    $name      = $ads->id . '_gallery_' . $key . time();
                    $folder    = '/uploads/ads/';
                    $filePath  = $folder . $name . '.' . $gallery->getClientOriginalExtension();
                    $this->uploadOne( $gallery, $folder, 'public', $name);

                    $ads_photo->name   = $filePath;
                    $ads_photo->ads_id = $ads->id;

                    $ads_photo->save();
                }
            }

        } catch (\Exception $ex) {
            //dd($ex);
            DB::rollBack();
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        DB::commit();

        try {
            /**
             * Notification when post new ads
             */

            $subscriber = Subscription::where('seller_id', $request->users_id)->get();

            foreach ($subscriber as $row){
                if (isset($row->user->email)) {
                    Notification::route('mail' , $row->user->email)->notify(new SubscribeNotification($ads));
                }
            }

            /**
             * /Notification when post new ads
             */
        } catch (\Exception $e){
            return response()->json([
                'message' => 'Subscription Email Not send. But Data Successfully Saved',
                'data' => new AdsResource($ads)
            ]);
        }

        return new AdsResource($ads);
    }

    public function show($id)
    {
        try{
            $ads = Ads::find($id);

            if(!$ads)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new AdsResource($ads);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|min:3',
            'state'                 => 'sometimes|nullable|in:Weapon,Accessories,Other',
            'price'                 => 'required|numeric',
            'is_used'               => 'required|boolean',
            'is_shipping'           => 'required|boolean',
            'status'                => 'required|in:Published,Draft,Pending,Canceled,Expired',
            'photo'                 => 'sometimes|nullable|image',
            'attribute_vals'        => 'sometimes|nullable|array',
            'tags'                  => 'sometimes|nullable|array',
            'items'                 => 'sometimes|nullable|array',
            'galleries'             => 'sometimes|nullable|array',
            'seller'                => 'sometimes|nullable|string',
            'areas_id'              => 'sometimes|nullable|integer|exists:areas,id',
            'product_brands_id'     => 'sometimes|nullable|integer|exists:product_brands,id',
            'product_categories_id' => 'sometimes|nullable|integer|exists:product_categories,id',
            'product_types_id'      => 'sometimes|nullable|integer|exists:product_types,id',
            'companies_id'          => 'sometimes|nullable|integer|exists:companies,id',
            //'purchase_packages_id'       => 'sometimes|nullable|integer|exists:purchase_packages,id',
            'products_id'           => 'sometimes|nullable|integer|exists:products,id',
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        DB::beginTransaction();

        try {

            $ads              = Ads::find($id);

            if (isset($request->status)) {
                $ads->status = $request->status;
            }

           $ads->name        = $request->name;
            if (isset($request->state)){
                $ads->state       = $request->state;
            }
           $ads->price       = $request->price;
           $ads->is_used     = $request->is_used;
           $ads->is_shipping = $request->is_shipping;
           $ads->status      = $request->status;
           $ads->seller      = $request->seller;

           $ads->product_brands_id     = $request->product_brands_id;
           $ads->product_categories_id = $request->product_categories_id;
           $ads->product_types_id      = $request->product_types_id;
            if (isset($request->companies_id)) {
                $ads->companies_id          = $request->companies_id;
            }

           $ads->products_id           = $request->products_id;
           $ads->email         = $request->email;
           $ads->phone         = $request->phone;
           $ads->contact_time  = $request->contact_time;
           $ads->brand         = $request->brand;
           $ads->category      = $request->category;
           $ads->product_types = $request->product_types;
           $ads->descriptions  = $request->descriptions;

           if ($request->has('photo')) {
               if (isset($request->photo)) {
                   $image = $request->file('photo');
                   $photo_name = Str::slug($request->input('name')) . '_' . time();
                   $folder = '/uploads/ads/';
                   $filePath = $folder . $photo_name . '.' . $image->getClientOriginalExtension();

                   $this->uploadOne($image, $folder, 'public', $photo_name);

                   if (File::exists($ads->photo)) {
                       File::delete($ads->photo);
                   }

                   $ads->photo = $filePath;
               }
        }else{
               $ads->photo = null;
       }

        $ads->save();

        if( isset( $request->items ) && \is_array( $request->items ) ) {
            foreach( $request->items as $key => $item ) {
                $ads_item = AdsItem::where( 'ads_id', $ads->id )->where('id', $item[id])->first();

                $ads_item->quantity     = $item['quantity'];
                $ads_item->descriptions = $item['descriptions'];

                $name          = $ads->id . '_item_' . $key . time();
                $folder        = '/uploads/ads/';
                $itemfilePath  = $folder . $name . '.' . $item->getClientOriginalExtension();
                $this->uploadOne( $item, $folder, 'public', $name);

                if( File::exists( $ads_item->photo ) ) {
                    File::delete( $ads_item->photo );
                }

                $ads_item->photo        = $itemfilePath;
                $ads_item->products_id  = $item['products_id'];
                $ads_item->save();
            }
        }


        if( isset( $request->attribute_vals ) ) {
            AdsAttribute::where( 'ads_id', $ads->id)->delete();
            foreach( $request->attribute_vals as $key => $attribute ) {
                $ads_attribute          = new AdsAttribute();
                $ads_attribute->name    = $key ;
                $ads_attribute->values  = $attribute;
                $ads_attribute->ads_id  = $ads->id;
                $ads_attribute->save();
            }
        }

        if ( isset( $request->tags ) && \is_array( $request->tags ) ) {
            foreach( $request->tags as $tag ) {
                $ads_tag = AdsTag::where( 'ads_id', $ads->id )->where('id', $item[id])->first();
                $ads_tag->name     = $tag;
                $ads_tag->save();
            }
        }

        if ($request->has('galleries')) {
            foreach( $request->file('galleries') as $key => $gallery ) {
                $ads_photo = new AdsPhoto();
                $name      = $ads->id . '_gallery_' . $key . time();
                $folder    = '/uploads/ads/';
                $filePath  = $folder . $name . '.' . $gallery->getClientOriginalExtension();
                $this->uploadOne( $gallery, $folder, 'public', $name);

                if( File::exists( $ads_photo->name ) ) {
                    File::delete( $ads_photo->name );
                }

                $ads_photo->name   = $filePath;
                $ads_photo->ads_id = $ads->id;

                $ads_photo->save();
            }
        }

        } catch (\Exception $ex) {
            DB::rollBack();
            //dd($ex);
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        DB::commit();
        return new AdsResource($ads);
    }

    public function destroy($id)
    {
        try{
            Ads::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }

    public function searches(Request $request){
        try{
            $today = date('Y-m-d');
            $tablex = Ads::orderBy('id', 'DESC')->where('status', 'Published')->where('expire', '>', $today);
            if (isset($request->name)) {
                $tablex->where('name', 'like', '%'.$request->name.'%');
            }
            if (isset($request->areas_id)) {
                $tablex->where('areas_id', $request->areas_id);
            }
            if (isset($request->product_brands_id)) {
                $tablex->where('product_brands_id', $request->product_brands_id);
            }
            if (isset($request->product_categories_id)) {
                $tablex->where('product_categories_id', $request->product_categories_id);
            }
            if (isset($request->product_types_id)) {
                $tablex->where('product_types_id', $request->product_types_id);
            }
            if (isset($request->companies_id)) {
                $tablex->where('companies_id', $request->companies_id);
            }
            if (isset($request->products_id)) {
                $tablex->where('products_id', $request->products_id);
            }
            if (isset($request->is_limit)) {
                $tablex->take(config('naz.search_limit'));
            }
            $table = $tablex->paginate(config('naz.paginate'));
        }catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }
        return AdsResource::collection($table);
    }

    public function statusUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Published,Draft,Pending,Canceled,Expired',
            'ads' => 'required|array',
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        DB::beginTransaction();

        try {
            if( isset( $request->ads ) ) {
                //DB::connection()->enableQueryLog();
                Ads::whereIn('id',$request->ads)->update(['status' => $request->status]);
                $table = Ads::whereIn('id',$request->ads)->get();
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        DB::commit();

        return AdsResource::collection($table);
    }


    public function getByActiveAds(Request $request){
        try{
            $today = date('Y-m-d');
            $tablex = Ads::orderBy('id', 'DESC')->where('status', 'Published')->where('expire', '>', $today)->count();
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(['count' => $tablex]);
    }
}
