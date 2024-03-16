<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sensor = new Sensor;
        $sensor->start_node = 11;
        $sensor->end_node = 2;
        $sensor->lat = 41.6985859097092;
        $sensor->long = 23.368073633575072;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 18;
        $sensor->end_node = 17;
        $sensor->lat = 41.763163420267965;
        $sensor->long = 23.421722625871904;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 0;
        $sensor->end_node = 1;
        $sensor->lat = 41.65431682487063;
        $sensor->long = 23.483904798462365;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 3;
        $sensor->end_node = 6;
        $sensor->lat = 41.69994256939326;
        $sensor->long = 23.467238317626745;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 14;
        $sensor->end_node = 9;
        $sensor->lat = 41.74059256194718;
        $sensor->long = 23.46862309809758;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 11;
        $sensor->end_node = 15;
        $sensor->lat = 41.744644389766;
        $sensor->long = 23.331752126325352;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 22;
        $sensor->end_node = 24;
        $sensor->lat = 41.85544954467599;
        $sensor->long = 23.355767323035174;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 15;
        $sensor->end_node = 20;
        $sensor->lat = 41.80043345515622;
        $sensor->long = 23.361121382675325;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 20;
        $sensor->end_node = 19;
        $sensor->lat = 41.77257468943888;
        $sensor->long = 223.39625899320711;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 20;
        $sensor->end_node = 22;
        $sensor->lat = 41.8237012254274;
        $sensor->long = 23.376975754834337;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 3;
        $sensor->end_node = 0;
        $sensor->lat = 41.598821451700076;
        $sensor->long = 23.664390750073938;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 3;
        $sensor->end_node = 1;
        $sensor->lat = 41.673344415878915;
        $sensor->long = 23.445506344545134;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 0;
        $sensor->end_node = 4;
        $sensor->lat = 41.64558613309176;
        $sensor->long = 23.56746070173557;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 8;
        $sensor->end_node = 9;
        $sensor->lat = 41.71356844051148;
        $sensor->long = 23.48855235496682;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 4;
        $sensor->end_node = 8;
        $sensor->lat = 41.67478930220614;
        $sensor->long = 23.50664561733028;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 19;
        $sensor->end_node = 18;
        $sensor->lat = 41.76915747694424;
        $sensor->long = 23.41019787291296;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 17;
        $sensor->end_node = 11;
        $sensor->lat = 41.74890447793275;
        $sensor->long = 23.406631356878144;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 7;
        $sensor->end_node = 13;
        $sensor->lat = 41.72401940057292;
        $sensor->long = 23.44289792268485;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 8;
        $sensor->end_node = 12;
        $sensor->lat = 41.732790010144925;
        $sensor->long = 23.523196611862726;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 5;
        $sensor->end_node = 11;
        $sensor->lat = 41.69640097383532;
        $sensor->long = 23.3967064495427;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 1;
        $sensor->end_node = 5;
        $sensor->lat = 41.68270610318753;
        $sensor->long = 23.422819284875175;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 6;
        $sensor->end_node = 9;
        $sensor->lat = 41.72087607526183;
        $sensor->long = 23.47691793816803;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 18;
        $sensor->end_node = 21;
        $sensor->lat = 41.77372756620997;
        $sensor->long = 23.43120001431689;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 4;
        $sensor->end_node = 6;
        $sensor->lat = 41.6896957592400;
        $sensor->long = 23.482137919492992;
        $sensor->save();

        $sensor = new Sensor;
        $sensor->start_node = 12;
        $sensor->end_node = 16;
        $sensor->lat = 41.75205898022242;
        $sensor->long = 23.54074104136321;
        $sensor->save();

    }
}
