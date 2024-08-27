<?php

namespace App\Services\Web;

use App\Repositories\Interfaces\ContactMessageRepositoryInterface;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\Interfaces\GuideRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Arr;

use App\Models\User;
use App\Mail\ThanksEmailAfterContactUsCall;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Mpdf\Tag\Tr;

class LandingService
{
    private $contactMessageRepository;
    private $pageRepository;
    private $userRepository;
    private $faqRepository;
    private $guideRepository;
    private $planRepository;
    private $categoryRepository;
    public function __construct(
        ContactMessageRepositoryInterface $contactMessageRepository,
        PageRepositoryInterface $pageRepository,
        PlanRepositoryInterface $planRepository,
        UserRepositoryInterface $userRepository,
        FaqRepositoryInterface $faqRepository,
        GuideRepositoryInterface $guideRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->contactMessageRepository = $contactMessageRepository;
        $this->pageRepository = $pageRepository;
        $this->userRepository = $userRepository;
        $this->faqRepository = $faqRepository;
        $this->guideRepository = $guideRepository;
        $this->planRepository = $planRepository;
        $this->categoryRepository = $categoryRepository;
    }
    public function addContactMessage(array $data)
    {
        if (auth()->guard('user')->user()) {
            $data['email'] = auth()->guard('user')->user()->email;
        }
        if (!Arr::exists($data, 'name')) {
            $data['name'] = auth()->guard('user')->user()->name;
        }

        $this->contactMessageRepository->create(Arr::only($data, ['name', 'email', 'message']));

        Mail::to($data["email"])->send(new ThanksEmailAfterContactUsCall($data["name"]));
    }

    public function getAbout()
    {
        return $this->pageRepository->getBy('name', 'about');
    }

    public function getPage($page)
    {
        return $this->pageRepository->getBy('name', $page);
    }

    public function getTopPersonalProfiles()
    {
        return $this->userRepository->all(true)->public()->personal()->featured()->paginate(12);
    }

    public function getTopCorporateProfiles()
    {
        return $this->userRepository->all(true)->public()->corporate()->featured()->paginate(12);
    }
    public function getPublicStatus($user)
    {
        return (bool) $this->userRepository->all(true)->public()->where(['id' => $user->id])->first();
    }
    public function getAllProfiles($search, $account_type = null)
    {
        if ($account_type == 'personal' || $account_type == 'corporate') {
            return $this->userRepository->all(true)->public()->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
            })->where(['account_type' => $account_type])->orderBy('name', 'asc')->paginate(24)->appends(request()->query());
        } else {
            return $this->userRepository->all(true)->public()->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
            })->where('account_type', '!=', 'employee')->orderBy('name', 'asc')->paginate(24)->appends(request()->query());
        }
    }

    public function getPublicEmployeesOfCorporate($company, $search)
    {
        if ($search) {
            return $this->userRepository->all(true)->public()
                ->where('company_id', $company->id)
                ->where('name', 'like', '%' . $search . '%')
                ->orderBy('arrange', 'asc')->paginate(18);
        } else {
            return $this->userRepository->all(true)->public()->where('company_id', $company->id)->orderBy('arrange', 'asc')->paginate(18);
        }
    }


    public function getAllFaqs()
    {
        return $this->faqRepository->all();
    }

    public function getAllGuides()
    {
        return $this->guideRepository->all();
    }

    public function getPersonalPlans()
    {
        return $this->planRepository->all(true)->personal()->get();
    }

    public function getCorporatePlans()
    {
        return $this->planRepository->all(true)->corporate()->get();
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->all(true)->get();
    }
}
