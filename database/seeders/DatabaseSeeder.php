<?php
namespace Database\Seeders;
use App\Enums\ContentStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
 public function run(): void
 {
  $adminEmail=config('app.admin_email'); $adminPassword=config('app.admin_password');
  if($adminEmail && $adminPassword){User::updateOrCreate(['email'=>$adminEmail],['name'=>'Reza','password'=>Hash::make($adminPassword),'is_admin'=>true]);}
  $admin=User::where('is_admin',true)->first();
  $web=Category::updateOrCreate(['slug'=>'aplikasi-web'],['name'=>'Aplikasi Web','type'=>'project']); $notes=Category::updateOrCreate(['slug'=>'catatan-dev'],['name'=>'Catatan Developer','type'=>'post']);
  foreach(['Laravel','PHP','MySQL','Blade','Tailwind CSS','Alpine.js'] as $order=>$name){Technology::updateOrCreate(['slug'=>Str::slug($name)],['name'=>$name,'sort_order'=>$order]);}
  foreach(['site_name'=>'Webdev Reza','display_name'=>'Reza','headline'=>'Web Developer','short_bio'=>'Membuat website dan project teknologi dari kebutuhan nyata.','accepting_freelance'=>'1','whatsapp'=>'62895358302211','public_email'=>'halo@reza.web-id.id','github'=>'https://github.com/rezafikkri','seo_title'=>'Webdev Reza — Developer & pembuat project','seo_description'=>'Portfolio, tulisan, dan perjalanan project teknologi Reza.'] as $key=>$value){Setting::updateOrCreate(['key'=>$key],['value'=>$value,'group'=>'profile']);}
  foreach([['Bujon Carwash','bujon-carwash','Sistem web untuk membantu operasional dan layanan bisnis carwash.'],['ShADB','shadb','Project alat bantu database yang dirancang ringkas dan praktis.'],['Blokirjudi','blokirjudi','Inisiatif teknologi untuk membantu membatasi akses ke konten judi daring.']] as $i=>$item){$project=Project::updateOrCreate(['slug'=>$item[1]],['category_id'=>$web->id,'name'=>$item[0],'summary'=>$item[2],'content'=>"Latar belakang\n\nProject ini lahir dari kebutuhan nyata. Solusi dibangun bertahap dengan fokus pada kemudahan penggunaan, keamanan, dan pemeliharaan.",'status'=>ContentStatus::Published,'project_status'=>'Selesai','year'=>(int)date('Y'),'role'=>'Full-stack developer','is_featured'=>true,'sort_order'=>$i,'published_at'=>now()->subMonths(3-$i)]); $project->technologies()->sync(Technology::limit(3)->pluck('id'));}
  if($admin && app()->isLocal()){foreach(range(1,3) as $number){Post::updateOrCreate(['slug'=>'demo-catatan-'.$number],['user_id'=>$admin->id,'category_id'=>$notes->id,'title'=>'[Demo] Catatan membangun project #'.$number,'excerpt'=>'Data demonstrasi untuk menguji tampilan blog dan alur publikasi.','content'=>'## Catatan demo\n\nKonten ini adalah **data demo** dan dapat dihapus dari dashboard.','status'=>ContentStatus::Published,'published_at'=>now()->subDays($number),'is_featured'=>$number===1]);}}
 }
}
