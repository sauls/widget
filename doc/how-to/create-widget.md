# How to create a widget

Create your widget class and extend the `Sauls\Component\Widget` class. You will have to implement two methods:
* `configureOptions` - add options your widget will have. If your widget does not have options, leave this method blank.
* `render` - here you need to return a widget output as string. If you want to use a template for widget souptu see [How to create a view widget](create-view-widget.md).

```php
class MyWidget extends Widget
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
    }

    public function render(): string
    {
        return date('Y-m-d');
    }
}
```

And use your widget

```php
echo (new MyWidget())->widget();

// You should get depending on current date '2018-02-13'
```

We can slightly improve this widget by allowing to change the date format it outputs

```php
class MyWidget extends Widget
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(['format'])
            ->addAllowedTypes('format', ['string'])
            ->setDefaults(['format' => 'Y-m-d']);
    }

    public function render(): string
    {
        return date($this->getOption('format');
    }
}
``
And let's use the widget now

```php
echo (new MyWidget())->widget();
// You should get same result depending on current date '2018-02-13'

echo (new MyWidget())->widget(['format' => 'Y/m/d']);
// Now the result should be 2018/02/13
```

---
[Back](/../../Readme.md)
