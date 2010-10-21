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
        $tags = '(?:php)|(?:sql)|(?:css)|(?:js)|(?:html)|(?:bash)';
        $classExistsTH = class_exists('Text_Highlighter', false);
        foreach ($questions as $id => $question) {
            $text = $question['tq_text'];
            $text = nl2br(htmlspecialchars($text));
            
            if ($classExistsTH){
                $text .= '[code lang=""][/code]';
                // заменяет все теги на [code lang="langName"] и [/code]
                $text = preg_replace('/\[('.$tags.')\](.*?)\[\/(?:'.$tags.')\]/i', "[code lang='$1']$2[/code]", $text);
                //розбтвает строку и записывает в массив
                preg_match_all("/(.*?)\[code lang=['\"](.*?)['\"]\](.*?)\[\/code\]/", $text, $matches);
                $text = '';
                $count = count($matches[0]);
                for ($i=0; $i<$count; $i++){
                    $text .= $matches[1][$i];
                    if($matches[2][$i]){
                        $hl = &Text_Highlighter::factory($matches[2][$i]);
                        $text .= $hl->highlight($matches[3][$i]);
                    }
                }
            } else {
                $text = preg_replace('/(\[code lang=.*?\])/i', '<pre>', $text);
                $text = str_replace(array('[php]', '[sql]','[css]', '[js]', '[html]', '[bash]'),'<pre>',$text);
                $text = str_replace(array('[/php]', '[/sql]', '[/css]', '[/js]', '[/html]', '[/bash]', '[/code]'),'</pre>',$text);
            }

            $elemQuestion = $this->createElement('hidden', 'question_' . $id)
                            ->setDescription($question['tq_sort_index'] . '. ' . $text);
            $elemQuestion->getDecorator('Description')->setOption('escape', false);
            $this->addElement($elemQuestion);           

            $i = 1;
            if (isset($answers[$id])) {
                foreach ($answers[$id] as $answer) {
                    $elemAnswer = $this->createElement('checkbox', 'answer_' . $answer['tqa_id'])
                                    ->setLabel($i++ . ') ' . nl2br(htmlspecialchars($answer['tqa_text'])));
                    $elemAnswer->getDecorator('Label')->setOption('escape', false);
                    $this->addElement($elemAnswer);
                }
            }
        }

        $submit = $this->createElement('submit', 'send', array('label' => 'Oтправить'));
        $this->addElement($submit);
    }

}
