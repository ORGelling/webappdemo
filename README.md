# Practicing with Symfony

This is a webapp I am using as a learning project for the Symfony tool package.

# Summary

Here follows my own summary of a tutorial I have been following along with, to keep handy when needed and to help me memorise and understand the uses of symfony and the framework it generates and supports.

## SETUP AND BASICS

### common commands:
symfony 

check requirements:
```
symfony check:req 
```

new app:
```
symfony new [name] 
```

start server:
```
symfony serve 
```
### Setup and general structure
index.php is the default file. Located in public folder, with all other files accessible from a webbrowser.

index.php is a front controller and controls all access to all other files in the rest of the framework. Does not need to be changed.

src source folder holds all php code.

controller folder: create new file called HomeController. Define HomeController class with "public method" index()

define a Route to enable the framework to run the index method. The best way to do this is to use php attributes. 

Add a use statement above the class to import it into the current namespace. 

Controller action method needs to return a Response class. Add use statement for Response class and return type declaration for the index() method.

symfony flex modifies composer behaviour to allow for executing additional commands after installation

HTML content to be output goes in separate files called templates, which can be created with twig library
```
composer require symfony/twig-bundle
```
this also creates a templates folder

use directories to match controller name (like home) and files to match method name (index). 

suffix: .html.twig

Load and output template: extend a base class

<pre>use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
(...)
class HomeController extends AbstractController
{
    #[Route('/')]
    public function index: Response
    {
        $contents = $this->renderView('home/index.html.twig');
        return new Response($contents);
    }
}</pre>

> Symfony shorthand: Render method
```
return $this->render('home/index.html.twig');
```
Creates exactly what was created above.

twig allows template inheritance 

extend base.html.twig template. Use twig blocks!
```
{% extends 'base.html.twig' %}
```
symfony can create these with the maker bundle:
```
composer require --dev symfony/maker-bundle
```
Recipes and aliases make installing these simpler
```
composer require maker --dev
```
run this to see available commands
<pre>php bin/console
php bin/console list</pre>
may not need to write "php " here 
```
bin/console help make:controller 
```
shows options and usage
```
bin/console make:controller Product 
```
To generate controller called "Product". Will ask if no name given. Name gets suffixed with "Controller"

This has namespaces, AbstractController class, and includes an index method with a Route.

ProductController has a name specified in the Route, which allows more easy use of link redirecting.
Call to render is different. passes data to view template.

A template was generated inside the templates/product folder, "index.html.twig". Look at {{ controller_name }}!

Add anchor tags to home/index.html.twig like so:
```html
<a href = "{{ path('product_index') }}">Products</a>
```

The link now shows up on the home page. 

Absolute url can be made with:
```html
<a href = "{{ url('product_index') }}">Products</a>
```

There are many more options here in the documentation

Using Route name makes the code more pliable.

Currently defined Routes can be found with:
```
bin/console debug:router
```

## DATABASES

composer require symfony/orm-pack

Docker config for running in isolated container. Not necessary here.

.env file contains configuration options. Select your database type by un-commenting the right kind of DATABASE_URL. Postgresql is default. I have used sqlite but mysql is also possible.

configure the file name. %[stuff]% is the project folder, default file name is data.db. Pick something fitting.
```
bin/console list 
```
now shows new doctrine commands

The file [name].db should now be automatically have been created in the var folder. 

other dbms's will create the database inside said server.

### Adding tables to database
sqlite has DB browser tool, mysql has phpmyadmin, etc

Symfony can do this directly. 

We create an entity class. Installing doctrine creates an Entity folder inside the src folder. Entity classes are stored here. A repository folder is also made
```
bin/console make:entity product
```
Creates a class "product" in entity folder along with a class called "ProductRepository" in the repository folder

Asks for properties of the entity class (fields)
- name (camel case)
- type (? for available)
- length (number of characters it can have)
- null (can it be null, default no)

Add more until done, then input empty line when asks for another "property"

product.php in entity folder now shows our database entries written out as a class. All the properties are private and there are public getter and setter methods. private int id only has a setter. Edit it however you want.

These attributes are used by "doctrine" to generate the code that will write the table in the database.

The code that does this is called a migration. It can can be created using 
```
bin/console make:migration
```
It creates a file in the /migrations folder, which contains the current timestamp. The file contains a class that uses the migration method called "up" which is written in SQL language. This can be edited but isn't necessary

Run this using (asks for confirmation):
```
bin/console doctrine:migrations:migrate
```
The tables have now been added along with doctrine_migration_versions, which helps keep track of migrations, and sqlite_sequence for auto-increment fields. Safe to ignore.

Adding another field can be done manually through the Entity class in Product.php, add the attributes and a getter and setter method.

One can also run the Entity generator again, which will edit the Entity class:
```
bin/console make:entity product
```

### Working with the database
Adding data to the table. Can do this directly with db browser, but symfony also has a tool for this, called fixtures:

With Symfony Flex
```
composer require --dev orm-fixtures
```
Without:
```
composer require --dev doctrine/doctrine-fixtures-bundle
```
This creates a folder called DataFixtures in src, which contains a class called AppFixtures.php

Add a use statement for the product entity class:
```
use App\Entity\Product;
```
Then add code inside the load() method to create new objects of that class, using the setter methods to set values for the attributes.
```
    $product = new Product;
    $product->setName('Product Three');
    $product->setDescription('This is the third product.');
    $product->setSize(300);

    $manager->persist($product);

    $manager->flush();
```
Repeat the first chunk and the persist method to create the objects, and then the flush method to write it all to the database.

Run this using (needs confirmation)
```
bin/console doctrine:fixtures:load #--append
```
This removes all data from every table. The --append flag prevents this.

Check the new data using direct console query:
```
bin/console dbal:run-sql "SELECT * FROM product"
```
> enclose the SQL string in quotes.

### Displaying data in the browser.
in the index() method in ProductController.php we are currently only rendering a template. We need to retrieve some data. In a plain php app this would be done by connecting to db and executing some SQL. Symfony has tools for this though!

When the product Entity class was generated a ProductRepository class was also generated in the src/Repository folder. ProductRepository extends the doctrine ServiceEntityRepository class, which provides functionality.

in ProductController.php we add another use statement.
```
use App\Repository\ProductRepository;
```
then in the index method we create an object of the class, which needs arguments.
```
$repository = new ProductRepository;
```
Use Service Containers to find the argument. Services are objects that help managing a website. We need access to a Repository object. We can use the Service Container to create objects that we need in our app.

In a controller we can ask the container to return an object of the class we want by type-hinting an argument with the service name or object class.

So we add an argument to the index() method with a type declaration of the Repository class:
```
public function index(ProductRepository $repository): Response
```
This is type-hinting, and it causes the Service Container to output the correct ProductRepository instance. Dependencies on other objects are also automatically resolved.

The Repository object is the thing that retrieves data from the table in the db. It has several methods for this. finding individual records based on ID, finding one based on the value of a column, or getting all the records.

We will get all records with this line in ProductController.php:
```
$products = $repository->findAll();
```
Debugging can be done with the dump method:
```
dump($products);
```
We now pass the data directly to the template when it's being rendered.
```
'products' => $repository->findAll(),
```
This means we also need to replace the twig's body block of the code in templates/product/index.html.twig template
```
<h1>Products</h1>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Size</th>
        </tr>
    </thead>
    <tbody>
        {% for product in products %}
        <tr>
            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>{{ product.size }}</td>
        </tr>
        {% endfor %}
    </tbody>
</table>
```
There is no styling here. One can use a classless stylesheet like simple.css to quickly add styles.

Copy the CDN hosted link from the simple.css github page into templates/base.html.twig head section:
```
<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
```
Since it is in the base template it is now applied to every page.
