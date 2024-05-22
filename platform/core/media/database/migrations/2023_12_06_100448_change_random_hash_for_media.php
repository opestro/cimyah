<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        setting()->forceSet('media_random_hash', md5((string) time()))->save();
    }
};
