<?php
namespace Database\Factories;
use App\Enums\ContentStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
/** @extends Factory<Post> */
class PostFactory extends Factory { protected $model=Post::class; public function definition(): array { $title=fake()->unique()->sentence(4); return ['user_id'=>User::factory(),'title'=>$title,'slug'=>fake()->unique()->slug(),'excerpt'=>fake()->paragraph(),'content'=>'## '.fake()->sentence().'\n\n'.fake()->paragraphs(3,true),'status'=>ContentStatus::Draft,'is_featured'=>false]; } public function published(): static { return $this->state(fn()=>['status'=>ContentStatus::Published,'published_at'=>now()->subDay()]); } public function scheduled(): static { return $this->state(fn()=>['status'=>ContentStatus::Scheduled,'published_at'=>now()->addDay()]); } }
