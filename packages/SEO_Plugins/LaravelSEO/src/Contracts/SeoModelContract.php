<?php

namespace SEO_Plugins\LaravelSEO\Contracts;
interface SeoModelContract
{
    public function getSeoUrl(): string;
    public function getSeoTitle(): string;
}
