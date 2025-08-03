##This is my own summary of a tutorial I have been following along with, to keep handy when needed and to help me memorise and understand the uses of symfony and the framework it generates and supports.

#

## SETUP AND BASICS

### common commands:
symfony 

check requirements:
symfony check:req 

new app:
symfony new [name] 

start server:
symfony serve 

index.php is the default file. Located in public folder, with all other files accessible from a webbrowser.

index.php is a front controller and controls all access to all other files in the rest of the framework. Does not need to be changed.

src source folder holds all php code.

controller folder: create new file called HomeController. Define HomeController class with "public method" index()

define a Route to enable the framework to run the index method. The best way to do this is to use php attributes. 

Add a use statement above the class to import it into the current namespace. 

Controller action method needs to return a Response class. Add use statement for Response class and return type declaration for the index() method.

symfony flex modifies composer behaviour to allow for executing additional commands after installation

HTML content to be output goes in separate files called templates, which can be created with twig library

composer require symfony/twig-bundle

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
return $this->render('home/index.html.twig');
#Creates exactly what was created above.

twig allows template inheritance 

extend base.html.twig template. Use twig blocks!
{% extends 'base.html.twig' %}

symfony can create these with the maker bundle:
composer require --dev symfony/maker-bundle

Recipes and aliases make installing these simpler
composer require maker --dev

run this to see available commands
<pre>php bin/console
php bin/console list</pre>
#may not need to write "php " here 

bin/console help make:controller 
#shows options and usage

bin/console make:controller Product 
#To generate controller called "Product". Will ask if no name given. Name gets suffixed with "Controller"

#This has namespaces, AbstractController class, and includes an index method with a Route.

#ProductController has a name specified in the Route, which allows more easy use of link redirecting.
#Call to render is different. passes data to view template.

#A template was generated inside the templates/product folder, "index.html.twig". Look at {{ controller_name }}!

#Add anchor tags to home/index.html.twig like so:
<a href = "{{ path('product_index') }}">Products</a>

#The link now shows up on the home page. 
#Absolute url can be made with:
<a href = "{{ url('product_index') }}">Products</a>

#There are many more options here in the documentation
#Using Route name makes the code more pliable.

#Currently defined Routes can be found with:
bin/console debug:router

#

## DATABASES

composer require symfony/orm-pack

#Docker config for running in isolated container. Not necessary here.
#.env file contains configuration options. Select your database type by un-commenting the right kind of DATABASE_URL. Postgresql is default. I have used sqlite but mysql is also possible.
#configure the file name. %[stuff]% is the project folder, default file name is data.db. Pick something fitting.

bin/console list 
#now shows new doctrine commands
#The file [name].db should now be automatically have been created in the var folder. 
#other dbms's will create the database inside said server.

### Adding tables to database
sqlite has DB browser tool, mysql has phpmyadmin, etc

Symfony can do this directly. 

We create an entity class. Installing doctrine creates an Entity folder inside the src folder. Entity classes are stored here. A repository folder is also made



bin/console dbal:run-sql "SELECT * FROM product"