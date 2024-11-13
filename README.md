## Libraries and technologies used
The entire proeject was built using Sail through docker, to facilitate setting up the environment
(https://github.com/laravel/sail)
Bootstrap for frontend elements in blade
(https://getbootstrap.com/)
Bladewindui for the timeline view
(https://bladewindui.com/)
Livewire to build dynamic components in the order creation page
(https://github.com/livewire/livewire)

## Assumptions and arbitrary choices
- Products, product types and clients have a name
- Products and product types are shared between all clients
- Orders are client-specific
- While I did add a product_type to the order object, I thought of the possibility that one day, orders might not be restricted to a singular product type. With forward compatibility in mind, where possible, I tried to access the product type of order items by way of their relation with the product object, rather than through the order object (for example, $orderItem->product->productType, as opposed to $orderItem->order->ProductType)
- An order item represents an amount of instances of a specific product to be produced as part of an order
- The schedule viewer will show the production schedule for all items from all clients


## Design decisions && thought process
1) I started by creating the database migrations for everything mentioned in the instructions, as well as the eloquent models representing them.
2) I seeded the database with the clients, product types and products from the instructions. [php artisan migrate] and [php artisan db:seed] create and populate the databse with the elements mentioned in the instructions,
   but no orders or order items are created at this step.
3) I build routes, controllers and empty views for the order creation and schedule viewing
4) I built the order creation page, at first without validations or error handling, and only allowing a set number of items to be added to the order.
5) I created a very basic algorithm that simply arranged the orders from nearest to furthest "need by" date, while placing changeover delays where needed, and created a simple view that displayed the order schedule, and information about every order.
6) I went back to the order creation page and added a button to dynamically new items to the order being created. I initially planned to write that part in javascript, but decided to use Livewire, which I was familiar with, to save time.
7) I went back to the algorithm and modified it so that it prioritizes the need by date but also tries to group orders with the same product type to avoid changeover delays when time allows.
   If processing the next order with the earliest need by date would incur a changeover delay, without making it finish late, it tries to process as many orders that dont require a changeover delay as possible.
8) I used a Bladewindui template to build a more visual representation of the timeline, estimating the start time and duration of each order, changeover delay, and even each individual item within an order.
9) I went back and added basic validations to the order creation form. I created a StoreOrderRequest class to specify the validations, as it is in my opinion preferable as a project grows to avoid clutter in the controllers.
10) I created a navbar to navigate bertween the 2 pages of the project

## Next steps if I had more time && What I would do differently in hindsight
1) The next thing I would have done would have been to create some kind of presentation model to send the information needed to the schedule view, to avoid using large arrays and to better encapsulate the data.
2) Given more time, I would further optimize the scheduling algorithm. I have transformed part of it into different functions to avoid clutter, but it remains large and difficult to read due to the large amount of variables.
3) With more time, I would have created simple pages to veiw a list of orders and order items, and pages to edit or delete them. There currently exists and empty /orders that only contais the navbar.
4) I ran into an issue where I opted to use arrays to avoid having dynamic variable names in the request coming from the create order form. this made it more difficult to create validation rules the "order items" part of the form.
   Given more time, I would try to find another solution to send the inputs through the forms or simply use a javascript library like react for the frontend of the application.

## Instructions to run the project on windows

1) Install wsl and docker
2) in the wsl environment, clone the repository
3) In the repository folder, run the following command, without the quotes:
   "docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php83-composer:latest \
   composer install --ignore-platform-reqs"
4) In the repository folder, run the following command, without the quotes:
   "./vendor/bin/sail up"
5) In the repository folder, copy teh contents of the .env.example file into a newly created .env file
This terminal shell is now displaying Sail's information and is unusable without shutting down the docker container
5) In a new terminal, in the repository folder, run the following command, without the quotes:
   "./vendor/bin/sail shell"
6) In the sail shell, run the 3 following commands, without the quotes:
   "php artisan key:generate"
   "php artisan migrate"
   "php artisan db:seed"

The project should now run properly
   
