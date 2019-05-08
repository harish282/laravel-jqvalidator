<?php

namespace Sohamgreens\Jqvalidator;

use Illuminate\Support\HtmlString;

class FormBuilder extends \Collective\Html\FormBuilder {

    public $rules = array();
    public $errors = null;
    public $errorWrap = '<span class="help-block has-error">{error}</span>';

    public function open(array $options = array(), $rules = array(), $errors = null) {
        $this->rules = $rules;
        $this->errors = $errors;
        return parent::open($options);
    }

    public function model($model, array $options = array(), $rules = array(), $errors = null) {
        $this->model = $model;
        return $this->open($options, $rules, $errors);
    }

    public function setErrorWrap($wrap) {
        $this->errorWrap = $wrap;
    }

    public function input($type, $name, $value = null, $attributes = array()) {
        $attributes = static::merge_rules($name, $attributes);
        $inputhtml = parent::input($type, $name, $value, $attributes)->toHtml();

        if ($this->errors && $this->errors->has($name)) {
            $inputhtml .= str_replace('{error}', $this->errors->first($name), $this->errorWrap);
        }
        return $this->toHtmlString($inputhtml);
    }

    public function textarea($name, $value = '', $attributes = array()) {
        $attributes = static::merge_rules($name, $attributes);
        return parent::textarea($name, $value, $attributes);
    }

    public function select($name, $options = [], $selected = null, array $attributes = [], array $sattributes = [], array $gattributes = []) {
        $attributes = static::merge_rules($name, $attributes);
        return parent::select($name, $options, $selected, $attributes, $sattributes, $gattributes);
    }

    protected function merge_rules($name, $attributes) {
        if ($this->rules && array_key_exists($name, $this->rules)) {
            $attributes = array_merge($attributes, array(
                'data-validations' => $this->rules[$name] ?: '',
            ));
        }

        if ($this->errors && $this->errors->has($name)) {
            if (isset($attributes['class']))
                $attributes['class'] .= ' has-error';
            else
                $attributes['class'] = 'has-error';
        }

        return $attributes;
    }

}
