<?php
namespace App\Services\Web;

use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\SocialLinkRepositoryInterface;
use App\Services\Traits\UserValidityTrait;
use Illuminate\Support\Str;

class BaseService {
    use UserValidityTrait;

    private $settingRepository;
    private $socialLinkRepository;
    private $recordTypeRepository;
    public function __construct(SettingRepositoryInterface $settingRepository, SocialLinkRepositoryInterface $socialLinkRepository,
                                RecordTypeRepositoryInterface $recordTypeRepository){
        $this->settingRepository = $settingRepository;
        $this->socialLinkRepository = $socialLinkRepository;
        $this->recordTypeRepository = $recordTypeRepository;

    }
    public function getContactEmails()
    {
        $contact_emails = $this->settingRepository->getBy('name', 'contact_emails');
        return $contact_emails ? Str::of($contact_emails->value)->explode(','): [] ;
    }
    public function getContactNumbers(){
        $contact_numbers = $this->settingRepository->getBy('name','contact_numbers');
        return $contact_numbers ? Str::of($contact_numbers->value)->explode(','): [] ;
    }
    public function getLocation(){
        $location = $this->settingRepository->getBy('name','office_location');
        return $location ? $location->value: __('global.please_contact_us') ;
    }
    public function getSocialLinks(){
        return $this->socialLinkRepository->all();
    }
}
