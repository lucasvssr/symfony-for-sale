<?php

namespace App\Story;

use App\Factory\CategoryFactory;
use Zenstruck\Foundry\Story;

final class CategoryStory extends Story
{
    public function build(): void
    {
        $category = [];
        foreach (file(__DIR__.'/../DataFixtures/data/category.txt') as $value) {
            $category[] = trim($value);
        }

        $this->addState('category_without_advertisement', CategoryFactory::createOne(['name' => array_shift($category)]));

        foreach ($category as $value) {
            $this->addToPool('categories', CategoryFactory::createOne(['name' => $value]));
        }
    }
}
