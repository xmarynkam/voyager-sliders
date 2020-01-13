<?php

use Pvtl\VoyagerSliders\Sliders;

use TCG\Voyager\Traits\Seedable;
use Illuminate\Database\Seeder;

class SlidersDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__ . '/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('SlidersTableSeeder');
    }
}
