<?php

/**
 * Copyright (c) 2022 Yun Dou <dixyes@gmail.com>
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
    use LinuxLibraryTrait;
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
        $cppflags = 'CPPFLAGS="-DU_CHARSET_IS_UTF8=1  -DU_USING_ICU_NAMESPACE=1  -DU_STATIC_IMPLEMENTATION=1"';
        $cxxflags = 'CXXFLAGS="-std=c++11"';
        $ldflags = 'LDFLAGS="-static"';
        passthru(
            $this->config->setX . ' && ' .
                "cd {$this->sourceDir} && " .
                "{$this->config->configureEnv} " .
                "{$cppflags} {$cxxflags} {$ldflags} " .
                ' ./runConfigureICU Linux ' .
                '--enable-static ' .
                '--disable-shared ' .
                '--with-data-packaging=static ' .
                '--enable-release=yes ' .
                '--enable-extras=yes ' .
                '--enable-icuio=yes ' .
                '--enable-dyload=no ' .
                '--enable-tools=yes ' .
                '--enable-tests=no ' .
                '--enable-samples=no ' .
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
