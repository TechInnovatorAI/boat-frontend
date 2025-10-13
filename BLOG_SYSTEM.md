# Multi-Language Blog System

This document describes the multi-language blog system implemented for the Boat Website project.

## Overview

The blog system has been completely restructured to support multiple languages with markdown-based content management. Content is stored in markdown files organized by language, making it easy to manage and maintain.

## Directory Structure

```
resources/
└── content/
    └── blog/
        ├── en/          # English content
        ├── fr/          # French content
        ├── de/          # German content
        └── it/          # Italian content
```

## Content Format

Blog posts are stored as markdown files with YAML front matter for metadata:

```markdown
---
title: "Post Title"
slug: "post-slug"
excerpt: "Brief description of the post"
author: "Author Name"
published_at: "2025-09-20"
featured_image: "https://example.com/image.jpg"
tags: ["tag1", "tag2", "tag3"]
---

# Post Content

Your markdown content goes here...
```

### Front Matter Fields

- **title**: The post title
- **slug**: URL-friendly identifier (auto-generated if not provided)
- **excerpt**: Brief description (auto-generated if not provided)
- **author**: Author name
- **published_at**: Publication date (YYYY-MM-DD format)
- **featured_image**: URL to the featured image
- **tags**: Array of tags for categorization

### File Naming Convention

Files should be named using the format: `YYYY-MM-DD-title-slug.md`

Example: `2025-09-20-sailing-in-switzerland.md`

## Supported Languages

- **English (en)**: Default language
- **French (fr)**: Français
- **German (de)**: Deutsch
- **Italian (it)**: Italiano

## Key Components

### 1. BlogService (`app/Services/BlogService.php`)

The core service that handles:
- Reading and parsing markdown files
- Converting markdown to HTML
- Managing multi-language content
- Generating excerpts and slugs
- Finding related posts

#### Key Methods

- `getPosts($language)`: Get all posts for a language
- `getPost($slug, $language)`: Get a specific post
- `getAvailableLanguages($slug)`: Get available languages for a post
- `getRelatedPosts($slug, $language, $limit)`: Get related posts

### 2. BlogController (`app/Http/Controllers/BlogController.php`)

Updated controller that:
- Uses BlogService for content retrieval
- Handles language parameters
- Provides data to views

### 3. Language Support

#### SetLocale Middleware (`app/Http/Middleware/SetLocale.php`)
- Automatically sets application locale based on request parameters
- Stores language preference in session
- Validates supported languages

#### LanguageController (`app/Http/Controllers/LanguageController.php`)
- Handles language switching
- Maintains context when switching languages
- Preserves blog page state

## URL Structure

### Blog Index
- English: `/blog` or `/blog?lang=en`
- French: `/blog?lang=fr`
- German: `/blog?lang=de`
- Italian: `/blog?lang=it`

### Blog Posts
- English: `/blog/post-slug` or `/blog/post-slug?lang=en`
- French: `/blog/post-slug?lang=fr`
- German: `/blog/post-slug?lang=de`
- Italian: `/blog/post-slug?lang=it`

## Language Switching

### Blog Index Page
- Language switcher displays all available languages
- Active language is highlighted
- Clicking a language loads the blog in that language

### Blog Post Page
- Language switcher shows available translations
- Disabled languages are grayed out if translation doesn't exist
- Maintains the same post across different languages

## Features

### 1. Automatic Content Processing
- **Markdown to HTML**: Converts markdown content to HTML
- **Excerpt Generation**: Auto-generates excerpts from content
- **Slug Generation**: Creates URL-friendly slugs from titles
- **Image Handling**: Processes markdown images with responsive classes

### 2. Multi-Language Support
- **Language Detection**: Automatically detects language from URL parameters
- **Fallback**: Falls back to English if requested language is not available
- **Session Persistence**: Remembers language preference across sessions

### 3. Content Management
- **File-Based**: Easy to manage with version control
- **No Database**: Content is stored in files, not database
- **Easy Editing**: Simple markdown format for content creators

### 4. SEO Friendly
- **Clean URLs**: SEO-friendly URL structure
- **Meta Tags**: Proper title and description tags
- **Language Tags**: Proper language attributes

## Adding New Content

### 1. Create Markdown File
Create a new `.md` file in the appropriate language directory:

```bash
# For English content
touch resources/content/blog/en/2025-12-01-new-post.md

# For French content
touch resources/content/blog/fr/2025-12-01-nouveau-article.md
```

### 2. Add Front Matter
Include the required metadata at the top of the file:

```markdown
---
title: "Your Post Title"
slug: "your-post-slug"
excerpt: "Brief description"
author: "Your Name"
published_at: "2025-12-01"
featured_image: "https://example.com/image.jpg"
tags: ["sailing", "tips"]
---

# Your Content Here

Write your markdown content...
```

### 3. Content Guidelines
- Use proper markdown syntax
- Include high-quality images
- Write engaging excerpts
- Use relevant tags
- Follow the file naming convention

## Adding New Languages

### 1. Create Language Directory
```bash
mkdir resources/content/blog/[language-code]
```

### 2. Update BlogService
Add the new language to the `$supportedLanguages` array:

```php
protected $supportedLanguages = ['en', 'fr', 'de', 'it', 'new-lang'];
```

### 3. Update Language Files
Create translation files in `lang/[language-code]/messages.php`

### 4. Update Views
Add the new language to language switchers in views

## Best Practices

### 1. Content Creation
- Write engaging, informative content
- Use proper markdown formatting
- Include relevant images
- Write compelling excerpts
- Use appropriate tags

### 2. File Organization
- Follow the naming convention
- Keep files organized by language
- Use descriptive filenames
- Maintain consistent metadata

### 3. Translation
- Ensure all languages have equivalent content
- Maintain consistent tone across languages
- Use proper language-specific formatting
- Test all language versions

### 4. Performance
- Optimize images for web
- Keep content files reasonably sized
- Use efficient markdown processing
- Cache content when appropriate

## Troubleshooting

### Common Issues

1. **Post Not Found**
   - Check file exists in correct language directory
   - Verify slug matches filename
   - Ensure proper front matter format

2. **Language Not Switching**
   - Check middleware is registered
   - Verify language is in supported list
   - Clear browser cache and session

3. **Content Not Displaying**
   - Check markdown syntax
   - Verify front matter format
   - Check file permissions

4. **Images Not Loading**
   - Verify image URLs are correct
   - Check image accessibility
   - Ensure proper markdown image syntax

## Future Enhancements

### Planned Features
- **Content Editor**: Web-based markdown editor
- **Image Management**: Automatic image optimization
- **Search**: Full-text search across languages
- **Categories**: Advanced categorization system
- **Comments**: Comment system integration
- **Analytics**: Content performance tracking

### Technical Improvements
- **Caching**: Implement content caching
- **CDN**: Content delivery network integration
- **API**: RESTful API for content management
- **Admin Panel**: Administrative interface
- **Backup**: Automated content backup system

## Conclusion

The multi-language blog system provides a robust, scalable solution for managing blog content across multiple languages. The file-based approach makes it easy to manage content with version control, while the markdown format ensures content creators can focus on writing rather than formatting.

The system is designed to be extensible and maintainable, with clear separation of concerns and comprehensive language support. Future enhancements can be easily integrated without disrupting the existing functionality.
