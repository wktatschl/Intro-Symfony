<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Categories;
use App\Repository\PostRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoriesFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private PostRepository $postRepo)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $categs = [];

        for($i = 0; $i < 10; $i++){
            $category = new Categories();
            $category->setTitle($faker->sentence(2));
            $manager->persist($category);
            $categs[] = $category;
            
        }
        $posts = $this->postRepo->findAll();

        foreach($posts as $post){
            for($i = 0; $i < mt_rand(1, 5); $i++){
                $post->addCategory($categs[mt_rand(0, count($categs) - 1)]
            );
            }
        }



        $tags = [];

        for($i = 0; $i < 5; $i++){
            $tag = new Tag();
            $tag->setTitle($faker->sentence(2));
            $manager->persist($tag);
            $tags[] = $tag;
            
        }
        $posts = $this->postRepo->findAll();

        foreach($posts as $post){
            for($i = 0; $i < mt_rand(1, 5); $i++){
                $post->addTag($tags[mt_rand(0, count($tags) - 1)]
            );
            }
        }



        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PostFixtures::class
        ];
    }
}
