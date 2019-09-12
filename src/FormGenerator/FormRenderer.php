<?php

namespace CosmoCode\Formserver\FormGenerator;


use CosmoCode\Formserver\Exceptions\TwigException;
use CosmoCode\Formserver\FormGenerator\FormElements\FieldsetFormElement;
use CosmoCode\Formserver\FormGenerator\FormElements\AbstractFormElement;
use Twig\TemplateWrapper;

/**
 * Renders twig blocks
 */
class FormRenderer
{
    /**
     * @var TemplateWrapper
     */
    protected $twig;

    /**
     * @var Form
     */
    protected $form;

    /**
     * Pass the form which later should be rendered
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $twigLoader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../view/');
        $twigEnvironment = new \Twig\Environment($twigLoader);
        try {
            $this->twig = $twigEnvironment->load('layout.twig');
        } catch (\Twig\Error\Error $e) {
            throw new TwigException(
                "Could not load twig layout file:" . $e->getMessage()
            );
        }
    }

    /**
     * Renders the complete Form
     *
     * @return string
     * @throws TwigException
     */
    public function render()
    {
        $formHtml = '';
        $title = $this->form->getMeta('title');
        foreach ($this->form->getFormElements() as $formElement) {
            if ($formElement instanceof FieldsetFormElement) {
                $formHtml .= $this->renderFieldsetFormElement($formElement);
            } else {
                $formHtml .= $this->renderFormElement($formElement);
            }
        }

        return $this->renderBlock(
            'form',
            [
                'formHtml' => $formHtml,
                'title' => $title
            ]
        );
    }

    /**
     * Renders the view of a FormElement
     *
     * @param FieldsetFormElement $fieldsetFormElement
     * @return string
     * @throws TwigException
     */
    protected function renderFieldsetFormElement(
        FieldsetFormElement $fieldsetFormElement
    ) {
        foreach ($fieldsetFormElement->getChildren() as $childFormElement) {
            $fieldsetFormElement->addRenderedChildView(
                $this->renderFormElement($childFormElement)
            );
        }

        return $this->renderFormElement($fieldsetFormElement);
    }

    /**
     * Helper function to render a FormElement
     *
     * @param AbstractFormElement $formElement
     * @return string
     */
    protected function renderFormElement(AbstractFormElement $formElement)
    {
        return $this->renderBlock(
            $formElement->getType(),
            $formElement->getViewVariables()
        );
    }

    /**
     * Helper function to render a twig block
     *
     * @param string $block
     * @param array $variables
     * @return string
     * @throws TwigException
     */
    protected function renderBlock(string $block, array $variables)
    {
        if (! $this->twig->hasBlock($block)) {
            throw new TwigException(
                "Template block for form element type '$block' not found."
            );
        }

        // Global variables
        $variables['form_id'] = $this->form->getId();

        try {
            return $this->twig->renderBlock($block, $variables);
        } catch (\Throwable $e) {
            throw new TwigException(
                "Could not render block '$block': " . $e->getMessage()
            );
        }
    }
}
