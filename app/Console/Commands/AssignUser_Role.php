<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssignUser_Role extends Command
{
    protected $signature = 'assignuser:role 
        {user : The ID of the user} 
        {role : The name of the role to assign}';

    protected $description = 'Assigns a role to a user.';

    protected function write($msg)
    {
        // Yes, I know I could put this out of the function for better perf, but it's only a couple more executions!
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        \Log::info($msg);
        $out->writeln($msg);
    }

    public function handle()
    {
        $user_id = $this->argument('user');
        $role = $this->argument('role');

        // If user or role is non-existent, fail.
        if ($user_id == null || $role == null)
        {
            $this->write('User or role not inputted. Please use `php artisan assignuser:role {user_id} {role}');

            return Command::FAILURE;
        }

        // Attempt to load user.
        try
        {
            $user = User::findOrFail($user_id);
        }
        catch (ModelNotFoundException $e)
        {
            $this->write('User not found with ID `' . $user_id . '`');

            return Command::FAILURE;
        }

        // Assign role.
        $user->assignRole($role);

        // Save user.
        $user->save();

        $this->write('User with ID `' . $user_id . '` added to role `' . $role . '`!');
        
        return Command::SUCCESS;
    }
}
