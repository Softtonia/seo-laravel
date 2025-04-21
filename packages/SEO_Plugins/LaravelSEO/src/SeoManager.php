<?php

namespace SEO_Plugins\LaravelSEO;

class SeoManager
{
    protected $title;
    protected $description;
    protected $image;
    protected $canonical;

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    public function setDescription($desc)
    {
        $this->description = $desc;
        return $this;
    }
    public function setImage($img)
    {
        $this->image = $img;
        return $this;
    }
    public function setCanonical($url)
    {
        $this->canonical = $url;
        return $this;
    }

    // Add the generateTitle method here
    public function generateTitle($title)
    {
        $this->setTitle($title);
        return $this->title;
    }

    public function render()
    {
        return view('seo::tags', [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'canonical' => $this->canonical,
        ]);
    }
}
