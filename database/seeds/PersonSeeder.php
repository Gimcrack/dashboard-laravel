<?php

use Illuminate\Database\Seeder;
use App\Person;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //empty table first
        //Person::truncate();

        Person::create(['name' => 'Jeremy Bloomstrom']);

        $faker =  Faker\Factory::create();

        foreach(range(1,20) as $num) {
          Person::create(['name' => $faker->name]);
        }
    }
}
