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
        foreach ($questions as $id => $question) {
            $elemQuestion = $this->createElement('textarea', 'question_' . $id)
                            ->setValue($question['tq_sort_index'] . '. ' . $question['tq_text'])
                            ->setAttrib('rows', 2)
                            ->setAttrib('cols', 100)
                            ->setAttrib('readonly', 'false');
            $this->addElement($elemQuestion);

            $i = 1;
            if (isset($answers[$id])) {
                foreach ($answers[$id] as $answer) {
                    $elemAnswer = $this->createElement('checkbox', 'answer_' . $answer['tqa_id'])
                                    ->setLabel($i++ . ') ' . $answer['tqa_text']);
                    $this->addElement($elemAnswer);
                }
            }
        }

        $submit = $this->createElement('submit', 'send', array('label' => 'Oтправить'));
        $this->addElement($submit);
    }

}
