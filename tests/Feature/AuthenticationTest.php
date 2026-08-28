<?php
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
uses(LazilyRefreshDatabase::class);
it('redirects guests away from admin',fn()=>$this->get(route('admin.dashboard'))->assertRedirect(route('admin.login')));
it('authenticates only an admin with valid credentials',function(){ $admin=User::factory()->create(['is_admin'=>true,'password'=>'correct-password']); $this->post(route('admin.login.store'),['email'=>$admin->email,'password'=>'wrong-password'])->assertSessionHasErrors('email'); $this->post(route('admin.login.store'),['email'=>$admin->email,'password'=>'correct-password'])->assertRedirect(route('admin.dashboard')); $this->assertAuthenticatedAs($admin); });
it('forbids a non admin account',function(){ $user=User::factory()->create(['is_admin'=>false]); $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden(); });
