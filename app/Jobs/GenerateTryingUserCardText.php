<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GenerateTryingUserCardText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userImage;
    protected $userEmail;
    protected $userId;
    protected $firstName;
    public $timeout = 7200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userImage, $userEmail, $userId, $firstName)
    {
        $this->userImage = $userImage;
        $this->userEmail = $userEmail;
        $this->userId = $userId;
        $this->firstName = $firstName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = new Request;
        $name = $this->firstName;
        $page = __('global.profile_title', ['name'=>$name]);
        $desc = __('global.trying_profile_desc', ['name'=>$name]);
        $image = "uploads/profile/card-{$this->userId}.jpg";
        $image = Storage::disk('public')->exists($image) ? storage_asset($image) : storage_asset($this->userImage);
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
    }
}
