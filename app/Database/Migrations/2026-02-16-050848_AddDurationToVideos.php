<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDurationToVideos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('videos', [
            'duration' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'description',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('videos', 'duration');
    }
}
