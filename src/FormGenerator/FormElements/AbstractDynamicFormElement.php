<?php

namespace CosmoCode\Formserver\FormGenerator\FormElements;


/**
 * Base class for every InputFormElement
 */
abstract class AbstractDynamicFormElement extends AbstractFormElement
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Returns true if this form element has a value
     *
     * @return bool
     */
    public function hasValue()
    {
        return ! empty($this->value);
    }

    /**
     * Returns raw value (array in case of multiselect elements)
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Element's value as string
     *
     * @return string
     */
    public function getValueString()
    {
        return is_string($this->value) ? $this->value : implode(', ', $this->value);
    }

    /**
     * Sets the value and triggers validation
     *
     * @param string|null $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the field's configured validation rules
     *
     * @return array
     */
    public function getValidationRules()
    {
        return $this->getConfigValue('validation') ?? [];
    }

    /**
     * Return true if this form element is required
     *
     * @return bool
     */
    public function isRequired()
    {
        return isset($this->getValidationRules()['required'])
            && $this->getValidationRules()['required'];
    }

    /**
     * Attaches an error to the form element
     *
     * @param string $error
     * @return void
     */
    public function addError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Returns the field's errors
     *
     * @return string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Return true if the value of this form element is marked as valid
     *
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errors);
    }
}
