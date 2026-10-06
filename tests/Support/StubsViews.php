<?php

namespace Tests\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

/**
 * Menimpa view halaman HTTP dengan stub kosong agar test memeriksa kontrak data
 * controller dan tetap lulus selagi tampilan masih dikerjakan terpisah.
 */
trait StubsViews
{
    /**
     * @param  array<int, string>  $names  Nama view bertitik, contoh `auth.login`.
     */
    protected function stubViews(array $names): void
    {
        $directory = sys_get_temp_dir().'/maukuliah-view-stubs-'.getmypid();

        foreach ($names as $name) {
            $path = $directory.'/'.str_replace('.', '/', $name).'.blade.php';

            File::ensureDirectoryExists(dirname($path));
            File::put($path, 'stub');
        }

        View::getFinder()->prependLocation($directory);
    }
}
