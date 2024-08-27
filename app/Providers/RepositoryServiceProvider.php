<?php

namespace App\Providers;

use App\Repositories\Eloquent\AdminRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ContactMessageRepository;
use App\Repositories\Eloquent\CorporateEmployeeRepository;
use App\Repositories\Eloquent\CountryRepository;
use App\Repositories\Eloquent\GuideRepository;
use App\Repositories\Eloquent\NewsletterEmailRepository;
use App\Repositories\Eloquent\PageRepository;
use App\Repositories\Eloquent\PlanRepository;
use App\Repositories\Eloquent\RecordRepository;
use App\Repositories\Eloquent\RecordTypeRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\SocialLinkRepository;
use App\Repositories\Eloquent\SubscriptionRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\Eloquent\TryingRecordRepository;
use App\Repositories\Eloquent\TryingUserRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;
use App\Repositories\Interfaces\CorporateEmployeeRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\GuideRepositoryInterface;
use App\Repositories\Interfaces\NewsletterEmailRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\RecordRepositoryInterface;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\SocialLinkRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\Interfaces\TryingRecordRepositoryInterface;
use App\Repositories\Interfaces\TryingUserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RecordTypeRepositoryInterface::class, RecordTypeRepository::class);
        $this->app->bind(ContactMessageRepositoryInterface::class, ContactMessageRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(RecordRepositoryInterface::class, RecordRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(SocialLinkRepositoryInterface::class, SocialLinkRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(GuideRepositoryInterface::class, GuideRepository::class);
        $this->app->bind(NewsletterEmailRepositoryInterface::class, NewsletterEmailRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(CorporateEmployeeRepositoryInterface::class, CorporateEmployeeRepository::class);
        $this->app->bind(TryingUserRepositoryInterface::class, TryingUserRepository::class);
        $this->app->bind(TryingRecordRepositoryInterface::class, TryingRecordRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }
}
