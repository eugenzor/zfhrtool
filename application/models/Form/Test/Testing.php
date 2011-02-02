<?php

/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма прохождение теста
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Test_Testing extends Zend_Form {

    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
        $this->setAction($this->getView()->
                        url(array('controller' => 'test', 'action' => 'testing')));
        $this->setMethod('post');
    }

    /**
     * Добавляет вопросы и ответы на форму
     *
     * @param array $questions Массив вопросов, ключ которого содержит Id вопроса
     * @param array $answers Массив ответов. Ключ которого содержит Id вопроса, а элемент массива - масив содержащий ответы на вопрос Id
     * @return void
     */
    public function addElementsForm(array $questions, array $answers) {

        @ include_once "Text/Highlighter.php";

        $classExistsTH = class_exists('Text_Highlighter', false);

        foreach ($questions as $id => $question) {             
            $elemQuestion = $this->createElement('hidden', 'question_' . $id)
                            ->setDescription($question['tq_sort_index'] . '. ' . $this->_highlighter($question['tq_text'], $classExistsTH));
            $elemQuestion->getDecorator('Description')->setOptions(array('escape'=> false, 'tag'=>'div', 'class'=>'description'));
            $this->addElement($elemQuestion);           

            $i = 1;
            if (isset($answers[$id])) {
                foreach ($answers[$id] as $answer) {
                    $elemAnswer = $this->createElement('checkbox', 'answer_' . $answer['tqa_id'])
                                    ->setLabel($i++ . ') ' . $this->_highlighter($answer['tqa_text'], $classExistsTH));
                    $elemAnswer->getDecorator('Label')->setOption('escape', false);
                    $this->addElement($elemAnswer);
                }
            }
        }

        $submit = $this->createElement('submit', 'send', array('label' => 'Oтправить'));
        $this->addElement($submit);
    }
    
    /**
     * Функция перенесена из функции addElementsForm() из-за возникающей ошибки:
     *     Cannot redeclare highlighter() (previously declared in 
     *     application\models\Form\Test\Testing.php:41) in 
     *     application\models\Form\Test\Testing.php on line 41
     * @param unknown_type $text
     * @param unknown_type $classExistsTH
     */
    private function _highlighter($text, $classExistsTH = false){
            $text = str_replace('[js]','[javascript]',$text);
            $text = str_replace('[/js]','[/javascript]',$text);
            $text = str_replace(array('[code lang="js"]','[code lang=\'js\']'),'[code lang="javascript"]',$text);
            $tags = '(?:php)|(?:sql)|(?:css)|(?:javascript)|(?:html)|(?:sh)';
            if ($classExistsTH){
                $text .= '[code lang=""][/code]';
                // заменяет все теги на [code lang="langName"] и [/code]
                $text = preg_replace('/\[('.$tags.')\](.*?)\[\/(?:'.$tags.')\]/is', "[code lang='$1']$2[/code]", $text);
                //розбтвает строку и записывает в массив
                preg_match_all("/(.*?)\[code lang=['\"](.*?)['\"]\](.*?)\[\/code\]/is", $text, $matches);
                $text = '';
                $count = count($matches[0]);
                for ($i=0; $i<$count; $i++){
                    $text .= nl2br(htmlspecialchars($matches[1][$i]));
                    if($matches[2][$i]){
                        $hl = & Text_Highlighter::factory($matches[2][$i]);
                        $text .= $hl->highlight($matches[3][$i]);
                    }
                }
            } else {
                $text = htmlspecialchars($text);
                $text = preg_replace('/(\[code lang=.*?\])/is', '<pre>', $text);
                $text = str_replace(array('[php]', '[sql]','[css]', '[js]', '[html]', '[bash]'),'<pre>',$text);
                $text = str_replace(array('[/php]', '[/sql]', '[/css]', '[/js]', '[/html]', '[/bash]', '[/code]'),'</pre>',$text);
                //$text = nl2br($text);
                $text = str_replace("\r\n", '<br/>', $text);
            }
            return $text;
        }
        /////////////////////
    

}
