<?php
namespace App\Tests;
use App\Entity\Beat;
use App\Enum\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Test extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $beat = new Beat();
        $beat->setName('Nom');
        $this->assertSame($beat->getName(),'Nom');
        $beat->setCategory(Category::Rap);
        $this->assertSame($beat->getCategory(),Category::Rap);
    }

    public function testFilterSystemWorks(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface */
        $urlGeneratorInterface = $client->getContainer()->get('router');

        /** @var EntityManagerInterface */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var PostRepository */
        $postRepository = $entityManager->getRepository(Post::class);

        /** @var CategoryRepository */
        $categoryRepository = $entityManager->getRepository(Category::class);

        /** @var Post */
        $post = $postRepository->findOneBy([]);

        /** @var Tag */
        $tag = $post->getTags()[0];

        /** @var Category */
        $category = $categoryRepository->findOneBy([]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGeneratorInterface->generate('post.index')
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $searchs = [
            substr($post->getTitle(), 0, 3),
            substr($tag->getName(), 0, 3)
        ];

        foreach ($searchs as $search) {
            $form = $crawler->filter('form[name=search]')->form([
                'search[q]' => $search,
                'search[category][0]' => 1
            ]);

            $crawler = $client->submit($form);

            $this->assertResponseIsSuccessful();
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            $this->assertRouteSame('post.index');

            $nbPosts = count($crawler->filter('div.card'));
            $posts = $crawler->filter('div.card');
            $count = 0;

            foreach ($posts as $index => $post) {
                $title = $crawler->filter("div.card h5")->getNode($index);
                if (
                    str_contains($title->textContent, $search) ||
                    str_contains($tag->getName(), $search)
                ) {
                    $postCategories = $crawler->filter('div.card div.badges')->getNode($index)->childNodes;

                    for ($i = 1; $i < $postCategories->count(); $i++) {
                        $postCategory = $postCategories->item($i);
                        $name = trim($postCategory->textContent);

                        if ($name === $category->getName()) {
                            $count++;
                        }
                    }
                }
            }

            $this->assertEquals($nbPosts, $count);
        }
    }

}
