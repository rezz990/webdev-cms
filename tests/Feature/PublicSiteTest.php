<?php
use App\Models\Post;
use App\Models\Project;
it('renders the homepage',fn()=>$this->get(route('home'))->assertOk()->assertSee('Halo, saya Reza'));
it('only exposes published posts',function(){ $published=Post::factory()->published()->create(['title'=>'Terlihat publik']); $draft=Post::factory()->create(['title'=>'Rahasia draft']); $this->get(route('blog.index'))->assertOk()->assertSee($published->title)->assertDontSee($draft->title); $this->get(route('blog.show',$draft))->assertNotFound(); });
it('does not expose scheduled posts before publication',function(){ $post=Post::factory()->scheduled()->create(); $this->get(route('blog.show',$post))->assertNotFound(); $this->get(route('blog.index'))->assertDontSee($post->title); });
it('publishes a scheduled post when its publication time arrives', function () {
    $post = Post::factory()->scheduled()->create(['published_at' => now()->addMinute()]);

    $this->travel(2)->minutes();

    $this->get(route('blog.show', $post))->assertOk()->assertSee($post->title);
});
it('resolves published project and post slugs',function(){ $post=Post::factory()->published()->create(); $project=Project::factory()->published()->create(); $this->get('/blog/'.$post->slug)->assertOk()->assertSee($post->title); $this->get('/projects/'.$project->slug)->assertOk()->assertSee($project->name); });
it('keeps drafts out of the sitemap',function(){ $published=Post::factory()->published()->create(); $draft=Post::factory()->create(); $this->get(route('sitemap'))->assertOk()->assertSee($published->slug)->assertDontSee($draft->slug); });
it('uses the custom Reza favicon instead of the Laravel icon', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('favicon.svg')
        ->assertDontSee('favicon.ico');
});
it('renders the anime night city developer identity on the homepage', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Membangun produk digital yang')
        ->assertSee('benar-benar dipakai.')
        ->assertSee('Project pilihan')
        ->assertSee('Dev journal')
        ->assertDontSee('CHAPTER');
});
