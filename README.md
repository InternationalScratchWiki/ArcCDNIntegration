## ArcCDNIntegration
Enable [Arc CDN](https://arc.io) on a MediaWiki installation.

## Configuration
Find your Arc widget ID. Arc tells you to add something like this to your site:
```html
<script async src="https://arc.io/widget.min.js#(WIDGET ID)"></script>
```
Find the `(WIDGET ID)` and configure the extension with it:
```php
wfLoadExtension( 'ArcCDNIntegration' );
$wgArcWidgetID = '(WIDGET ID)';
```
Optionally, you can choose to only enable Arc CDN for a certain proportion of users:
```php
$wgArcRolloutPercentage = 10; // 10% of users will be involved
```
