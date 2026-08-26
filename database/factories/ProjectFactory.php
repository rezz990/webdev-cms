<?php
namespace Database\Factories;
use App\Enums\ContentStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
/** @extends Factory<Project> */
class ProjectFactory extends Factory { protected $model=Project::class; public function definition(): array { $name=fake()->unique()->words(3,true); return ['name'=>$name,'slug'=>fake()->unique()->slug(),'summary'=>fake()->paragraph(),'content'=>fake()->paragraphs(4,true),'status'=>ContentStatus::Draft,'project_status'=>'Dalam pengembangan','year'=>(int)date('Y'),'sort_order'=>0,'is_featured'=>false]; } public function published(): static { return $this->state(fn()=>['status'=>ContentStatus::Published,'published_at'=>now()->subDay()]); } }
