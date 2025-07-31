# Zhihe Primary Posts Extension

A Flarum extension for marking posts as primary content (正文) in serialized stories, designed for Chinese fanfiction platforms.

## Features

- **Primary Post Marking**: Mark posts as "正文" (primary content) vs comments
- **Visual Indicators**: Bookmark badges on primary posts
- **Smart Filtering**: Toggle between "Show All" and "Primary Only" views
- **Author Controls**: Mark/unmark controls in post dropdown menus
- **Composer Integration**: Checkbox for marking posts as primary during creation
- **Bilingual Support**: Chinese (Simplified) and English translations

## Installation

### For Development (Local Path)

1. Clone or download this extension to your workspace
2. Add to your Flarum's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../zhihe-primary-posts"
        }
    ]
}
```

3. Install the extension:

```bash
composer require zhihe/primary-posts
php flarum extension:enable zhihe-primary-posts
```

### For Production (Future)

```bash
composer require zhihe/primary-posts
php flarum extension:enable zhihe-primary-posts
```

## Usage

### For Authors
- **Creating Posts**: Check the "正文" checkbox when creating discussions or replies to mark them as primary content
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

- Issues: [GitHub Issues](https://github.com/zhihe/zhihe-primary-posts/issues)
- Documentation: This README
- Chinese fanfiction community: Zhihe (纸鹤书)