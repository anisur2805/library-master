# Library System Plugin

## Description

A plugin to manage a library system with custom SQL, caching, security, and modern front-end.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/library-master` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.

## Usage

The plugin provides a custom REST API for managing book records.

## Endpoints

- `GET /wp-json/library/v1/books`
- `POST /wp-json/library/v1/books`

## Development

### Front-End

The front-end is built using React and Tailwind CSS. To compile the assets:

```sh
cd frontend
npm install
npm run build
```

### Not able to do -
1. Paginate react frontend data
2. Cache not set

## To display frontend data with react
### use `shortcode` '['library-master']'