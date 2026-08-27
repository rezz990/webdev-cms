<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ContactRequest extends FormRequest { public function authorize(): bool { return true; } public function rules(): array { return ['name'=>['required','string','max:100'],'email'=>['required','email:rfc','max:255'],'subject'=>['nullable','string','max:150'],'message'=>['required','string','min:10','max:5000'],'website'=>['nullable','max:0']]; } }
