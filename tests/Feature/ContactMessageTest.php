<?php
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
uses(LazilyRefreshDatabase::class);
it('validates and stores a contact message',function(){ $this->post(route('contact.store'),[])->assertSessionHasErrors(['name','email','message']); $this->post(route('contact.store'),['name'=>'Dina','email'=>'dina@example.com','message'=>'Saya ingin membahas sebuah project web.','website'=>''])->assertRedirect()->assertSessionHas('success'); $this->assertDatabaseHas('contact_messages',['email'=>'dina@example.com']); });
it('rate limits repeated contact submissions',function(){ $payload=['name'=>'Dina','email'=>'dina@example.com','message'=>'Pesan yang cukup panjang untuk validasi.','website'=>'']; foreach(range(1,5) as $attempt){$this->post(route('contact.store'),$payload)->assertRedirect();} $this->post(route('contact.store'),$payload)->assertTooManyRequests(); });
