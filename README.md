# Boat Website - Laravel Blade Application

A modern, responsive website built with Laravel Blade featuring a home page, contact form, and blog functionality.

## Features

### 🏠 Home Page
- Modern hero section with call-to-action buttons
- Feature highlights with icons and descriptions
- Latest blog posts preview
- Responsive design with dark mode support

### 📞 Contact Us Page
- Contact form with validation
- Contact information display
- FAQ section
- **Future Jira Service Management Integration** - Ready for Jira ticket creation
- Form validation and success messages

### 📝 Blog Page
- Blog post listing with featured images
- Individual blog post pages
- Author information
- Related posts section
- Newsletter signup
- Category exploration

## Installation & Setup

1. **Navigate to the website directory:**
   ```bash
   cd website
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup:**
   ```bash
   php artisan migrate
   ```

5. **Build assets:**
   ```bash
   npm run build
   # or for development:
   npm run dev
   ```

6. **Start the development server:**
   ```bash
   php artisan serve
   ```

## Project Structure

```
website/
├── app/
│   └── Http/Controllers/
│       ├── HomeController.php      # Home page controller
│       ├── ContactController.php  # Contact form handling
│       └── BlogController.php      # Blog functionality
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php       # Master layout
│   │   ├── home.blade.php          # Home page
│   │   ├── contact.blade.php      # Contact page
│   │   └── blog/
│   │       ├── index.blade.php     # Blog listing
│   │       └── show.blade.php      # Individual blog post
│   └── css/
│       └── app.css                 # Custom styles
└── routes/
    └── web.php                     # Application routes
```

## Routes

- `/` - Home page
- `/contact` - Contact form (GET/POST)
- `/blog` - Blog listing
- `/blog/{slug}` - Individual blog post

## Key Features

### Responsive Design
- Mobile-first approach
- Dark mode support
- Tailwind CSS for styling
- Modern UI components

### Contact Form
- Form validation
- Success/error messages
- Prepared for Jira integration
- Contact information display

### Blog System
- Sample blog posts included
- Featured images
- Author information
- Related posts
- Category system

### Navigation
- Consistent navigation across all pages
- Active state indicators
- Mobile-friendly menu

## Future Enhancements

### Jira Service Management Integration
The contact form is prepared for future Jira integration. To implement:

1. Install Jira Service Management SDK
2. Configure API credentials in `.env`
3. Update `ContactController@store` method
4. Add Jira ticket creation logic

Example integration:
```php
// In ContactController@store
$jiraService = new JiraServiceManagement();
$ticket = $jiraService->createTicket([
    'summary' => $request->subject,
    'description' => $request->message,
    'reporter' => $request->email,
    'priority' => 'Medium'
]);
```

## Customization

### Adding New Blog Posts
Edit `BlogController.php` and add new posts to the `$posts` array in the `index()` and `show()` methods.

### Styling
- Modify `resources/css/app.css` for custom styles
- Update Tailwind classes in Blade templates
- Add new components to the layout

### Content
- Update contact information in `layouts/app.blade.php`
- Modify hero sections in individual pages
- Customize FAQ section in contact page

## Technologies Used

- **Laravel 11** - PHP framework
- **Blade** - Templating engine
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Asset bundler
- **Inter Font** - Typography

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).