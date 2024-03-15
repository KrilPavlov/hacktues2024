<?php

namespace Database\Seeders;

use App\Models\NodeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NodeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $node = new NodeType;
        $node->name = 'Хижа';
        $no
        $node->save();

        $node = new NodeType;
        $node->name = "Връх";
        $node->save();

        $node = new NodeType;
        $node->name = "Недефинирано";
        $node->save();

        $node = new NodeType;
        $node->name = "Чешма";
        $node->save();

        $node = new NodeType;
        $node->name = "Водопад";
        $node->save();

        $node = new NodeType;
        $node->name = "Езеро";
        $node->save();

        $node = new NodeType;
        $node->name = "Аптечка";
        $node->save();
    }
}
