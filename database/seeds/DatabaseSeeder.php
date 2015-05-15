<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Comment;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		$this->call('CommentTableSeeder');
        $this->command->info('Comment table seeded.');
	}

}

class CommentTableSeeder extends Seeder {

    public function run()
    {
        DB::table('comments')->delete();
        Comment::create(
            array(
                'email' => 'foo@bar.com',
                'name' => 'Fooz',
                'comment' => 'Here is a comment',
                'ip' => '127.0.0.1',
                'slug' => '2015/04/27/durham-restaurant-time-machine.html',
            )
        );
        Comment::create(
           array(
                'email' => 'bar@foo.com',
                'name' => 'Bar',
                'comment' => 'No comment.',
                'ip' => '127.0.0.1',
                'slug' => '2015/04/27/durham-restaurant-time-machine.html',
            )
        );
    }

}
