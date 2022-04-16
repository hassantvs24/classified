<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CompanyResource;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;

class CompanyController extends Controller
{

    use UploadTrait;

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_id'      => 'sometimes|nullable|exists:users,id',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {
            if(Auth::user()->types == 'Admin'){
                $companiesx = Company::orderBy('id', 'DESC');

                if (isset($request->users_id)) {
                    $companiesx->where( 'users_id', $request->users_id );
                }

                $companies = $companiesx->get();
            }else {
                $companies = Company::where( 'users_id', Auth::id())->orderBy('id', 'DESC')->get();
            }

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return CompanyResource::collection($companies);
    }


    public function store(Request $request )
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3|unique:companies,name',
            'status'        => 'required|boolean',
            'users_id'      => 'required|exists:users,id',
            'logo'          => 'sometimes|nullable|image',
            'description'   => 'sometimes|nullable',
            'contact'       => 'sometimes|nullable|max:15|string',
            'contact_person'=> 'sometimes|nullable|string',
            'website'       => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $company = new Company();
            $company->name           = $request->name;
            $company->status         = $request->status;
            $company->users_id       = $request->users_id;
            $company->description    = $request->description;
            $company->contact        = $request->contact;
            $company->contact_person =  $request->contact_person;
            $company->website        = $request->website;

            if ( $request->has('logo') ) {
                if (isset($request->logo)) {
                    // Get image file
                    $image = $request->file('logo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/company/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $company->logo = $filePath;
                }
            }

            $company->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);
    }


    public function show($id)
    {
        try{

            $company = Company::find($id);

            if(!$company)
                return response()->json(config('naz.n_found'), config('naz.not_found'));

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);

    }


    public function update(Request $request, $id )
    {

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|min:3|unique:companies,name,'. $id,
            'status'         => 'required|boolean',
            'users_id'      => 'required|exists:users,id',
            'logo'           => 'sometimes|nullable|file',
            'description'    => 'sometimes|nullable',
            'contact'        => 'sometimes|nullable|max:15|string',
            'contact_person' => 'sometimes|nullable|string',
            'website'        => 'sometimes|nullable|string'
        ]);

        if ( $validator->fails() ) return response()->json( $validator->errors(), config('naz.validation') );

        try {

            $company = Company::find($id);
            $company->name           = $request->name;
            $company->status         = $request->status;
            $company->users_id       = $request->users_id;
            $company->description    = $request->description;
            $company->contact        = $request->contact;
            $company->contact_person = $request->contact_person;
            $company->website        = $request->website;

            if ( $request->has('logo') ) {
                if (isset($request->logo)) {
                    // Get image file
                    $image = $request->file('logo');
                    // Make a image name based on user name and current timestamp
                    $name = Str::slug($request->input('name')) . '_' . time();
                    // Define folder path
                    $folder = '/uploads/company/';
                    // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                    // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                    // Set user profile image path in database to filePath
                    $company->logo = $filePath;
                }
            }else{
                $company->logo = null;
            }

            $company->save();

        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return new CompanyResource($company);
    }


    public function destroy($id)
    {
        try{
            Company::destroy($id);
        } catch (\Exception $ex) {
            return response()->json(config('naz.db'), config('naz.db_error'));
        }

        return response()->json(config('naz.del'));
    }
}
