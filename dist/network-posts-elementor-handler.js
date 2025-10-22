/**
 * Network Posts Elementor Widget Handler
 *
 * Handles Masonry layout initialization for the Network Posts widget
 * using Elementor's built-in Masonry module.
 *
 * @since 1.0.0
 */
(function($) {
	'use strict';

	/**
	 * Initialize Masonry layout for Network Posts widget
	 *
	 * @param {jQuery} $scope - The widget wrapper element
	 */
	var NetworkPostsHandler = function($scope) {
		var $widget = $scope.find('.elementor-widget-container');
		var $container = $widget.find('.elementor-posts-masonry');

		// Only proceed if masonry container exists
		if (!$container.length) {
			return;
		}

		// Get widget settings
		var settings = $scope.data('settings');
		if (!settings) {
			console.warn('Network Posts: Widget settings not found');
			return;
		}

		// Check if masonry is enabled
		if (settings.masonry !== 'yes') {
			return;
		}

		// Get items within this specific container
		var $items = $container.find('.netsposts-content');

		if (!$items.length) {
			console.warn('Network Posts: No items found for masonry layout');
			return;
		}

		// Get current device mode and column count
		var currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
		var colsCount;

		switch (currentDeviceMode) {
			case 'mobile':
				colsCount = settings.columns_mobile || 1;
				break;

			case 'tablet':
				colsCount = settings.columns_tablet || 2;
				break;

			default:
				colsCount = settings.columns || 3;
		}

		// Get vertical spacing
		var verticalSpaceBetween = 0;
		if (settings.row_gap && settings.row_gap.size !== undefined && settings.row_gap.size !== '') {
			verticalSpaceBetween = parseInt(settings.row_gap.size);
		} else if (settings.item_gap && settings.item_gap.size !== undefined && settings.item_gap.size !== '') {
			verticalSpaceBetween = parseInt(settings.item_gap.size);
		}

		// Initialize Elementor's Masonry module
		try {
			var masonry = new elementorFrontend.modules.Masonry({
				container: $container,
				items: $items,
				columnsCount: colsCount,
				verticalSpaceBetween: verticalSpaceBetween
			});

			masonry.run();

			// Re-run masonry on window resize (debounced)
			var resizeTimer;
			$(window).on('resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function() {
					// Get new device mode after resize
					var newDeviceMode = elementorFrontend.getCurrentDeviceMode();
					var newColsCount;

					switch (newDeviceMode) {
						case 'mobile':
							newColsCount = settings.columns_mobile || 1;
							break;

						case 'tablet':
							newColsCount = settings.columns_tablet || 2;
							break;

						default:
							newColsCount = settings.columns || 3;
					}

					// Only reinitialize if column count changed
					if (newColsCount !== colsCount) {
						colsCount = newColsCount;
						masonry = new elementorFrontend.modules.Masonry({
							container: $container,
							items: $items,
							columnsCount: colsCount,
							verticalSpaceBetween: verticalSpaceBetween
						});
						masonry.run();
					}
				}, 250);
			});

		} catch (error) {
			console.error('Network Posts Masonry Error:', error);
		}
	};

	/**
	 * Initialize when Elementor frontend is ready
	 */
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/network_posts_widget.default',
			NetworkPostsHandler
		);
	});

})(jQuery);
