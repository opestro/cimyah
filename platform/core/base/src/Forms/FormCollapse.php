<?php

namespace Botble\Base\Forms;

use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Closure;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Tappable;
use LogicException;

class FormCollapse
{
    use Conditionable;
    use Tappable;

    protected string $targetFieldName;

    protected string $targetFieldType = OnOffCheckboxField::class;

    protected array $targetFieldOption = [];

    protected bool $targetFieldModify = false;

    protected string $targetFieldValue = '1';

    protected array $fieldsetCallback = [];

    protected ?Closure $beforeRegisterFieldset = null;

    protected ?Closure $afterRegisterFieldset = null;

    protected bool $isOpened = false;

    public function __construct(protected string $id)
    {
    }

    public static function make(string $id): static
    {
        return app(static::class, compact('id'));
    }

    public function getId(): string
    {
        return sprintf('collapsible-form-%s', $this->id);
    }

    public function targetField(
        string $fieldName,
        string $fieldType = OnOffCheckboxField::class,
        array|FormFieldOptions $fieldOptions = [],
        bool $fieldModify = false
    ): static {
        $this->targetFieldName = $fieldName;
        $this->targetFieldType = $fieldType;
        $this->targetFieldOption = is_array($fieldOptions)
            ? $fieldOptions
            : $fieldOptions->toArray();
        $this->targetFieldOption['attr']['data-bb-toggle'] = 'collapse';
        $this->targetFieldOption['attr']['data-bb-target'] = '.' . $this->getId();

        $this->targetFieldModify = $fieldModify;

        return $this;
    }

    public function fieldset(
        Closure $callback,
        ?string $targetFieldName = null,
        ?string $targetFieldValue = null,
        ?bool $isOpened = null
    ): static {
        $this->fieldsetCallback[] = [
            'callback' => $callback,
            'targetFieldName' => $targetFieldName,
            'targetFieldValue' => $targetFieldValue,
            'isOpened' => $isOpened,
        ];

        return $this;
    }

    public function targetValue(string|bool $targetValue): static
    {
        $this->targetFieldValue = $targetValue;

        return $this;
    }

    public function isOpened(bool $isOpened = true): static
    {
        $this->isOpened = $isOpened;

        return $this;
    }

    public function beforeRegisterField(Closure $callback): static
    {
        $this->beforeRegisterFieldset = $callback;

        return $this;
    }

    public function afterRegisterField(Closure $callback): static
    {
        $this->afterRegisterFieldset = $callback;

        return $this;
    }

    public function build(FormAbstract $form): void
    {
        if (! isset($this->targetFieldName)) {
            throw new LogicException('Collapsible form requires fieldset and target field name.');
        }

        $form->add($this->targetFieldName, $this->targetFieldType, $this->targetFieldOption, $this->targetFieldModify);

        if ($this->beforeRegisterFieldset) {
            call_user_func($this->beforeRegisterFieldset, $form);
        }

        foreach ($this->fieldsetCallback as $key => $fieldsetCallback) {
            $this->buildFieldset($form, $fieldsetCallback, $key);
        }

        if ($this->afterRegisterFieldset) {
            call_user_func($this->afterRegisterFieldset, $form);
        }
    }

    protected function buildFieldset(FormAbstract $form, array $fieldset, int $key): void
    {
        $postfix = ($fieldset['targetFieldName'] ? '_' . $fieldset['targetFieldName'] : '');
        $postfix = $postfix . '_' . hash('sha1', $key);

        $form->add(
            sprintf('open_fieldset_%s', $this->id . $postfix),
            HtmlField::class,
            HtmlFieldOption::make()
                ->content(sprintf(
                    '<fieldset class="%s form-fieldset" data-bb-value="%s" style="display: %s"/>',
                    $this->getId(),
                    $fieldset['targetFieldValue'] !== null ? $fieldset['targetFieldValue'] : $this->targetFieldValue,
                    ($fieldset['isOpened'] !== null ? $fieldset['isOpened'] : $this->isOpened) ? 'block' : 'none',
                ))
                ->toArray()
        );

        if (isset($fieldset['callback']) && is_callable($fieldset['callback'])) {
            call_user_func($fieldset['callback'], $form);
        }

        $form->add(
            sprintf('close_fieldset_%s', $this->id . $postfix),
            HtmlField::class,
            HtmlFieldOption::make()->content('</fieldset>')->toArray()
        );
    }
}
