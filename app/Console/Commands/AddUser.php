<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\User;


class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user {email} {username*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add User';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        $username = implode("",$this->argument('username'));
        
        $request = [
            'name' => "Command",
            'email' => $email,
            'mobile' => '9988776655',
            'username' => $username,
            'password' => bcrypt('password'),
        ];

        $user = User::create($request);
        $this->info($user);
    }
}
