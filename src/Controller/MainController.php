<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class MainController extends AbstractController
{
    public function makeWord(array $order = [])
    {
        $variants = [
            new PhraseTree(
                ['kad tave '],
                [
                    new PhraseTree(
                        ['kur ', ''], [
                            'galas', 'perkūnas', 'žaibas', 'rupūžė', 'rūta žalioji'
                        ]
                    )
                ]
            ),
            new PhraseTree(['po '], ['velnių', 'galais', 'perkūnais']),
            new PhraseTree(
                ['eik '],
                [
                    new PhraseTree(
                        ['tu '], ['namas', 'šikt']
                    ),
                    'į peklą', 'šieno ravėt'
                ]
            ),
            new PhraseTree(['o '],
                [
                    new PhraseTree(['tu '],
                        [
                            'rupūže', 'bijūnėli', 'rūta žalioji', 'žalioji rūtytėle'
                        ]
                    ),
                    new PhraseTree(['Viešpatie '], ['Dieve', 'su Viešpačiukais'])
                ]
            ),
            new PhraseTree(['Va'], ['jetau', 'jėzau', 'jetus', 'jėzus']),
            new PhraseTree(['Jetus', 'Jėzus'], [' Marija'])
        ];
        if (count($order) === 0 || $order[0] === '') {
            $result = [];
            $index = mt_rand(0, count($variants) - 1);
            $result[] = $index;
            $current = $variants[$index];
            while (true) {
                if (is_string($current)) {
                    break;
                }
                if (count($current->names) === 0) {
                    break;
                }
                $name = mt_rand(0, count($current->names) - 1);
                $result[] = $name;
                if (count($current->children) === 0) {
                    break;
                } else {
                    $children = mt_rand(0, count($current->children) - 1);
                    $result[] = $children;
                    $current = $current->children[$children];
                }
            }
            $order = $result;
        }
        $str = implode('', $order);
        return $variants[array_shift($order)]->toString($order) . '!<br /><a href="/' . $str . '">Permanent link to this page</a>';
    }

    /**
     * @Route("/{id}", name="main_page")
     * @param string $id
     * @return Response
     */
    public function list(string $id = '')
    {
        return new Response($this->makeWord(isset($id) ? str_split($id) : []));
    }
}

