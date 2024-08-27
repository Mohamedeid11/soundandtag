<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Profile\SaveRecordRequest;
use App\Models\UserHit;
use App\Services\Web\ProfileService;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends BaseWebController
{
    private $profileService;
    public function __construct(ProfileService $profileService){
        parent::__construct();
        $this->profileService = $profileService;
    }
    public function index(){
        return view('web.edit-profile');
    }
    public function details(){
        $user = auth()->user();
        return json_encode(
            [
                'status'=>1,
                'is_public'=>$this->profileService->getPublicStatus($user),
                'records' => $this->profileService->getRecords($user)
            ]
        );
    }

    /**
     *
     * @param SaveRecordRequest $request
     */
    public function saveRecord(SaveRecordRequest $request){

        $user = auth()->user();

        if(! $user->validity){
            return json_encode(
                [
                    'status'=> 0
                ]
            );
        }

        $record = $this->profileService->saveRecord($user, $request->all());

        if (!$record){

            return json_encode(
                [
                    'error'=> 'The combined length of the full name is too long.',
                    'status'=> 0
                ]
            );

        }

        $record->refresh();

        return json_encode(
            [
                'status'=>1,
                'is_public'=>$this->profileService->getPublicStatus($user),
                'new_record' => $record
            ]
        );
    }
    public function publicProfile(Request $request, $username){
        $profile = $this->profileService->getProfile($username);

        if($profile) {
            $user = $profile->get('user');
            $user->today_hits = $user->hits()->firstOrCreate(['day'=>date('Y-m-d')], ['count'=>0]);
            $user->today_hits->count = $user->today_hits->count + 1;
            $user->today_hits->save();
            $name = $user->trans('name');
            $page = __('global.profile_title', ['name'=>$name]);
            // $desc = __('global.profile_desc', ['name'=>$name, 'country'=>$user->country->trans('name')]);
            $desc = "";
            $image = "uploads/profile/card-{$user->username}.jpg";
            $image = Storage::disk('public')->exists($image) ? storage_asset($image) : storage_asset($user->image);
            SEOMeta::setTitle($page);
            SEOMeta::setDescription($desc);

            OpenGraph::setTitle($page);
            OpenGraph::setDescription($desc);
            OpenGraph::setUrl($request->fullUrl());
            OpenGraph::addImage($image, ['width'=>800, 'height'=>450]);

            TwitterCard::setTitle($page);
            TwitterCard::setUrl($request->fullUrl());
            TwitterCard::setDescription($desc);
            TwitterCard::addValue('image', $image);
            TwitterCard::setType("summary_large_image");
            TwitterCard::addImage($image);

            JsonLd::setTitle($page);
            JsonLd::setDescription($desc);
            JsonLd::addImage($image);
            return view('web.profile', compact('profile', 'image'));
        }
        else {
            $user = $this->profileService->getUser($username);
            return view('web.profile-doesnt-exist', compact('user'));
        }
    }
}
