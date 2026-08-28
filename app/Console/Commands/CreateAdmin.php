<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {--name=} {--email=}';

    protected $description = 'Create or promote the administrator account';

    public function handle(): int
    {
        $name = $this->option('name') ?: text('Nama admin', default: 'Reza', required: true);
        $email = $this->option('email') ?: text('Email admin', required: true);
        $plainPassword = password('Kata sandi (minimal 12 karakter)', required: true);

        $validator = Validator::make(compact('name', 'email', 'plainPassword'), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'plainPassword' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($plainPassword), 'is_admin' => true],
        );

        $this->info('Admin siap. Buka '.route('admin.login').' lalu masuk dengan email tersebut.');

        return self::SUCCESS;
    }
}
