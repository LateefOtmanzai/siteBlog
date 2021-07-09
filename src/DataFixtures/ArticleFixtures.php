<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       // On importe la librairie Faker pour les fixtures, cela nous permet de créer des fausses articles, catégories, commentaires plus évolués avec par exemple des faux noms, faux prénoms, date aléatoires etc...
       $faker = \Faker\Factory::create('fr_FR'); 

       for ($cat = 1; $cat <= 3; $cat++) 
       { 
           $category = new Category;

           $category->setTitre($faker->word)
                    ->setDescription($faker->paragraph());

           $manager->persist($category);

           // Création de 4 à 10 articles par catégorie
           for ($art=1; $art <= mt_rand(4,10); $art++) 
           {
               // $faker->paragraph(5) retourne 1 array, setContenu attend une chaine de caractéres en arguments 
               // join (alias implode) permet d'extraire  
               $contenu = '<p>' . join($faker->paragraphs(5), '<p></p>') . '</p>';


               $article = new Article;

               $article->setTitre($faker->sentence())
                       ->setContenu($contenu)
                       ->setImage($faker->imageUrl(600,600))
                       ->setDate($faker->dateTimeBetween('-6 months'))
                       ->setCategory($category);


                $manager->persist($article);

                //Création de 4 à 10 commentaire pour chaque article
                for ($cmt=1; $cmt <= mt_rand(4,10) ; $cmt++) { 

                    // Traitements des paragraphs de commentaire
                    $contenu = '<p>' . join($faker->paragraphs(2), '<p></p>') . '</p>';

                    $comment = new Comment;

                    $now = new DateTime;

                    $interval = $now->diff($article->getdate());// retourne un timestamp (temps en secondes) entre la date de création des articles et aujourd'hui

                    $days = $interval->days;// retourne le nombre de jour entre la date de création des articles et aujourd'hui
                   
                    $minimum = "-$days days"; 
                   
                    $comment->setAuteur($faker->name)
                           ->setCommentaire($contenu)
                           ->setDate($faker->dateTimeBetween($minimum)) // dateTimeBetween(-10 days)
                           ->setArticle($article);

                    $manager->persist($comment);

                }
           }
       }
       $manager->flush();
    }
}
