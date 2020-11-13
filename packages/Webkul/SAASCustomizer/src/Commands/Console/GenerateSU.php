<?php

namespace Webkul\SAASCustomizer\Commands\Console;

use Illuminate\Console\Command;
use Webkul\SAASCustomizer\Repositories\Super\CompanyRepository;
use Webkul\SAASCustomizer\Repositories\Super\CompanyDetailsRepository;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Carbon\Carbon;
use Validator;

class GenerateSU extends Command
{
    /**
     * Holds the execution signature of the command needed
     * to be executed for generating super user
     */
    protected $signature = 'saas:install';

    /**
     * Will inhibit the description related to this
     * command's role
     */
    protected $description = 'Generates only one super user for the system';

    public function __construct(
        CompanyRepository $company,
        CompanyDetailsRepository $details,
        Admin $admin,
        Role $role
    )   {
        parent::__construct();

        $this->company = $company;
        $this->details = $details;
        $this->admin = $admin;
        $this->role = $role;
    }

    /**
     * Does the all sought of lifting required to be performed for
     * generating a super user
     */
    public function handle()
    {
        // running `php artisan migrate`
        $this->warn('Step: Migrating all tables into database (will take a while)...');
        
        if ($this->confirm('Confirm: Please confirm, Do you want to remove all tables and records?')) {
            $migrate = shell_exec('php artisan migrate:fresh');
        } else {
            $migrate = shell_exec('php artisan migrate');
        }
        
        $this->info($migrate);

        // running `php artisan vendor:publish --all`
        $this->warn('Step: Publishing Assets and Configurations...');
        $result = shell_exec('php artisan vendor:publish --all');
        $this->info($result);

        // running `php artisan storage:link`
        $this->warn('Step: Linking Storage directory...');
        $result = shell_exec('php artisan storage:link');
        $this->info($result);

        $this->comment('Info: Generating super user for the system.');
        $name = $this->ask('Input: Please enter super user name?');

        $validator = Validator::make([
            'first_name' => $name
        ], [
            'first_name' => 'required',
        ]);

        if ($validator->fails()) {
            $this->comment('Warning: Name invalid, please enter try again.');

            return false;
        }

        $this->comment('Info: You entered = '. $name);
        $email = $this->ask('Input: Please enter email?');

        $data = [
            'email' => $email
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email|unique:super_admins,email',
        ]);

        if($validator->fails()) {
            $this->comment('Warning: Email already exists or invalid, please enter try again.');

            return false;
        }

        unset($data);

        $this->comment('Info: You entered = ' . $email);
        $password = $this->ask('Input: Please enter password?');
        $data = ['password' => $password];

        $validator = Validator::make($data, [
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            $this->comment('Warning: Password invalid, make sure password is atleast 6 characters of length.');

            return false;
        }

        $this->comment('Info: You entered = '. $password);

        unset($data);

        if ($this->confirm('Confirm: Please confirm all the entered details are correct?')) {
            $data = [
                'first_name'    => $name,
                'email'         => $email,
                'password'      => bcrypt($password),
            ];

            $result = $this->generateSuperUserCompany($data);

            if ($result) {
                $this->comment('Success: Super user for the system is created successfully.');
            } else {
                $this->comment('Warning: Super user for the system already exists, please contact support@bagisto.com for troubleshooting.');
            }

        } else {
            $this->comment('Warning: Please try again for creating the super user.');
        }
    }

    public function generateSuperUserCompany($data)
    {
        \DB::insert('insert into super_admins (first_name, email, password, status, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', [$data['first_name'], $data['email'], $data['password'], 1, now(), now()]);

        $super_channel = \DB::select('select * from super_channel');

        if ( count($super_channel) == 0) {
            $this->createSuperChannel();
        }

        return true;
    }

    public function createSuperChannel()
    {
        $now = Carbon::now();
        
        \DB::table('super_locales')->delete();
        \DB::table('super_locales')->insert([
            ['id' => '1', 'code' => 'en', 'name' => 'English', 'direction' => 'ltr', 'created_at' => $now, 'updated_at' => $now]
        ]);

        \DB::table('super_currencies')->delete();
        \DB::table('super_currencies')->insert([
            ['id' => '1', 'code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'created_at' => $now, 'updated_at' => $now]
        ]);

        \DB::table('super_channel')->delete();
        \DB::table('super_channel')->insert([
            [
                'id'        => '1',
                'code'      => 'default',
                'name'      => 'Default Channel',
                'hostname'  => '',
                'home_page_content' => '<div class="banner-container">
                <div class="full-banner"><img src="../../../vendor/webkul/saas/assets/images/banner-full.png" />
                <div class="banner-content">
                <h1>Turn Your Passion Into a Business</h1>
                <p>Shake hand with the most reported company known for eCommerce and the marketplace. We reached around all the corners of the world. We serve the customer with our best service experiences.</p>
                <a href="../../../company/register" class="btn btn-black btn-lg">Open Shop Now</a></div>
                </div>
                <div class="left-banner"><img src="../../../vendor/webkul/saas/assets/images/banner-left.jpg" /></div>
                <div class="right-banner"><img src="../../../vendor/webkul/saas/assets/images/banner-right-1.png" /><img src="../../../vendor/webkul/saas/assets/images/banner-right-2.jpg" /></div>
                </div>',
                'footer_page_content' => '<div class="list-container"><span class="list-heading">Connect With Us</span><ul class="list-group"><li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li><li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li><li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li><li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li><li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li></ul></div>',
                'home_seo' => '{"meta_title": "Super Meta Title", "meta_keywords": "Super Meta Keyword","meta_description": "Super Meta Description"}',
                'default_locale_id' => '1',
                'base_currency_id' => '1',
                'created_at' => $now,
                'updated_at' => $now
                ]
        ]);

        \DB::table('super_channel_locales')->insert([
            'super_channel_id' => 1,
            'locale_id' => 1,
        ]);

        \DB::table('super_channel_currencies')->insert([
            'super_channel_id' => 1,
            'currency_id' => 1,
        ]);
    }
}