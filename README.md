# Zhihe Primary Posts Extension

[![Latest Stable Version](https://poser.pugx.org/zhihe/primary-posts/v/stable)](https://packagist.org/packages/zhihe/primary-posts)
[![Total Downloads](https://poser.pugx.org/zhihe/primary-posts/downloads)](https://packagist.org/packages/zhihe/primary-posts)
[![License](https://poser.pugx.org/zhihe/primary-posts/license)](https://packagist.org/packages/zhihe/primary-posts)

A Flarum extension that allows marking posts as primary content, perfect for serialized stories, tutorials, guides, and any multi-part content.

## Features

- **Primary Post Marking**: Mark posts as "primary content" vs comments/discussions
- **Visual Indicators**: Bookmark badges on primary posts for easy identification
- **Smart Filtering**: Toggle between "Show All" and "Primary Only" views
- **Author Controls**: Mark/unmark controls in post dropdown menus
- **Composer Integration**: Checkbox for marking posts as primary during creation
- **Bilingual Support**: Chinese (Simplified) and English translations

Perfect for:
- 📚 **Serialized stories** and episodic content
- 📖 **Tutorials** and educational series  
- 🛠️ **Development logs** and progress updates
- 📝 **Guides** and documentation
- 🎯 **Any multi-part content** where you want to distinguish main content from discussions

## Requirements

- Flarum 1.8.0+
- PHP 8.1+

## Installation

Install with Composer:

```bash
composer require zhihe/primary-posts
```

Enable the extension:

```bash
php flarum extension:enable zhihe-primary-posts
```

## Usage

### For Authors
- **Creating Posts**: Check the "Primary" checkbox when creating discussions or replies to mark them as primary content
- **Managing Posts**: Use the dropdown menu (three dots) on posts to mark/unmark as primary

### For Readers  
- **Filtering**: Use the sidebar toggle button to switch between "Show All" and "Primary Only" views
- **Visual Cues**: Primary posts display a bookmark icon in the header

## Database Schema

The extension adds two columns to the `posts` table:
- `is_primary`: Boolean flag for primary content
- `primary_number`: Sequential numbering for primary posts (future feature)

## Development

### Building Frontend Assets

```bash
cd js/
npm install
npm run build
```

### File Structure

```
zhihe-primary-posts/
├── src/                    # PHP backend code
├── js/                     # JavaScript frontend code  
├── locale/                 # Translation files
├── migrations/             # Database migrations
├── less/                   # CSS styling
├── composer.json          # Package configuration
└── extend.php             # Extension configuration
```

## License

MIT License - see LICENSE file for details

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Support

- **Issues**: [GitHub Issues](https://github.com/echolocked/zhihe-primary-posts/issues)
- **Documentation**: This README
- **Discussions**: [Flarum Community](https://discuss.flarum.org)

---

**Making every post count** 🔖✨