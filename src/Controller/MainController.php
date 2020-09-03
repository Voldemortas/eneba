<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
        $crawl = function ($index = 0, $currentArr = []) use ($variants, $order, &$crawl) {
            /*
              !array -> mandatory
              array -> crawl
            */
        };
        if(count($order) === 0 || $order[0] === ''){
          //return 'a';
          $result = [];
          $index = mt_rand(0, count($variants) -1);
          $result[] = $index;
          $current = $variants[$index];
          while(true){
            if(is_string($current)){
              break;
            }
            if(count($current->names) === 0){
              break;
            }
            $name = mt_rand(0, count($current->names) - 1);
            $result[] = $name;
            if(count($current->children) === 0){
              break;  
            }else{
              $children = mt_rand(0, count($current->children) - 1);
              $result[] = $children;
              $current = $current->children[$children];
            }
          }          
          $order = $result;
        }
        $str = implode('', $order); 
        return $variants[array_shift($order)]->toString($order).'!<br /><a href="/'.$str.'">Permanent link to this page</a>';
    }

    /**
     * @Route("/{id}", name="main_page")
     */
    public function list(string $id = '')
    {
        return new Response($this->makeWord(isset($id)?str_split($id):[]));
    }
}

class PhraseTree
{
    public function __construct(array $names = [], array $children = null)
    {
        $this->names = $names;
        $this->children = $children;
    }
    public function toString($order){
      $current = $this;
      $result = $current->names[$order[0]];
      for($i = 1; $i < count($order); $i += 2){
        if(!array_key_exists($order[$i], $current->children)){
          throw new NotFoundHttpException('The ID was not found');
        }
        $current = $current->children[$order[$i]];
        if($i < count($order) - 1){          
          $result .= $current->names[$order[$i+1]];
        }else{
          $result .= $current;
        }
      }
      if(isset($current->children)){ 
        throw new NotFoundHttpException('The ID was not found');
      }
      return $result;
    }
}