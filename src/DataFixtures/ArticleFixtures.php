<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Comment;
use Faker;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('en_GB');

        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();

            $article->setTitle("Title of the Article n°$i")
                ->setContent("<p>Content of Article n°$i</p>")
                ->setImage("https://place-hold.it/300x150/666")
                ->setCreatedAt(new \DateTime());

            $manager->persist($article);



            // Article Comments

            for ($j = 1; $j <= mt_rand(4, 10); $j++) {

                $comment = new Comment();
                
                $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                $comment->setAuthor($faker->name)
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                    ->setArticle($article);

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
