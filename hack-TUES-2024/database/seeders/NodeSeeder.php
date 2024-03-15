<?php

namespace Database\Seeders;

use App\Models\Node;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $node = new Node;
        $node->lat = "41.548497";
        $node->long = "23.7857489619772";
        $node->name = "Пирин";
        $node->type_id = 3;
        $node->save();


        $node = new Node;
        $node->lat = "41.673886";
        $node->long = "23.428085";
        $node->name = "Беговица";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.669802";
        $node->long = "23.394216";
        $node->name = "Яне Сандански";
        $node->type_id = 3;
        $node->save();


        $node = new Node;
        $node->lat = "41.672028";
        $node->long = "23.487852";
        $node->name = "Яловарника";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.672357";
        $node->long = "23.507271";
        $node->name = "Каменица";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.688415";
        $node->long = "23.419411";
        $node->name = "Спано поле";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.700008";
        $node->long = "23.46719";
        $node->name = "Тевно езеро";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.71298";
        $node->long = "23.459048";
        $node->name = "Превалски чукар";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.706859";
        $node->long = "23.4984";
        $node->name = "Дженгал";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.721346";
        $node->long = "23.477137";
        $node->name = "Превалски чукар 2";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.683297";
        $node->long = "23.291231";
        $node->name = "Синаница";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.710174";
        $node->long = "23.357549";
        $node->name = "Спано поле";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.734061";
        $node->long = "23.524412";
        $node->name = "Безбог";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.737743";
        $node->long = "23.422821";
        $node->name = "Бъндерики чукар";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.742824";
        $node->long = "23.467636";
        $node->name = "Демяница";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.753787";
        $node->long = "23.324910";
        $node->name = "Синаница";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.759134";
        $node->long = "23.547160";
        $node->name = "Гоце Делчев";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.756702";
        $node->long = "23.416513";
        $node->name = "Вихрен";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.766628";
        $node->long = "23.424516";
        $node->name = "Бъндерица";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.771500";
        $node->long = "23.396938";
        $node->name = "Вихрен";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.812887";
        $node->long = "23.370789";
        $node->name = "Байови дупки";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.81889";
        $node->long = "23.473719";
        $node->name = "Банско";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.825551";
        $node->long = "23.378034";
        $node->name = "Яворов";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.840510";
        $node->long = "23.390403";
        $node->name = "Яворов";
        $node->type_id = 3;
        $node->save();

        $node = new Node;
        $node->lat = "41.870000";
        $node->long = "23.344931";
        $node->name = "Даутов връх";
        $node->type_id = 3;
        $node->save();
    }
}
