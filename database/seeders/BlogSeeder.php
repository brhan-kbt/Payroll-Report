<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if not exists
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Editor user
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
            'role' => 'editor',
        ]);

        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Employee::create([
                'name' => $faker->name,
                'employee_id' => strtoupper($faker->unique()->bothify('EMP###')),
                'department' => $faker->randomElement(['HR', 'Finance', 'IT', 'Marketing', 'Sales']),
                'position' => $faker->jobTitle,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'date_of_joining' => $faker->date('Y-m-d', 'now'),
                'date_of_birth' => $faker->date('Y-m-d', '-22 years'),
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'address' => $faker->address,
                'is_active' => $faker->boolean(90), // 90% chance active
            ]);
        }

        // Create categories
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'image' => null,
                'parent_id' => null,
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'image' => null,
                'parent_id' => null,
            ],
            [
                'name' => 'Mobile Development',
                'slug' => 'mobile-development',
                'image' => null,
                'parent_id' => null,
            ],
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'image' => null,
                'parent_id' => null,
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'image' => null,
                'parent_id' => null,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            $createdCategories[] = $category;
        }

        // Create posts
        $posts = [
            [
                'title' => 'Getting Started with Laravel 10',
                'subtitle' => 'A comprehensive guide to building modern web applications with Laravel',
                'body' => 'Laravel is a powerful PHP framework that makes web development enjoyable and creative. In this comprehensive guide, we\'ll explore the key features and concepts that make Laravel one of the most popular frameworks for web development.

## What is Laravel?

Laravel is a web application framework with expressive, elegant syntax. It attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- Authentication
- Routing
- Sessions
- Caching
- Database operations

## Key Features

### Eloquent ORM
Laravel\'s Eloquent ORM provides a beautiful, simple ActiveRecord implementation for working with your database. Each database table has a corresponding "Model" that is used to interact with that table.

### Blade Templating
Blade is the simple, yet powerful templating engine provided with Laravel. Unlike other popular PHP templating engines, Blade does not restrict you from using plain PHP code in your views.

### Artisan Console
Artisan is the command-line interface included with Laravel. It provides a number of helpful commands that can assist you while you build your application.

## Getting Started

To get started with Laravel, you\'ll need to install it using Composer:

```bash
composer create-project laravel/laravel my-blog
cd my-blog
php artisan serve
```

This will create a new Laravel project and start the development server.',
                'category_id' => $createdCategories[3]->id, // Laravel category
                'user_id' => $user->id,
                'views' => 150,
                'likes' => 25,
            ],
            [
                'title' => 'Modern JavaScript ES6+ Features',
                'subtitle' => 'Explore the latest JavaScript features that every developer should know',
                'body' => 'JavaScript has evolved significantly over the years, and ES6+ (ES2015 and later) introduced many powerful features that make JavaScript development more efficient and enjoyable.

## Arrow Functions

Arrow functions provide a more concise syntax for writing function expressions:

```javascript
// Traditional function
function add(a, b) {
    return a + b;
}

// Arrow function
const add = (a, b) => a + b;
```

## Destructuring Assignment

Destructuring allows you to unpack values from arrays or properties from objects into distinct variables:

```javascript
// Array destructuring
const [first, second, third] = [1, 2, 3];

// Object destructuring
const { name, age } = { name: "John", age: 30 };
```

## Template Literals

Template literals provide an easy way to interpolate variables and expressions into strings:

```javascript
const name = "World";
const greeting = `Hello, ${name}!`;
```

## Promises and Async/Await

Modern JavaScript provides powerful tools for handling asynchronous operations:

```javascript
// Using Promises
fetch("/api/data")
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error(error));

// Using async/await
async function fetchData() {
    try {
        const response = await fetch("/api/data");
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error(error);
    }
}
```

These features make JavaScript more powerful and easier to work with in modern web development.',
                'category_id' => $createdCategories[4]->id, // JavaScript category
                'user_id' => $user->id,
                'views' => 200,
                'likes' => 35,
            ],
            [
                'title' => 'Building Responsive Web Applications',
                'subtitle' => 'Best practices for creating mobile-first, responsive designs',
                'body' => 'Responsive web design is crucial in today\'s multi-device world. With users accessing websites from various devices with different screen sizes, creating responsive applications is no longer optional—it\'s essential.

## Mobile-First Approach

Start designing for mobile devices first, then progressively enhance for larger screens:

```css
/* Mobile-first CSS */
.container {
    width: 100%;
    padding: 1rem;
}

/* Tablet and up */
@media (min-width: 768px) {
    .container {
        max-width: 750px;
        margin: 0 auto;
    }
}

/* Desktop and up */
@media (min-width: 1024px) {
    .container {
        max-width: 1200px;
    }
}
```

## Flexible Grid Systems

Use CSS Grid and Flexbox to create flexible layouts:

```css
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.flex-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
```

## Responsive Images

Ensure images scale properly across devices:

```html
<img src="image.jpg"
     srcset="image-small.jpg 480w, image-medium.jpg 768w, image-large.jpg 1200w"
     sizes="(max-width: 480px) 100vw, (max-width: 768px) 50vw, 33vw"
     alt="Responsive image">
```

## Performance Considerations

- Optimize images for different screen sizes
- Use lazy loading for images below the fold
- Minimize HTTP requests
- Use CSS instead of JavaScript for animations when possible

Responsive design is about creating the best possible user experience across all devices.',
                'category_id' => $createdCategories[1]->id, // Web Development category
                'user_id' => $user->id,
                'views' => 180,
                'likes' => 28,
            ],
            [
                'title' => 'Mobile App Development Trends 2024',
                'subtitle' => 'The latest trends and technologies shaping mobile app development',
                'body' => 'Mobile app development continues to evolve rapidly, with new technologies and trends emerging each year. Here are the key trends shaping mobile development in 2024.

## Cross-Platform Development

Frameworks like React Native, Flutter, and Xamarin continue to gain popularity:

- **React Native**: Facebook\'s framework for building native apps with JavaScript
- **Flutter**: Google\'s UI toolkit for building natively compiled applications
- **Xamarin**: Microsoft\'s framework for building cross-platform apps

## Progressive Web Apps (PWAs)

PWAs combine the best of web and mobile apps:

- Offline functionality
- Push notifications
- App-like experience
- Easy installation

## AI and Machine Learning Integration

Mobile apps are increasingly incorporating AI features:

- Image recognition
- Natural language processing
- Predictive analytics
- Personalized recommendations

## 5G and Edge Computing

The rollout of 5G networks enables:

- Faster data transfer
- Lower latency
- Real-time applications
- Enhanced AR/VR experiences

## Security and Privacy

With increasing concerns about data privacy:

- End-to-end encryption
- Biometric authentication
- Privacy-focused design
- Compliance with regulations like GDPR

These trends are shaping the future of mobile app development and creating new opportunities for developers.',
                'category_id' => $createdCategories[2]->id, // Mobile Development category
                'user_id' => $user->id,
                'views' => 120,
                'likes' => 18,
            ],
            [
                'title' => 'The Future of Web Development',
                'subtitle' => 'Exploring emerging technologies and their impact on web development',
                'body' => 'Web development is constantly evolving, with new technologies and paradigms emerging regularly. Let\'s explore what the future holds for web development.

## WebAssembly (WASM)

WebAssembly allows you to run high-performance code in the browser:

- Near-native performance
- Support for multiple programming languages
- Ideal for computationally intensive tasks
- Growing ecosystem and browser support

## Serverless Architecture

Serverless computing is changing how we build and deploy applications:

- Automatic scaling
- Pay-per-execution pricing
- Reduced operational overhead
- Focus on business logic

## Edge Computing

Moving computation closer to users:

- Reduced latency
- Better performance
- Distributed processing
- CDN integration

## Web Components

Standardized way to create reusable UI components:

- Framework-agnostic
- Native browser support
- Encapsulated styling and behavior
- Interoperability

## AI-Powered Development

Artificial intelligence is becoming a development tool:

- Code generation and completion
- Automated testing
- Performance optimization
- Bug detection and fixing

## Sustainability in Web Development

Green web development practices:

- Optimized performance
- Reduced energy consumption
- Sustainable hosting
- Efficient algorithms

The future of web development is exciting, with these technologies opening up new possibilities for creating better, faster, and more sustainable web applications.',
                'category_id' => $createdCategories[0]->id, // Technology category
                'user_id' => $user->id,
                'views' => 250,
                'likes' => 42,
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }

        $this->command->info('Blog data seeded successfully!');
        $this->command->info('Created ' . count($createdCategories) . ' categories and ' . count($posts) . ' posts.');
        $this->command->info('Test user: admin@blog.com / password');
    }
}
