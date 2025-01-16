<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $categories = $this->generateCategories($this->faker, 10);
        foreach ($categories as $category) {
            $manager->persist($category);

            $numberOfArticles = rand(2, 5);
            $articles = $this->generateArticles($this->faker, $numberOfArticles, $category);
            foreach ($articles as $article)
                $manager->persist($article);
        }

        $uncategorizedArticles = $this->generateArticles($this->faker, 10, null);
        foreach ($uncategorizedArticles as $article)
            $manager->persist($article);

        $manager->flush();
    }

    private function generateCategories($faker, int $numberOfCategories): array
    {
        $categories = [];
        for ($i = 0; $i < $numberOfCategories; $i++) {
            $category = new ArticleCategory();
            $category->setName($faker->word());
            $category->setDescription($faker->optional()->sentence());
            $category->setEnabled($faker->boolean(80));
            $categories[] = $category;
        }

        return $categories;
    }

    private function generateArticles($faker, int $numberOfArticles, $category): array
    {
        $articles = [];
        for ($i = 0; $i < $numberOfArticles; $i++) {
            $article = new Article();
            $article->setName($faker->word());
            $article->setDescription($faker->optional()->sentence());
            $article->setIngredients($faker->optional()->text());
            $article->setPrice(number_format($faker->randomFloat(2, 5, 50), 2));
            $article->setAvailable($faker->boolean(80));
            $article->setEnabled($faker->boolean(90));
            $article->setAllergens($faker->optional()->word());
            $article->setCategory($category);
            $articles[] = $article;
        }

        return $articles;
    }
}
