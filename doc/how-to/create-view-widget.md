# How to create a view widget

Before we start a view widget is a widget which output is formatted by the view.

For string based template use: `StringView`
For php file based template use: `PhpFileView`
For twig file based template use: `TwigTemplate`

Or read - [how to setup and use widged factory]().

To create a view widget extend the `Sauls\Component\ViewWidget` class.

Creating own view lets define default `viewFile` option.

```php
class MyViewWidget extends ViewWidget
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'viewFile' => 'templates/widget.template.php',
        ]);
    }
}
``` 

The template file
```php
<?php
    echo "My widget says: " . $message;
```

Let's use it now. To create the path we will use a [sauls/helpers](https://github.com/sauls/helpers) library function `create_directory_path`

```php
$phpFileView = new PhpFileView(
    [
        \Sauls\Component\Helper\create_directory_path([__DIR__, 'templates']),
    ]
); 

echo (new MyViewWidget)->widget(['message' => 'Hello world!']);
```

And you should get the output

```text
My widget says: Hello world!
```

---
[Back](/../../Readme.md)


