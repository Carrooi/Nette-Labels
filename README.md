# Carrooi/Labels

[![Build Status](https://img.shields.io/travis/Carrooi/Nette-Labels.svg?style=flat-square)](https://travis-ci.org/Carrooi/Nette-Labels)
[![Donate](https://img.shields.io/badge/donate-PayPal-brightgreen.svg?style=flat-square)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5LPZU9QNQAVTC)

Labels module for Nette framework.

Now any doctrine entity can be turned to "labelable" entity (imagine your gmail mails with labels).

## Installation

```
$ composer require carrooi/labels
$ composer update
```

## Configuration

```neon
extensions:
	labels: Carrooi\Labels\DI\LabelsExtension

labels:

	ownerClass: App\Model\Entities\User
	labelItemClass: App\Model\Entities\LabelItem

	entities:
		App\Model\Entities\Mail: mail
		App\Model\Entities\Article: article
```

**ownerClass**: Most probably your `User` entity
**labelItemClass**: Entity with associations between your labels and labelable entities
**entities**: List of labelable entities with name of column in your `LabelItem` entity

## Owner entity

Owner entity must implement `Carrooi\Labels\Model\Entities\ILabelOwner` interface with only one method:

* `getId()`: returns identifier

```php
namespace App\Model\Entities;

use Carrooi\Labels\Model\Entities\ILabelOwner;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * @ORM\Entity
 * @author David Kudera
 */
class User implements ILabelOwner
{

	// ...

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}
```

## LabelItem entity

Entity which must implement `Carrooi\Labels\Model\Entities\ILabelItem` interface with these methods:

* `getId()`: returns identifier
* `getLabel()`: returns `Carrooi\Labels\Model\Entities\Label` entity
* `setLabel()`: sets `Carrooi\Labels\Model\Entities\Label` entity

Instead of implementing last two methods on your own, you can use prepared `Carrooi\Labels\Model\Entities\TLabelItem` trait.

```php
namespace App\Model\Entities;

use Carrooi\Labels\Model\Entities\ILabelItem;
use Carrooi\Labels\Model\Entities\TLabelItem;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @author David Kudera
 */
class LabelItem implements ILabelItem
{

	use TLabelItem;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="\App\Model\Entities\Mail")
	 * @var \App\Model\Entities\Mail
	 */
	private $mail;

	/**
	 * @ORM\ManyToOne(targetEntity="\App\Model\Entities\Article")
	 * @var \App\Model\Entities\Article
	 */
	private $article;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	// ... other getters and setters for mail and article fields

}
```

## Labelable entity

Last thing is to update your labelable entities, eg. our `Mail` entity.

Each labelable entity must implement `Carrooi\Labels\Model\Entities\ILabelableEntity` interface with these methods:

* `getId()`: returns identifier
* `getLabelItems()`: returns array of `Carrooi\Labels\Model\Entities\ILabelItem` entities
* `addLabelItem()`: adds now `Carrooi\Labels\Model\Entities\ILabelItem` entity to label items collection
* `removeLabelItem()`: removes `Carrooi\Labels\Model\Entities\ILabelItem` entity from label items collection

Again you don't need to implement these methods (except for `getId()`) on your own, but simply use `Carrooi\Labels\Model\Entities\TLabelable` trait.

```php
namespace CarrooiTests\LabelsApp\Model\Entities;

use Carrooi\Labels\Model\Entities\ILabelableEntity;
use Carrooi\Labels\Model\Entities\TLabelable;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @author David Kudera
 */
class Article implements ILabelableEntity
{

	use TLabelable;

	// ... your own fields

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	// ... some other getters and setters

}
```

## Usage

The main idea is that you have more label namespaces in which you (or your users) can create new labels. Of course if you want, you can have just one main namespace and all labels in that namespace. Example of this aproach is eg. GitHub where your labels are shared between issues and pull requests.

These namespaces will be probably "hard-defined" in your project and database.

## Label namespaces facade

For working with namespaces you can use registered service `Carrooi\Labels\Model\Facades\LabelNamespacesFacade`.

**Create new namespace**:

```php
$namespace = $namespaces->addNamespace('articles');
```

**Find namespace**:

```php
$namespace = $namespaces->findNamespace('articles');
```

**Get all namespaces**:

```php
foreach ($namespaces->getNamespaces() as $namespace) {
	// ...
}
```

**Rename namespace**:

```php
$namespaces->renameNamespace($namespace, 'mails');
```

**Remove namespace**:

```php
$namespaces->removeNamespace($namespace);
```

## Labels facade

Now you can let your users create own labels. There is `Carrooi\Labels\Model\Facades\LabelsFacade` service.

**Creating label**:

```php
$label = $labels->addLabel($namespace, $owner, 'Best articles', 'best');
```

Last argument is for "system name" of label and is not required. This can come in handy if you have some default labels and you want to work with them later.

**Get all labels**:

```php
foreach ($labels->getLabels($namespace, $owner) as $label) {
	// ...
}
```

**Find label by system name**:

```php
$label = $labels->findLabel($namespace, $owner, 'best');
```

**Find label by id**:

```php
$label = $labels->findLabelById($id);
```

**Rename label**:

```php
$labels->renameLabel($label, 'Super articles', 'super');
```

You can replace 2nd and 3rd arguments with nulls.

**Remove label**:

```php
$labels->removeLabel($label);
```

## Label items facade

Last facade class is `Carrooi\Labels\Model\Facades\LabelItemsFacade` which is again registered as service in DI container. 

With this service you can manage actual labelable entities in created labels.

**Add labelable item to label**:

```php
$labelItems->addItemToLabel($article, $label);
```

**Find label item entity by labelable item**:

```php
$labelItem = $labelItems->findItem($article, $label);
```

**Is labelable item in label?**:

```php
$labelItems->isItemInLabel($article, $label);
```

**Get all label items in label**:

```php
foreach ($labelItems->getItems($label) as $labelItem) {
	// ...
}
```

**Get labelable items by type**:

```php
foreach ($labelItems->getItemsByType($label, Article::class) as $article) {
	// ...
}
```

**Remove labelable item from label**:

```php
$labelItems->removeItemFromLabel($label, $item);
```

## Changelog

* 1.0.0
	+ Initial version
