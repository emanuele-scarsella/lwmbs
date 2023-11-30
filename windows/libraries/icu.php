<?php

/**
 * Copyright (c) 2022 Emanuele Scarsella <emascars@gmail.com>
 *
 * lwmbs is licensed under Mulan PSL v2. You can use this
 * software according to the terms and conditions of the
 * Mulan PSL v2. You may obtain a copy of Mulan PSL v2 at:
 *
 * http://license.coscl.org.cn/MulanPSL2
 *
 * THIS SOFTWARE IS PROVIDED ON AN "AS IS" BASIS,
 * WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO NON-INFRINGEMENT,
 * MERCHANTABILITY OR FIT FOR A PARTICULAR PURPOSE.
 *
 * See the Mulan PSL v2 for more details.
 */

declare(strict_types=1);

class Libicu extends Library
{
    use WindowsLibraryTrait;
    protected string $name = 'icu';
    protected array $depNames = [];
    protected array $staticLibs = [
        "libicui18n.a",
        "libicuio.a",
        "libicuuc.a",
        "libicudata.a"
    ];
    protected array $headers = [];
    protected array $pkgconfs = [];

    protected function build(): void
    {
        Log::i("building {$this->name}");
        $ret = 0;
        passthru(
            "cd {$this->sourceDir}/source && " .
                // "{$this->config->configureEnv} " .
                'bash ./runConfigureICU Cygwin ' .
                '--enable-static ' .
                '--disable-shared ' .
                '--prefix= && ' .
                "make clean && " .
                "make -j{$this->config->concurrency} && " .
                'make install DESTDIR=' . realpath('.'),
            $ret
        );
        if ($ret !== 0) {
            throw new Exception("failed to build {$this->name}");
        }
    }
}
